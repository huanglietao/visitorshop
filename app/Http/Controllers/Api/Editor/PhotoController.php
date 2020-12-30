<?php
namespace App\Http\Controllers\Api\Editor;

use App\Exceptions\CommonException;
use App\Http\Requests\Api\Photo\SavePhotoRequest;
use App\Repositories\SaasDiyImagesRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 用户照片相关接口
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/8
 */

class PhotoController extends BaseController
{
    /**
     * 保存用户上传的图片
     * @param SavePhotoRequest $photoRequest
     * @param SaasDiyImagesRepository $diyImage
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePhoto(SavePhotoRequest $photoRequest, SaasDiyImagesRepository $diyImage)
    {
        try {
            $params = $photoRequest->all();
            $data = [
                'photo_ph_id' => $params['ph_id'] ?? '',
                'photo_uid' => $params['uid'] ??0,
                'photo_mid' => $params['sp_id'] ??0,
                'photo_wid' => $params['wid'] ?? 0,
                'photo_org_name' => isset($params['org_name']) ? $params['org_name']:'',
                'photo_org_width' => isset($params['org_width']) ? $params['org_width']:0,
                'photo_org_height' => isset($params['org_height']) ? $params['org_height']:0,
                'photo_org_size' => isset($params['org_size']) ? $params['org_size']:0,
                'photo_ext' => isset($params['ext']) ? $params['ext']:'',
                'photo_url' => isset($params['url']) ? $params['url']:'',
                'album_id' => isset($params['album_id']) ? $params['album_id']:0,
                'created_at' => time(),
            ];

            $diyImage->insert($data);

            return $this->success(null);
        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 获取图片列表
     * @param Request $request
     * @param SaasDiyImagesRepository $diyImage
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetPhotoList(Request $request, SaasDiyImagesRepository $diyImage)
    {
        try {
            $wid = $request->input('wid');
            if (empty($wid)) {
                Helper::EasyThrowException("10022",__FILE__.__LINE__);
            }
            $info = $diyImage->getRows(['photo_wid' => $wid], 'photo_id');
            if (empty($info)) {
                Helper::EasyThrowException("10010",__FILE__.__LINE__);
            }
            $return = [] ;
            foreach ($info as $k=>$v) {
                $return[$k]['ph_id'] = $v['photo_ph_id'];
                $return[$k]['uid'] = $v['photo_uid'];
                $return[$k]['sp_id'] = $v['photo_mid'];
                $return[$k]['org_name'] = $v['photo_org_name'];
                $return[$k]['org_width'] = $v['photo_org_width'];
                $return[$k]['org_height'] = $v['photo_org_height'];
                $return[$k]['org_size'] = $v['photo_org_size'];
                $return[$k]['ext'] = $v['photo_ext'];
                $return[$k]['url'] = $v['photo_url'];
                $return[$k]['add_time'] = $v['created_at'];
            }

            return $this->success([['list' => $return]]);

        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 删除图片
     * @param Request $request
     * @param SaasDiyImagesRepository $diyImage
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeletePhotos(Request $request, SaasDiyImagesRepository $diyImage)
    {
        try {
            $phIds = $request->input('ph_ids');
            if (empty($phIds)) {
                Helper::EasyThrowException("10022",__FILE__.__LINE__);
            }
            $photoIdsArr = explode(',',$phIds);
            $result = $diyImage->WhereIn('photo_ph_id',$photoIdsArr)->toArray();

            if (!empty($result)) {
                foreach ($result as $k=>$v) {
                    $diyImage->delete($v['photo_id']);
                }
                return $this->success([]);
            }else{
                return $this->error('100100','该照片id无效，无法删除！');
            }



        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }


}