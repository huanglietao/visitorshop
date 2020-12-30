<?php
namespace App\Http\Controllers\Backend\Salary;

use App\Exceptions\CommonException;
use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Salary\DetailRequest;
use App\Repositories\SaasSalaryCalculationRepository;
use App\Repositories\SaasSalaryDetailRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * 项目说明 职工管理--薪酬列表
 * 详细说明
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date: 2020/06/30
 */
class DetailController extends BaseController
{
    protected $viewPath = 'backend.salary.detail';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasSalaryCalculationRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->shift = ['1'=>'白班','2'=>'夜班'];
    }


    public function index()
    {

        //获取职位
        $position_arr = config('salary.position_setting');

        return view('backend.salary.detail.index',['position'=>$position_arr,'shift'=>$this->shift]);
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {
        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs,"created_at desc");
        $htmlContents = $this->renderHtml('',['list' =>$list,'shift'=>$this->shift]);
        $pagesInfo = $list->toArray();
        $total = $pagesInfo['total'];
        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    //数据导入
    public function import()
    {
        try{
            \DB::beginTransaction();
            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                $filename = $_FILES['file']['name'];
                $ext_list = explode(".",$filename);
                $ext = $ext_list[count($ext_list)-1];
                if($ext=='xls'){
                    $ext = 'Xls';
                }elseif($ext=='xlsx'){
                    $ext = 'Xlsx';
                }
                // 有Xls和Xlsx格式两种
                $objReader = IOFactory::createReader($ext);

                $fileTmpname = $_FILES['file']['tmp_name'];
                $objPHPExcel = $objReader->load($fileTmpname);  //$filename可以是上传的表格，或者是指定的表格

                $sheetCount = $objPHPExcel->getSheetCount();
                $snum = 0;
                $total = 0;
                for ($i=0;$i<$sheetCount;$i++){
                    $sheet = $objPHPExcel->getSheet($i);   //excel中的第一张sheet
                    $highestRow = $sheet->getHighestRow();       // 取得总行数

                    $salaryDetailRepository = app(SaasSalaryDetailRepository::class);
                    for ($row = 3; $row <= $highestRow; $row++) {
                        $personals = [];
                        //日期
                        $finish_time = $sheet->getCell('A'.$row)->getValue();
                        //格式化客户订单时间
                        if($finish_time){
                            $n = intval(($finish_time - 25569) * 3600 * 24); //转换成1970年以来的秒数
                            $finish_time = gmdate('Y-m-d H:i:s',$n);//格式化时间,不是用date哦, 时区相差8小时的
                        }
                        $finish_time = strtotime($finish_time);
                        //班次
                        $shift = $sheet->getCell('B'.$row)->getCalculatedValue();
                        if(strpos($shift,'白') !== false){
                            $shift = ONE;
                        }
                        if(strpos($shift,'夜') !== false){
                            $shift = 2;
                        }
                        //机台
                        $mchine = $sheet->getCell('C'.$row)->getCalculatedValue();
                        //筐数
                        $basket = $sheet->getCell('D'.$row)->getCalculatedValue();
                        //产出数量
                        $output = $sheet->getCell('E'.$row)->getCalculatedValue();
                        //上报合格数量
                        $qualified = $sheet->getCell('F'.$row)->getCalculatedValue();
                        //废品数量
                        $scrap = $sheet->getCell('G'.$row)->getCalculatedValue();
                        //待修数量
                        $repair = $sheet->getCell('H'.$row)->getCalculatedValue();
                        //自用数量
                        $personal = $sheet->getCell('I'.$row)->getCalculatedValue();
                        //打片数量
                        $slice = $sheet->getCell('J'.$row)->getCalculatedValue();
                        //实际合格数量
                        $actually_qualified = $sheet->getCell('K'.$row)->getCalculatedValue();
                        //待修数量
                        $actually_repair = $sheet->getCell('L'.$row)->getCalculatedValue();
                        //自用数量
                        $actually_personal = $sheet->getCell('M'.$row)->getCalculatedValue();
                        //不合格数量
                        $unqualified = $sheet->getCell('N'.$row)->getCalculatedValue();
                        //内销数量
                        $sold_inside = $sheet->getCell('O'.$row)->getCalculatedValue();
                        //生产人员
                        $personals[] = $sheet->getCell('P'.$row)->getValue();
                        $personals[] = $sheet->getCell('Q'.$row)->getValue();
                        $personals[] = $sheet->getCell('R'.$row)->getValue();
                        $personals[] = $sheet->getCell('S'.$row)->getValue();
                        $personals[] = $sheet->getCell('T'.$row)->getValue();
                        $personals[] = $sheet->getCell('U'.$row)->getValue();
                        $personals[] = $sheet->getCell('V'.$row)->getValue();
                        $personals[] = $sheet->getCell('W'.$row)->getValue();

                        //生产人员 数据字符串化
                        $prod_personnel = implode(",",array_filter($personals));
                        //产量
                        $output_totals = $sheet->getCell('X'.$row)->getCalculatedValue();
                        //单价
                        $univalence = $sheet->getCell('Y'.$row)->getCalculatedValue();

                        //存入表中的数据组装
                        $salary_detail_data = [
                            'finish_time'           =>  $finish_time,           //日期
                            'shift'                 =>  $shift,                 //班次
                            'mchine'                =>  $mchine,                //机台
                            'basket'                =>  $basket??0,             //筐数
                            'output'                =>  $output??0,             //产出数量
                            'qualified'             =>  $qualified??0,          //上报合格数量
                            'scrap'                 =>  $scrap??0,              //废品数量
                            'repair'                =>  $repair??0,             //待修数量
                            'personal'              =>  $personal??0,           //自用数量
                            'slice'                 =>  $slice??0,              //打片数量
                            'actually_qualified'    =>  $actually_qualified??0, //实际合格数量
                            'actually_repair'       =>  $actually_repair??0,    //待修数量
                            'actually_personal'     =>  $actually_personal??0,  //自用数量
                            'unqualified'           =>  $unqualified??0,        //不合格数量
                            'sold_inside'           =>  $sold_inside??0,        //内销数量
                            'prod_personnel'        =>  $prod_personnel,        //生产人员
                            'output_totals'         =>  $output_totals??0,      //产量
                            'univalence'            =>  $univalence??0          //单价
                        ];

                        //判断该条数据是否已经存在
                        $md5_code = md5(json_encode($salary_detail_data));
                        $isExit = $salaryDetailRepository->isExit($md5_code);
                        if(empty($isExit)){
                            //存入数据表中
                            $salary_detail_data['md5_code'] = $md5_code;
                            $ret = $salaryDetailRepository->save($salary_detail_data);
                            if(!$ret){
                                \DB::rollBack();
                                return $this->jsonFailed("数据保存出错，已保存0条数据!");
                            }
                            $total+=1;
                        }
                    }
                    $snum += 1;
                }
                \DB::commit();
                return $this->jsonSuccess("已成功导入".$snum."张表格,共".$total."条数据！已存在的数据不会再次保存");
            }
        }catch(CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }
    }


    //导出
    public function export(Request $request)
    {
        $info = $request->get('info');
        $inputs = json_decode($info,true);
        $finish = $inputs['finish_time'];
        $time = Helper::getTimeRangedata($finish);

        $list = $this->repositories->getExportTableList($inputs,"created_at desc");

        $column = ['D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X',
                    'Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI'];

        for($i=$time['start'];$i<=$time['end'];){
            $times[] = $i;
            $i +=24*60*60;
        }

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('薪酬明细表');  //设置当前sheet的标题

        //设置自动列宽
        $objSheet->getColumnDimension('A')->setWidth(15);
        $objSheet->getColumnDimension('B')->setWidth(15);
        $objSheet->getColumnDimension('C')->setWidth(10);
        $objSheet->setCellValue('A1', '姓名')
            ->setCellValue('B1', '职位')
            ->setCellValue('C1', '职位系数');
        foreach ($times as $k=>$v){
            $date = date('m/d',$v);
            $objSheet->setCellValue($column[$k].'1', $date);
        }

        $index = 2;
        foreach ($list['data'] as $key => $value) {
            $objSheet->setCellValue('A' . $index, $key)
                ->setCellValue('B' . $index, $list['position'][$key]['name'])
                ->setCellValue('C' . $index, $list['position'][$key]['rate']);

            foreach ($times as $k=>$v){
                if(isset($value[$v])){
                    $salary = $value[$v];
                }else{
                    $salary = 0.00;
                }
                $objSheet->setCellValue($column[$k].$index, $salary);
            }

            $index +=1;
        }

        $this->downloadExcel($newExcel, "薪酬明细表", 'Xls');

    }

    //公共文件，用来传入xls并下载
    public function downloadExcel($newExcel, $filename, $format)
    {
        // $format只能为 Xlsx 或 Xls
        if ($format == 'Xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        } elseif ($format == 'Xls') {
            header('Content-Type: application/vnd.ms-excel');
        }

        header("Content-Disposition: attachment;filename=". $filename . date('Y-m-d') . '.' . strtolower($format));
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($newExcel, $format);

        $objWriter->save('php://output');

    }

   //添加/编辑操作
    public function save(DetailRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}