<?php
namespace App\Http\Controllers\Api;

use App\Jobs\AccessLog;


/**
 * api控制器的基类
 *
 * 继承App\Http\Controllers基类，实现api的
 * 公用的一些功能
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/9/3
 */
class BaseController extends \App\Http\Controllers\BaseController
{
    protected $modules = 'sys';  //当前功能所属模块
    protected $sysId = 'api'; //当前子系统 默认为大后台
    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        //把当前模块注入容器
        app()->instance('sys_id',$this->sysId);
        app()->instance('modules',$this->modules);

        $noLogArr = explode(',',$this->noLog);

        //获取当前的方法
        $nowFunction = $this->getControllerAndFunction();

        //判断当前方法是否要记入操作日志
        if (!in_array($nowFunction['method'],$noLogArr)){

            //记录所有请求的_GET与_POST数据作为日志(异步,走队列)
            if(config("app.access_log_enable")) {
                //记日志放入队列
                $data = [
                    'sys'         => $this->sysId,  //所属系统
                    'modules'     => $this->modules, //所属模块
                    'router'      => \Request::getRequestUri(), //当前路由
                    'data'        => ['get'=>$_GET, 'post'=>$_POST],
                    'ip' => \Request::getClientIp(),
                    'add_time'    => time()
                ];
                AccessLog::dispatch($data)->onQueue('logs');
            }

        }
    }

    /**
     * 格式化接口正确返回
     * @param $data
     * @return json
     */
    public function success($data)
    {
        return response()->json(['success' => 'true', 'result' => $data]);
    }

    /**
     * 接口错误返回
     * @param $errCode
     * @param $errMsg
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($errCode, $errMsg)
    {
        return response()->json(['success' => 'false','err_code'=>$errCode,  'err_msg' => $errMsg]);
    }

    /**
     * 标准化规格相关的数据
     * @param $sizeInfo 从数据库取出的记录
     * @return array
     */
    protected function formatSizeInfo($sizeInfo)
    {
        $info = [
            'id'        => $sizeInfo['size_id'],
            'name'      => $sizeInfo['size_name'],
            'remark'    => $sizeInfo['size_desc'],
            'type_id'   => $sizeInfo['size_cate_id'],
            'dpi'       => $sizeInfo['size_dpi'],
            //'spread'    => $sizeInfo['size_dpi'],
        ];
        $info['cover_info'] = $info['inpage_info'] = $info['back_info'] = '';
        if (isset($sizeInfo['detail_list'])) {
            foreach ($sizeInfo['detail_list'] as $k=>$v) {
                //封面和封面-封底对应 cover_info
                if ($v['size_type'] == GOODS_SIZE_TYPE_COVER || $v['size_type'] == GOODS_SIZE_TYPE_COVER_BACK)
                {
                    $info['cover_info'] = $this->formatSizeDetail($v);

                } else if($v['size_type'] == GOODS_SIZE_TYPE_BACK) {
                    $info['back_info'] = $this->formatSizeDetail($v);
                } else {
                    $info['inpage_info'] = $this->formatSizeDetail($v);
                }
            }
        }


        return $info;
    }

    /**
     * 标准化规格详情数据
     * @param $detail 规格详情的数据
     * @return array
     */
    protected function formatSizeDetail($detail)
    {
        return [
            'id'                    => $detail['size_info_id'],
            'top_layer_width'       => $detail['size_design_w'] + $detail['size_location_left'] + $detail['size_location_right'] , //效果上宽度（毫米）
            'top_layer_height'      => $detail['size_design_h'] + $detail['size_location_top'] + $detail['size_location_bottom'],    //效果上高度（毫米）
            'design_layer_width'    => $detail['size_design_w'],    //设计区宽度（毫米）
            'design_layer_height'   => $detail['size_design_h'],    //设计区高度（毫米）
            'design_layer_left'     => $detail['size_location_left'],    //设计区左偏移（毫米）
            'design_layer_top'      => $detail['size_location_top'],       //设计区上偏移（毫米）
            'tip_left'              => $detail['size_tip_left'],    //提示线左偏移（毫米）
            'tip_right'             => $detail['size_tip_right'],    //提示线右偏移（毫米）
            'tip_top'               => $detail['size_tip_top'],    //提示线上偏移（毫米
            'tip_bottom'            => $detail['size_tip_bottom'],    //提示线下偏移（毫米）
            'bleed_left'            => $detail['size_cut_left'],    //出血线左偏移（毫米）
            'bleed_right'           => $detail['size_cut_right'],    //出血线右偏移（毫米）
            'bleed_top'             => $detail['size_cut_top'],    //出血线上偏移（毫米）
            'bleed_bottom'          => $detail['size_cut_bottom'],    //出血线下偏移（毫米）
            'spread'                => $detail['size_is_cross'],    //是否跨页，0：非跨页，1：跨页
            'compound'              => $detail['size_is_output'],    //是否参与合成，0：不参与，1：参与
            'editable'              => $detail['size_is_locked'],    //能否编辑，0：不能，1：能
            'displayable'           => $detail['size_is_display'],    //能否可显示，0：不能，1：能
            'front_and_back'        => $detail['size_is_2faced'], //0单面印刷 1 双面印刷
        ];
    }

}