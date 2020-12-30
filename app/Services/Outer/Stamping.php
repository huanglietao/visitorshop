<?php
namespace App\Services\Outer;

use App\Models\DmsAgentInfo;
use App\Models\SaasProductsPrint;
use App\Models\SaasSizeInfo;
use App\Models\SaasTbOrderImage;
use App\Models\SaasTbOrderPicQueue;
use App\Repositories\SaasProductsSkuRepository;
use App\Services\Helper;
use Intervention\Image\ImageManager;
use OSS\Core\OssException;


/**
 * 冲印图片相关
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/12
 */
class Stamping
{

    /**
     * 获取oss信息
     */
    public function getOss()
    {
        return ['ossKey'=>env('ALI_SDK_ACCESS_KEY_B2'),'ossSecret'=>env('ALI_SDK_ACCESS_SECRET_B2'),'ossHost'=>env('ALI_OSS_HOST')];
    }

    /**
     * 获取oss保存路径
     */
    public function getOssDir()
    {
        $first = 'm02';
        $second = 'y'.date('y');
        $third = 'm'.date('md');
        $four = mt_rand(100000,999999);

        return $first.'/'.$second.'/'.$third.'/'.$four;
    }

    /**
     * 淘宝图片处理并上传oss
     * param $ossCilent oss对象 $url图片地址 $img_info图片完整路径
     */
    public function ossUpload($ossCilent, $url, $img_info)
    {

        $path = './test'.mt_rand(10000,99999).time().'.jpg';
        $new_path = './test1'.mt_rand(10000,99999).time().'.jpg';
        $str = $this->file_get_content($url);
        $res = file_put_contents($path,$str);
        $size = strlen($str);

        //图片改尺寸
        $y_info = getimagesize($path);
        $allow_crop = array(
            IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_GIF
        );

        if(in_array($y_info[2],$allow_crop)) {
            $manager = new ImageManager();
            $image = $manager->make($path);
            if($y_info[0] > $y_info[1]){
                $image->resize(2000,null,function ($constraint) {
                    $constraint->aspectRatio();
                });
            }else{
                $image->resize(null,2000,function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $image->save($new_path);
        }
        unlink($path);
        $info = getimagesize($new_path);

        $bucket = "yd-p2";
        try{
            $ossCilent->uploadFile($bucket, $img_info, $new_path);
            unlink($new_path);

            $big = "http://img2.meiin.com/".$img_info."?x-oss-process=image/auto-orient,1";
            $mid = "http://img2.meiin.com/".$img_info."?x-oss-process=image/resize,h_800,w_800,limit_0/auto-orient,1";
            $sml = "http://img2.meiin.com/".$img_info."?x-oss-process=image/resize,h_150,w_150,limit_0/auto-orient,1";

            $origwidth = $info[0];
            $origheight = $info[1];

            //返回图片路径
            $arr = array(
                'big' => $big,
                'mid' => $mid,
                'sml' => $sml,
                'origsize' =>  $size,
                'origwidth' => $origwidth,
                'origheight' => $origheight,

            );
            return $arr;
        } catch(OssException $e) {
            echo($e->getMessage());
            return false;
        }
    }

    /**
     * 下载图片
     */
    public function file_get_content($url) {
        if (function_exists('file_get_contents')) {
            $file_contents = @file_get_contents($url);
        }
        if ($file_contents == '') {

            $ch = curl_init();
            $timeout = 60;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);

            curl_close($ch);
        }
        return $file_contents;
    }

    /**
     * 通过淘宝订单号获取货品id和商品id
     * param $tid
     */
    public function getSkuByTid($tid)
    {
        //Model
        $pic_queue_model = app(SaasTbOrderPicQueue::class);
        $dms_info_model = app(DmsAgentInfo::class);
        $tb_image_model = app(SaasTbOrderImage::class);

        //Repository
        $sku_repository = app(SaasProductsSkuRepository::class);

        //取货号
        $prod_sku_sn = $tb_image_model->where(['tid'=>$tid])->value('outerSkuId');

        //取mch_id
        $agent_id = $pic_queue_model->where(['tid'=>$tid])->value('agent_id');
        $mch_id = $dms_info_model->where('agent_info_id',$agent_id)->value('mch_id');

        //返回商品id和货品id
        $info = $sku_repository->getProdSkuId($prod_sku_sn,$mch_id);
        $info[0]['mch_id'] = $mch_id;

        return $info;
    }

    /**
     * 横竖图情况处理
     * param $data oss图片数据 $ids[0] 商品prod_id，货品prod_sku_id，商户mch_id
     */
    public function getDiyImgData($data,$ids)
    {
        //Model
        $prod_print_model = app(SaasProductsPrint::class);
        $size_info_model = app(SaasSizeInfo::class);

        //取商品规格
        $size_id = $prod_print_model->where(['prod_id'=>$ids['prod_id'],'mch_id'=>$ids['mch_id']])->value('prod_size_id');
        $size_info = $size_info_model->where(['goods_id'=>$ids['prod_id'],'size_id'=>$size_id,'size_type'=>GOODS_SIZE_TYPE_INNER])->first();
        if(empty($size_info)){
            $size_info = $size_info_model->where(['goods_id'=>0,'size_id'=>$size_id,'size_type'=>GOODS_SIZE_TYPE_INNER])->first();
        }else{
            //商品规格不存在
            Helper::EasyThrowException(40015,__FILE__.__LINE__);
        }

        $orig_width = $data['origwidth'];
        $orig_height = $data['origheight'];
        if($size_info['size_design_w'] / $size_info['size_design_h'] > 1){
            //商品横图
            if($data['origwidth'] / $data['origheight'] < 1){
                //冲印竖图翻转为横图
                $data['big'] = $data['big']."/rotate,270";
                $data['mid'] = $data['mid']."/rotate,270";
                $data['sml'] = $data['sml']."/rotate,270";
                $data['origwidth'] = $orig_height;
                $data['origheight'] = $orig_width;
            }
        }else{
            //商品竖图
            if($data['origwidth'] / $data['origheight'] > 1){
                //冲印横图翻转为竖图
                $data['big'] = $data['big']."/rotate,270";
                $data['mid'] = $data['mid']."/rotate,270";
                $data['sml'] = $data['sml']."/rotate,270";
                $data['origwidth'] = $orig_height;
                $data['origheight'] = $orig_width;
            }
        }
        return $data;
    }
}
