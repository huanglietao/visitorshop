<?php
namespace App\Services\Outer;

use App\Models\SaasTbDiyImage;
use App\Models\SaasTbOrderImage;
use App\Models\SaasTbOrderMessage;
use App\Models\SaasTbOrderPicQueue;
use App\Services\Helper;
use App\Exceptions\CommonException;
use OSS\OssClient;


/**
 * 针对天猫接口的api
 * @author: hlt <1013488674@qq.com>
 * @version: 1.0
 * @date: 2020/6/11
 */
class Tmall
{
    //同步外部订单图片
    public function syncOrderImages()
    {
        //获取每次跑队列条数
        $limit = config("common.queue_limit.sync_order_images");
        $tbMessageModel = app(SaasTbOrderMessage::class);
        $imageInfo = $tbMessageModel->where(['msg_topic' => 'taobao_trade_TradeCreate','pic_queue_status'=>0])->limit($limit)->get()->toArray();

        $helper = app(Helper::class);

        foreach ($imageInfo as $k => $v)
        {

            //这里只有爱美印的淘宝图片链接能用，只能写死
            $res = $helper->getTbImage($v['tb_order_no'],AIMEIYIN_DEFAULT_USER_ID);//18
            /*$res = $helper->getTbImage('1055195970660770632','18');
            $res = $helper->getTbImage('1048986017567254755','18');
            $res = $helper->getTbImage('1053193219814121287','18'); //649
            $res = $this->getDemoData();*/
            //插入对列表
            $queueResult = $this->insertImageQueue($res,$v['tb_order_no'],$v['agent_id']);
            /*$queueResult = $this->insertImageQueue($res,'1048986017567254755');*/
            if (isset($queueResult['code'])){
                //插入失败
                $tbMessageModel->where('tb_msg_id',$v['tb_msg_id'])->update(['pic_queue_status' => 2]);
            }else if ($queueResult == true){
                //插入成功
                $tbMessageModel->where('tb_msg_id',$v['tb_msg_id'])->update(['pic_queue_status' => 1]);
            }
        }
        return true;

    }
    //插入图片队列
    public function insertImageQueue($res,$orderId,$agent_id)
    {
        try {
            if (!isset($res['result']['result']) || !isset($res['result']['result']['data'])) {
                //回调出错
                Helper::EasyThrowException(21003,__FILE__.__LINE__);
            }
            //无照片信息 直接跳过
            if (empty($res['result']['result']['data']) || $res['result']['result']['data']=="[]"){
                return true;
            }
            $imgListModel = app(SaasTbOrderImage::class);
            $imgQueueModel = app(SaasTbOrderPicQueue::class);

            //判断该订单是否已进入图片队列
            $isExist = $imgQueueModel->where('tid',$orderId)->exists();
            if ($isExist){
                //已存在记录
                return true;
            }
            \DB::beginTransaction();
            //插入图片队列表
            $picQueueData = [
                'tid' => $orderId,
                'agent_id' => $agent_id,
                'created_at' => time()
            ];
            $qInsertRes = $imgQueueModel->insert($picQueueData);
            if (!$qInsertRes){
                //插入失败
                \DB::rollBack();
                Helper::EasyThrowException(21004,__FILE__.__LINE__);
            }
            $data = json_decode($res['result']['result']['data'],true);
            foreach ($data as $data_k => $data_v){
                if (isset($data_v['pics'])){
                    foreach ($data_v['pics'] as $pic_k => $pic_v){
                        //组织数据插入订单图片列表的表等待下载
                        $idata = [
                            'number'=>$pic_v['number'],
                            'url'=>$pic_v['url'],
                            'outerSkuId' => isset($data_v['outerSkuId']) ? $data_v['outerSkuId'] : '',
                            'item_id'=>$data_v['itemId'],
                            'sku_properties'=>isset($data_v['skuProperties']) ? $data_v['skuProperties'] : '',
                            'item_title'=>$data_v['itemTitle'],
                            'order_id'=>$data_v['orderId'],//子订单号
                            'status'=>0,
                            'created_at' => time(),
                            'tid' => $orderId//淘宝订单号
                        ];
                        $lInsertRes = $imgListModel->insert($idata);
                        if (!$lInsertRes){
                            //插入失败
                            \DB::rollBack();
                            Helper::EasyThrowException(21005,__FILE__.__LINE__);
                        }
                    }
                }elseif (isset($data_v['composite_pic_url'])){
                    $pic = explode(',', $data_v['composite_pic_url']);
                    foreach ($pic as $pic_k => $pic_v){
                        $idata = [
                            'number'=>1,
                            'url'=>$pic_v,
                            'outerSkuId' => isset($data_v['outerSkuId']) ? $data_v['outerSkuId'] : '',
                            'item_id'=>$data_v['itemId'],
                            'sku_properties'=>isset($data_v['skuProperties']) ? $data_v['skuProperties'] : '',
                            'item_title'=>$data_v['itemTitle'],
                            'order_id'=>$data_v['orderId'],
                            'status'=>0,
                            'created_at' => time(),
                            'tid' => $orderId
                        ];
                        $lInsertRes = $imgListModel->insert($idata);
                        if (!$lInsertRes){
                            //插入失败
                            \DB::rollBack();
                            Helper::EasyThrowException(21005,__FILE__.__LINE__);
                        }
                    }
                }
            }
            \DB::commit();
            return true;

        }catch (CommonException $e) {
            \DB::rollBack();
            return [
                'code' => 0,
                'msg'  => $e->getMessage()
            ];
        }
    }

