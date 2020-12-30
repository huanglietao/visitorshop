<?php
namespace App\Http\Controllers\Merchant\Agent;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Agent\InfoRequest;
use App\Presenters\CommonPresenter;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\DmsFinanceDocRepository;
use App\Repositories\DmsMerchantAccountRepository;
use App\Repositories\SaasCustomerLevelRepository;
use App\Repositories\SaasOrdersRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * 项目说明 OMS系统 分销管理--推广订单
 * 详细说明 OMS系统 分销管理--推广订单
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/08/11
 */
class InviterController extends BaseController
{
    protected $viewPath = 'merchant.agent.inviter';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';


    public function __construct(SaasOrdersRepository $ordersRepository,DmsAgentInfoRepository $agentInfoRepository,CommonPresenter $commonPresenter)
    {
        parent::__construct();
        $this->repositories = $ordersRepository;
        $this->agentInfoRepository = $agentInfoRepository;
        $this->commonPresenter = $commonPresenter;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->agentInfo = $agentInfoRepository->getList(['mch_id'=>$this->merchantID])->toArray();
    }


    /**
     * 功能首页结构view
     * @return mixed
     */
    public function index()
    {
        $info = Helper::ListToKV('agent_info_id','agent_name',$this->agentInfo);
        $info = [0=>'请选择']+$info;
        return view('merchant.agent.inviter.index',['info'=>$info]);
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();
        //得到被邀请的商户的id
        $result = $this->agentInfoRepository->getInfoIds($this->merchantID);
        $inviters = $result['name'];
        if(isset($inputs['inviter_id']) && !empty($inputs['inviter_id'])){
            $ids = $this->agentInfoRepository->getList(['inviter_id'=>$inputs['inviter_id']])->toArray();
            if(empty($ids)){
                $inputs['user_ids'] = [ZERO];
            }else{
                foreach ($ids as $k=>$v){
                    $user_ids[] = $v['agent_info_id'];
                }
                $inputs['user_ids'] = $user_ids;
            }
            unset($inputs['inviter_id']);
        }else{
            $inputs['user_ids'] = empty($result['ids'])==false ? $result['ids']:[ZERO];
        }
        $list = $this->repositories->getInviterTableList($inputs);

        $htmlContents = $this->renderHtml('',['list' =>$list['data'],'inviters'=>$inviters]);
        $total = $list['total'];
        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }


    //订单统计导出
    public function export(Request $request)
    {
        $search = $request->get('info');
        $params = (array)json_decode($search);
        //得到被邀请的商户的id
        $result = $this->agentInfoRepository->getInfoIds($this->merchantID);
        $inviters = $result['name'];
        if(isset($params['inviter_id']) && !empty($params['inviter_id'])){
            $ids = $this->agentInfoRepository->getList(['inviter_id'=>$params['inviter_id']])->toArray();
            if(empty($ids)){
                $params['user_ids'] = [ZERO];
            }else{
                foreach ($ids as $k=>$v){
                    $user_ids[] = $v['agent_info_id'];
                }
                $params['user_ids'] = $user_ids;
            }
            unset($params['inviter_id']);
        }else{
            $params['user_ids'] = $result['ids'];
        }

        $Info =  $this->repositories->getOrderTableList($params,'created_at desc',ONE);

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('推广订单表');  //设置当前sheet的标题

        //设置自动列宽
        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setAutoSize(true);
        $objSheet->getColumnDimension('C')->setWidth(10);
        $objSheet->getColumnDimension('D')->setWidth(15);
        $objSheet->getColumnDimension('E')->setWidth(10);
        $objSheet->getColumnDimension('F')->setWidth(25);
        $objSheet->getColumnDimension('G')->setWidth(25);

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '订单号')
            ->setCellValue('B1', '交易时间')
            ->setCellValue('C1', '总数量')
            ->setCellValue('D1', '订单状态')
            ->setCellValue('E1', '实收款')
            ->setCellValue('F1', '来源')
            ->setCellValue('G1', '邀请人');


        foreach ($Info['data'] as $k=>$v) {
            $k=$k+2;
            $objSheet->setCellValue('A' . $k, $v['order_no']."\t")
                ->setCellValue('B' . $k, $this->commonPresenter->exchangeTime($v['created_at']))
                ->setCellValue('C' . $k, $v['nums']."件")
                ->setCellValue('D' . $k, $this->commonPresenter->exchangeOrderStatus($v['order_status']))
                ->setCellValue('E' . $k, $v['order_real_total'])
                ->setCellValue('F' . $k, $v['agent_name']."【".$v['cha_name']."】")
                ->setCellValue('G' . $k, $inviters[$v['user_id']]);

        }

        $this->downloadExcel($newExcel, "推广订单表", 'Xls');

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

}
