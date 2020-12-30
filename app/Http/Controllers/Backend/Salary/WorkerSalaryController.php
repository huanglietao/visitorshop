<?php
namespace App\Http\Controllers\Backend\Salary;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Controllers\Controller;
use App\Models\SaasSalaryCalculation;
use App\Models\SaasSalaryDetail;
use App\Models\SaasSalaryWorker;
use App\Repositories\SaasSalaryDetailRepository;
use App\Repositories\SaasSalaryWorkerRepository;
use foo\bar;
use Illuminate\Http\Request;
use App\Exceptions\CommonException;
use App\Services\Helper;

/**
 * 职工管理->职工薪酬计算
 *
 * @author: hlt
 * @version: 1.0
 * @date: 2020-06-29
 */
class WorkerSalaryController extends Controller
{
//    protected $viewPath = 'backend.salary.worker';  //当前控制器所的view所在的目录
//    protected $modules = 'sys';        //当前控制器所属模块
//
//    public function __construct(SaasSalaryWorkerRepository $repository)
//    {
//        parent::__construct();
//        $this->repositories  = $repository;
//        $this->positionList = $this->repositories->getPositionList();
//    }

    //工人的当日薪资计算
    public function salaryCalculation()
    {


        $salaryModel = app(SaasSalaryDetail::class);
        $salaryDetailRepository = app(SaasSalaryDetailRepository::class);
        $salaryDetailModel = app(SaasSalaryDetail::class);
        $salaryLimit = config('salary.salary_limit');
        //获取需计算的记录条数
        $salaryInfo = $salaryDetailModel->where('cal_status','ready')->limit($salaryLimit)->get()->toArray();
        //将计算状态改为progress
        foreach ($salaryInfo as $k=> $v){
            $salaryDetailModel->where('salary_detail_id',$v['salary_detail_id'])->update(['cal_status' => 'progress','start_time'=>time()]);
        }
        $this->runCalculation($salaryInfo);
    }

    //计算薪酬
    public function runCalculation($salaryInfo)
    {
        $salaryDetailModel = app(SaasSalaryDetail::class);
        $salaryConfig = config('salary.position_setting');
        $workerModel = app(SaasSalaryWorker::class);
        $calculationModel = app(SaasSalaryCalculation::class);
        foreach ($salaryInfo as $k => $v)
        {
        try{
            try {
                    \DB::beginTransaction();
                    if (!empty($v['prod_personnel']))
                    {
                        $workerInfo = explode(',',$v['prod_personnel']);
                        $workersCount = count($workerInfo);
                        $allCoef = 0;
                        $allCoefCount = 0;

                        //获取改班次的总产量跟单价
                        if (empty($v['output_totals'])){
                            //没有总产量，就取实际合格数量
                            $total = $v['actually_qualified'];
                        }else{
                            $total = $v['output_totals'];
                        }
                        if (empty($total)){
                            //总产量为0
                            Helper::EasyThrowException(25002,__FILE__.__LINE__);//产量为0
                        }
                        //获取单价
                        if (empty($v['univalence'])){
                            //没有单价,则根据产量匹配单价
                            //10000-20000 : 0.002
                            //20000-30000 : 0.0025
                            //>=30000     : 0.003
                            $univalence = 0.002;
                            if ($total>=20000 && $total<30000){
                                $univalence = 0.0025;
                            }elseif ($total>=30000){
                                $univalence = 0.003;
                            }
                        }else{
                            $univalence = $v['univalence'];
                        }

                        //先获取该班次员工的总系数
                        foreach ($workerInfo as $k_w => $v_w)
                        {
                            $workerArr = $workerModel->where('salary_worker_name',$v_w)->first();
                            if (!empty($workerArr)){

                                if (isset($salaryConfig[$workerArr['salary_worker_position']])){
                                    ++$allCoefCount;
                                    $workCoef = $salaryConfig[$workerArr['salary_worker_position']]['rate'];
                                    $allCoef +=$workCoef;
                                }
                            }else{
                                Helper::EasyThrowException(25004,__FILE__.__LINE__);//未录入员工表
                            }
                        }

                        //判断是否每个员工都有系数
                        if ($allCoefCount != $workersCount){
                            //员工系数不匹配，有员工没有岗位系数
                            Helper::EasyThrowException(25005,__FILE__.__LINE__);//有员工没有岗位系数
                        }
                        $calcData = [];

                        foreach ($workerInfo as $kk => $vv)
                        {
                            //计算每个人工资
                            /*$data = [
                                'salary_worker_name' => $vv,
                                'salary_worker_position' => 3,
                                'created_at' => time()
                            ];
                            app(SaasSalaryWorker::class)->insert($data);*/
                            $workerArr = $workerModel->where('salary_worker_name',$vv)->first();
                            if (empty($workerArr)){
                                //直接出错,不计算薪酬
                                Helper::EasyThrowException(25004,__FILE__.__LINE__);//未录入员工表
                            }else{
                                if (isset($salaryConfig[$workerArr['salary_worker_position']])){
                                    //获取该员工的薪酬计算系数
                                    $workCoef = $salaryConfig[$workerArr['salary_worker_position']]['rate'];

                                    //机长按照1.1的系数分配，助手、普工全勤（不缺工时）系数为1,公式即 产量*岗位系数*出勤率系数；
                                    //计算这个员工该班次的工资
                                    //$total*$univalence*$workersCount得到5个人产量的总工资
                                    //$total*$univalence*$workersCount/$allCoef得到岗位系数每个基点的工资
                                    //$total*$univalence*$workersCount/$allCoef*$workCoef得到该岗位系数员工的工资
                                    $thisSalary = $total*$univalence*$workersCount/$allCoef*$workCoef;
                                    //四舍五入保留两位小数
                                    $thisSalary = round($thisSalary,2);
                                    //录入员工工资表
                                    $calcData[] = [
                                        'salary_detail_id'       => $v['salary_detail_id'],
                                        'workers_name'           => $vv,
                                        'salary_worker_position' => $workerArr['salary_worker_position'],
                                        'shift'                  => $v['shift'],
                                        'finish_time'            => $v['finish_time'],
                                        'output_totals'          => $total,
                                        'univalence'             => $univalence,
                                        'salary'                 => $thisSalary,
                                        'created_at'             => time(),
                                    ];
                                }else{
                                    Helper::EasyThrowException(25005,__FILE__.__LINE__);//有员工找不到职位等级
                                }
                            }
                        }
                        //只有该班次的所有员工都计算出工资才会插入工资表
                        if (isset($calcData) && !empty($calcData))
                        {
                            $calculationModel->insert($calcData);
                        }

                    }
                $salaryDetailModel->where('salary_detail_id',$v['salary_detail_id'])->update(['cal_status' => 'finish','err_msg'=>'','end_time' => time()]);
                    \DB::commit();

            } catch (\Exception $exception){
                throw new CommonException($exception->getMessage(),$exception->getCode(),false,'all',[__FILE__.__LINE__.$exception->getMessage()]);
            }
        }catch (CommonException $e) {
            //队列错误
            if ($e->getCode()!=0){
                \DB::commit();
            }else{
                \DB::rollBack();
            }
            $salaryDetailModel->where('salary_detail_id',$v['salary_detail_id'])->update(['cal_status' => 'error','err_msg'=>$e->getMessage(),'end_time' => time()]);
        }
        }
    }



}