    public function getDemoData()
    {
        $return = [
            'success' => 'true',
            'result'  => [
                'result' => [
                    'data' => '[{"orderId":"1048986017567254755","itemTitle":"海报定制结婚生日儿童明星来图定做卧室墙贴动漫贴纸广告打印制作","orderStatus":1,"itemId":617860618207,"skuProperties":"4360151421463|材质:加厚双铜纸覆光膜;颜色分类:6张一套【来图定制】;尺寸:12寸","outerSkuId":"A550","pics":[{"number":1,"url":"https://img.alicdn.com/imgextra/i4/2207794255547/O1CN01yGUPyi1qqZaA1aLQC_!!2207794255547-0-dingzhi.jpg"},{"number":1,"url":"https://img.alicdn.com/imgextra/i1/2207794255547/O1CN011I3e5O1qqZa8LDzCj_!!2207794255547-0-dingzhi.jpg"},{"number":1,"url":"https://img.alicdn.com/imgextra/i2/2207794255547/O1CN01byFUZv1qqZaBOOihh_!!2207794255547-0-dingzhi.jpg"},{"number":1,"url":"https://img.alicdn.com/imgextra/i1/2207794255547/O1CN01r8YGDn1qqZa6oBNf7_!!2207794255547-0-dingzhi.jpg"},{"number":1,"url":"https://img.alicdn.com/imgextra/i2/2207794255547/O1CN010URX3L1qqZa8LJlAk_!!2207794255547-0-dingzhi.jpg"},{"number":1,"url":"https://img.alicdn.com/imgextra/i2/2207794255547/O1CN015gp93a1qqZaBOWhtu_!!2207794255547-0-dingzhi.jpg"}]}]',
                    'error_code' => '0',
                    'error_msg'  => "",
                    'suc'        => true,
                ],
                'request_id' => '8pi2jy9qwc8m'
            ],

        ];
        return $return;
    }

