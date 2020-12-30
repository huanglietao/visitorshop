<?php
namespace App\Http\Controllers\Api\Editor;

use App\Exceptions\CommonException;
use App\Repositories\SaasAreasRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 全局接口
 *
 * 获取服务器时间，获取省市信息等
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/27
 */
class GlobalController extends BaseController
{
    /**
     * 获取服务器时间
     */
    public function getServerTime()
    {
        $data = ['timestamp' => time()];
        return $this->success([$data]);
    }

    /**
     * 获取地区列表的信息
     * @param Request $request
     * @param SaasAreasRepository $repoArea
     * @return mixed
     */
    public function getLocationList(Request $request, SaasAreasRepository $repoArea)
    {
        try {
            $areaId = $request->input('id');
            //等级1=省份，2=市，3=区
            $level  = $request->input('getType');
            if (empty($level)) {
                Helper::apiThrowException('161001',__FILE__.__LINE__,$request->all());
            }
            if (empty($areaId)) {
                $where[] = ['level' , AREA_LEVEL_PROVINCE];
            } else {
                $where[] = ['level' , $level];
                $where[] = ['pid' , $areaId];
            }
            $list = $repoArea->getList($where, 'area_id', 'asc');

            $info = [];
            foreach ($list as $k=>$v) {
                $info[$k]['id']    = $v['area_id'];
                $info[$k]['name']  = $v['area_name'];
            }

            return $this->success([['list'=>$info ]]);
        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 获取oss上传相关的签名
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUploadSign(Request $request)
    {
        $string_to_sign = $request->input('str');
        $key = config('common.oss_key');
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $return['sign'] = $signature;
        return $this->success([$return]);
    }
}