    /**
     * 冲印图片下载、上传
     * @author: cjx
     * @version: 1.0
     * @date: 2020/06/11
     */
    public function developingPictures()
    {
        //Model
        $pic_queue_model = app(SaasTbOrderPicQueue::class);
        $diy_img_model = app(SaasTbDiyImage::class);
        $tb_img_model = app(SaasTbOrderImage::class);
        $stampingService = app(Stamping::class);
        $tb_message_model = app(SaasTbOrderMessage::class);

        //取出待图片下载队列
        $photo_queue = $pic_queue_model->where(['status'=>PIC_QUEUE_STATUS_WAITE,'queue_status'=>'ready'])->orderBy('created_at','desc')->first();

        if(empty($photo_queue)){
            echo '暂无下载队列';
            exit;
        }

        //更新当前队列状态为progress
        $pic_queue_model->where('pic_que_id',$photo_queue['pic_que_id'])->update(['queue_status'=>'progress']);

        //取出图片信息
        $limit = config('common.queue_limit.print');
        $pic_list = $tb_img_model->where(['status'=>0,'tid'=>$photo_queue['tid']])->orWhere([['status',0],['order_id',$photo_queue['tid']]])->orderBy('id','desc')->take($limit)->get()->toArray();

        if(empty($pic_list) && !empty($photo_queue)){
            //更新图片队列为下载完成状态
            $pic_queue_model->where(['tid'=>$photo_queue['tid']])->update(['status'=>1,'download_time'=>time(),'queue_status'=>'finish']);
        }

        //取得货品id、商品id、商户id
        $ids = $stampingService->getSkuByTid($photo_queue['tid']);

        //淘宝用户信息
        $tb_user_info = $tb_message_model->where('tb_order_no',$photo_queue['tid'])->select('agent_id','tb_user_id')->orderBy('created_at','desc')->first();

        //上传图片到OSS
        $oss = $stampingService->getOss();  //oss信息
        $dir = $stampingService->getOssDir(); //oss保存路径
        $ossCilent = new OssClient($oss['ossKey'],$oss['ossSecret'],$oss['ossHost']);
        try{
            try{
                \DB::beginTransaction();
                foreach ($pic_list as $k=>$v){
                    $img_info = $dir.'/'.uniqid().'.jpg';
                    $result = $stampingService->ossUpload($ossCilent, $v['url'], $img_info);
                    if(!empty($result)){
                        //更新图片使用状态
                        $tb_img_model->where(['id'=>$v['id']])->update(['status'=>1,'updated_at'=>time()]);

                        //组装tb_diy_image数据
                        $img_data = $stampingService->getDiyImgData($result,$ids[0]);
                        $tb_diy_image = [
                            'agent_id'          =>  $tb_user_info['agent_id'],
                            'tb_order_flag'     =>  $v['tid'].$v['outerSkuId'],
                            'photo_sn'          =>  time().mt_rand(1111,9999),
                            'origwidth'         =>  $img_data['origwidth'],
                            'origheight'        =>  $img_data['origheight'],
                            'origsize'          =>  $img_data['origsize'],
                            'big_img'           =>  $img_data['big'],
                            'mid_img'           =>  $img_data['mid'],
                            'sml_img'           =>  $img_data['sml'],
                            'sku_id'            =>  $ids[0]['prod_sku_id'],
                            'tb_user_id'        =>  $tb_user_info['tb_user_id'],
                            'tb_item_order_no'  =>  $v['order_id'],
                            'tb_order_no'       =>  $v['tid'],
                            'print_nums'        =>  $v['number'],
                            'created_at'       =>  time(),
                        ];
                        $res = $diy_img_model->insert($tb_diy_image);

                        if($res){
                            if(count($pic_list) < $limit){
                                //全部下载完成
                                $pic_queue_data = [
                                    'status'        =>  PIC_QUEUE_STATUS_DOWNLOAD,
                                    'queue_status'  =>  'finish',
                                    'download_time' =>  time(),
                                    'updated_at' =>  time(),
                                ];
                                $pic_queue_model->where('tid',$v['tid'])->update($pic_queue_data);
                            }else{
                                $pic_queue_model->where('tid',$v['tid'])->update(['queue_status'=>'ready']);
                            }
                        }else{
                            //冲印图片数据插入出错
                            Helper::EasyThrowException(70092,__FILE__.__LINE__);
                        }
                    }else{
                        //OSS处理出错
                        Helper::EasyThrowException(70091,__FILE__.__LINE__);
                    }
                }
                \DB::commit();

            }catch (\Exception $e){
                \DB::rollBack();
                if(!empty($e->getMessage())){
                    var_dump($e->getMessage());
                }else{
                    //OSS处理出错
                    Helper::EasyThrowException(70091,__FILE__.__LINE__);
                }
            }
        }catch (CommonException $exception){
            var_dump($exception->getMessage());
        }

    }

}
