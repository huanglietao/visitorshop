<?php
namespace App\Services;
use Illuminate\Support\Facades\Redis;
/**
 * 新旧系统数据同步逻辑
 *
 * newmy和saas数据同步的逻辑,这里一些方法只做一次性处理
 * 不会按标准写法进行，怎么简单怎么来。
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/19
 */
class SyncData
{
    //规格映射关系  '旧' => '新'
    protected $arrSize = [
        70  =>   30,
        83  =>   28,
        75  =>   33,
        76  =>   35,
        81  =>   38,
        80  =>   40,
        67  =>   68,
        66  =>   67,  //横款单页13P
        65  =>   66,  //竖款双页 26p
        64  =>   65,  //竖款单页 13P
        73  =>   83,  //单封面对裱竖
        74  =>   84,  //单封面对裱方
        85  =>   94,
        72 =>    95,
        65 =>    70,
        67 =>   71,

    ];

    //'旧' => '新'
    protected $arrProdType = [
        1  => 50,  //照片书
        2  => 51   //台历
    ];

    protected $arrSpecStyle = [
        1  =>  6,
        2   => 5,
        3   => 4,
        4   => 3,
        5   => 2,
        6=>1
    ];

    //主模板转移
    public function syncNewmyTemp($spec_id)
    {

        if (Redis::get('tidn'.$spec_id)) {
            $tid = Redis::get('tidn'.$spec_id);
        } else {
            $tid = 0;
        }
        \DB::beginTransaction();
        $sql = "SELECT * FROM fa_main_templates WHERE id>$tid AND check_status=3 AND  specifications_id=$spec_id ORDER BY id asc LIMIT 10";
        $list = \DB::connection('mysql_newmy')->select($sql);

        $insertData = [];
        foreach ($list as $k=>$v) {
            //取newmy模板分类
            $newmy_cate_sql = "SELECT * FROM fa_template_category WHERE id={$v->theme_id} LIMIT 1";
            $newmy_cate_info = \DB::connection('mysql_newmy')->select($newmy_cate_sql);

            //对应saas模板分类
            $saas_cate_sql = "SELECT * FROM saas_category WHERE cate_name='{$newmy_cate_info[0]->name}' AND cate_uid='template' LIMIT 1";
            $saas_cate_info =   \DB::connection('mysql')->select($saas_cate_sql);


            $main_tid = intval($v->id)+20000;
            $insertData['main_temp_id'] = $main_tid;
            $insertData['mch_id'] = 0;
            $insertData['main_temp_name'] = $v->name;
            $insertData['goods_type_id'] = $this->arrProdType[$v->goods_type_id];
            $insertData['main_temp_theme_id'] = isset($saas_cate_info[0]->cate_id) ? $saas_cate_info[0]->cate_id:38;
            $insertData['specifications_id'] = $this->arrSize[$spec_id];
            $insertData['main_temp_photo_count'] = $v->photo_count;
            $insertData['main_temp_check_status'] = 1;
            $insertData['main_temp_thumb'] = "http://material.meiin.com/".$v->thumb;
            $insertData['created_at'] = time();
            $ret = \DB::table("saas_main_templates")->insert($insertData);
            $main_id = $main_tid;
            //插入子页数据
            $child_sql = "SELECT * FROM fa_main_templates_pages WHERE fid = $v->id";
            $childList =\DB::connection('mysql_newmy')->select($child_sql);

            //获取规格详情数据
            $newSizeId = $this->arrSize[$spec_id];
            $size_sql = "SELECT * FROM saas_size_info WHERE goods_id= 0 AND size_id = $newSizeId";
            $sizeList = \DB::connection('mysql')->select($size_sql);

            $arrType = [];
            foreach ($sizeList as $sk=>$sv) {
                $arrType[$sv->size_type] = $sv->size_info_id;
            }


                //插入子页
            $childData= [];
            foreach ($childList as $kk=>$vv) {

                if ($vv->type == 1) {
                    $size_id =  !empty($arrType[$vv->type]) ? $arrType[$vv->type] : $arrType[2];
                    $type =  !empty($arrType[$vv->type]) ? 1:2;
                } elseif($vv->type == 2) {
                    $size_id = $arrType[3];
                    $type = 3;
                }elseif($vv->type == 3) {
                    $size_id = $arrType[4];
                    $type = 4;
                }

                $childData[$kk]['main_temp_page_id'] = (intval($vv->id)+350000);
                $childData[$kk]['mch_id'] = 0;
                $childData[$kk]['main_temp_page_tid'] = $main_tid;
                $childData[$kk]['specifications_id'] =  $this->arrSize[$spec_id];
                $childData[$kk]['spec_info_id'] =  $size_id;
                $childData[$kk]['main_temp_page_type'] = $type;
                $childData[$kk]['main_temp_page_name'] = $vv->name;
                $childData[$kk]['main_temp_page_real_w'] = $vv->real_page_width;
                $childData[$kk]['main_temp_page_real_h'] = $vv->real_page_height;
                $childData[$kk]['main_temp_page_dpi'] = $vv->dpi;
                $childData[$kk]['main_temp_page_year'] = $vv->year;
                $childData[$kk]['main_temp_page_photo_count'] = $vv->photo_count;
                $childData[$kk]['main_temp_page_stage'] = $vv->stage;
                $childData[$kk]['main_temp_page_sort'] = $vv->sort;
                if (!empty($vv->is_delete))
                    $childData[$kk]['deleted_at'] = time();
                else
                    $childData[$kk]['deleted_at'] = null;
            }
            $ret = \DB::table("saas_main_templates_pages")->insert($childData);

            Redis::set('tidn'.$spec_id,$v->id);
        }

        \DB::commit();
    }

    public function syncNewmyLayout($sizeId)
    {
        $this->arrSize = [
            70  =>   30,
            83  =>   28,
            77  =>   33,
            78  =>   35,
            81  =>   38,
            80  =>   40,
            67  =>   68,
            66  =>   67,  //横款单页13P
            65  =>   66,  //竖款双页 26p
            64  =>   65,  //竖款单页 13P
        ];

        if (Redis::get('layout'.$sizeId)) {
            $tid = Redis::get('layout'.$sizeId);
        } else {
            $tid = 0;
        }

        if (empty($sizeId)) {
            return false;
        }

        $sql = "SELECT * FROM fa_templates_layout WHERE id>{$tid} AND check_status=3 AND  specifications_id=$sizeId ORDER BY id asc LIMIT 1";
        $list = \DB::connection('mysql_newmy')->select($sql);

        $insertData = [];
        foreach ($list as $k=>$v){

            $insertData['temp_layout_id'] = $v->id;
            $insertData['mch_id'] = 0;
            $insertData['temp_layout_name'] = $v->name;
            $insertData['temp_layout_type'] = 1;
            $insertData['goods_type_id'] = 50;
            $insertData['specifications_id'] = $this->arrSize[$sizeId];
            $insertData['layout_spec_style'] = isset($this->arrSpecStyle[$v->spec_style]) ? $this->arrSpecStyle[$v->spec_style]:0;
            $insertData['layout_dpi'] =  $v->dpi;
            $insertData['layout_check_status'] = 1;
            $insertData['layout_real_page_w'] =  $v->real_page_width;
            $insertData['layout_real_page_h'] =  $v->real_page_height;
            $insertData['layout_real_dpi'] =  $v->real_dpi;
            $insertData['temp_layout_sort'] =  empty($v->sort) ?0 : $v->sort;
            $insertData['temp_layout_thumb'] =  "http://static.meiin.com/".$v->thumb;
            $insertData['temp_layout_stage'] =  $v->stage;
            $insertData['layout_photo_nums'] =  $v->photo_nums;
            $insertData['created_at'] =  time();

            $ret = \DB::table("saas_templates_layout")->insert($insertData);
            Redis::set('layout'.$sizeId,$v->id);
            var_dump($ret);exit;
        }
    }

    public function syncNewmyMaterial($type)
    {
        $arr_mater_type = [
            '1'  => 'background',
            '2'  => 'decorate',
            '3'  => 'frame',
            '5'  => 'special'
        ];

        if (Redis::get('mar'.$type)) {
            $mid = Redis::get('mar'.$type);
        } else {
            $mid = 0;
        }
        $sql = "SELECT * FROM fa_material WHERE id>$mid AND type=$type LIMIT 10";
        $list = \DB::connection('mysql_newmy')->select($sql);

        $insertData = [];
        foreach ($list as $k=>$v) {
            \DB::beginTransaction();
            $mtype = $type==1 ? 'background' : 'material';
            //取newmy模板分类
            $newmy_cate_sql = "SELECT * FROM fa_template_category WHERE id={$v->style_id} LIMIT 1";
            $newmy_cate_info = \DB::connection('mysql_newmy')->select($newmy_cate_sql);

            //对应saas模板分类
            $saas_cate_sql = "SELECT * FROM saas_category WHERE cate_name='{$newmy_cate_info[0]->name}' AND cate_uid='{$mtype}' LIMIT 1";
            $saas_cate_info =   \DB::connection('mysql')->select($saas_cate_sql);

            $insertData['material_id'] = $v->id;
            $insertData['mch_id'] = 0;
            $insertData['material_type'] = $mtype;
            $insertData['material_cate_flag'] =$arr_mater_type[$type];
            $insertData['material_name'] =$v->name;
            $insertData['material_cateid'] =isset($saas_cate_info[0]->cate_id) ? $saas_cate_info[0]->cate_id:0;
            $insertData['specification_id'] =0;
            $insertData['attachment_id'] =$v->attachment_id;
            $insertData['material_sort'] =$v->sort;
            $insertData['specification_style'] = $this->arrSpecStyle[$v->spec_style];
            $insertData['created_at'] = time();
            $ret = \DB::table("saas_material")->insert($insertData);

            //插入附件记录
            $atta_sql = "SELECT * FROM fa_material_attachment WHERE id={$v->attachment_id}";
            $atta_info = \DB::connection('mysql_newmy')->select($atta_sql);
            if (empty($atta_info[0])) {
                \DB::rollBack();
            }

            $atta_row = $atta_info[0];
            $insertAttaData['material_atta_id'] = $atta_row->id;
            $insertAttaData['material_atta_orig_name'] = $atta_row->orig_name;
            $insertAttaData['material_atta_path'] = $atta_row->path;
            $insertAttaData['material_atta_file_name'] = $atta_row->file_name;
            $insertAttaData['material_atta_width'] = $atta_row->width;
            $insertAttaData['material_atta_height'] = $atta_row->height;
            $insertAttaData['material_atta_size'] = $atta_row->size;
            $insertAttaData['material_atta_uniqid'] = $atta_row->uniqid;
            $insertAttaData['created_at'] = time();
            $ret = \DB::table("saas_material_attachment")->insert($insertAttaData);

            Redis::set('mar'.$type,$v->id);
            \DB::commit();


        }
    }

    /**
     *同步天猫消息数据,

     */
    public function syncTbTcmMsg()
    {
        //获取当天的记录
        $todayTimestamp = strtotime(date('Ymd'));

        //Redis::set('tborders_time',0);
        if (Redis::get('tborders_time')) {
            $time = Redis::get('tborders_time');
        } else {
            $time = $todayTimestamp;
        }

        $tbMsgSql = "SELECT * FROM is_agent_tb_messages WHERE  add_time>{$time} and is_sync=0 ORDER BY pub_time ASC  limit 100  ";
        $list = \DB::connection('ishop_mysql')->select($tbMsgSql);

        //获取聚石塔配置信息
        $syncConfSql = "SELECT * FROM saas_sync_order_conf WHERE deleted_at is null";
        $syncList = \DB::connection('mysql')->select($syncConfSql);
        $arrUserToAgent = [];
        foreach ($syncList as $sk=>$sv) {
            $arrUserToAgent[$sv->tb_user_id] = $sv->agent_id;
        }


        $insertData = [];
        foreach ($list as $k=>$v) {
            $contents = json_decode($v->content,true);
            $tbOrderNo = $contents['tid'];
            $status = isset($contents['status']) ? $contents['status'] :'';
            $insertData['agent_id'] = $arrUserToAgent[$v->user_id];
            $insertData['tb_order_no'] = strval($tbOrderNo);
            $insertData['tb_order_status'] = $status;
            $insertData['tb_seller_nick'] = $v->user_nick;
            $insertData['pub_app_key'] = $v->pub_app_key;
            $insertData['tb_user_id'] = $v->user_id;
            $insertData['msg_topic'] = $v->topic;
            $insertData['is_confrimed'] = 1;
            $insertData['created_at'] = $v->add_time;
            $insertData['old_sys_id'] = $v->id;
            $ret = \DB::table("saas_tb_order_message")->insert($insertData);

            $updateSql = "UPDATE is_agent_tb_messages SET is_sync=1 WHERE id={$v->id}";
            \DB::connection('ishop_mysql')->update($updateSql);

            Redis::set('tborders_time',$v->add_time);
        }

    }

    public function syncTbTcmMsgRe()
    {
        $startTime = date('Y-m-d',strtotime('-1 day'));
        $startTime = date('Y-m-d',time());
        $tbMsgSql = "SELECT * FROM is_agent_tb_messages WHERE  pub_time>'2020-08-25 00:00:00' AND pub_time<'2020-08-25 12:00:00'  ";
        $list = \DB::connection('ishop_mysql')->select($tbMsgSql);

        //获取聚石塔配置信息
        $syncConfSql = "SELECT * FROM saas_sync_order_conf WHERE deleted_at is null";
        $syncList = \DB::connection('mysql')->select($syncConfSql);
        $arrUserToAgent = [];
        foreach ($syncList as $sk=>$sv) {
            $arrUserToAgent[$sv->tb_user_id] = $sv->agent_id;
        }


        $insertData = [];
        foreach ($list as $k=>$v) {



            $contents = json_decode($v->content,true);
            $tbOrderNo = $contents['tid'];

            $saas_cate_sql = "SELECT * FROM saas_tb_order_message WHERE tb_order_no='{$tbOrderNo}' AND msg_topic='{$v->topic}' LIMIT 1";
            $saas_cate_info =   \DB::connection('mysql')->select($saas_cate_sql);

            if (empty($saas_cate_info)) {
                var_dump($tbOrderNo);
                echo '===>';
              
                $status = isset($contents['status']) ? $contents['status'] :'';
                $insertData['agent_id'] = $arrUserToAgent[$v->user_id];
                $insertData['tb_order_no'] = strval($tbOrderNo);
                $insertData['tb_order_status'] = $status;
                $insertData['tb_seller_nick'] = $v->user_nick;
                $insertData['pub_app_key'] = $v->pub_app_key;
                $insertData['tb_user_id'] = $v->user_id;
                $insertData['msg_topic'] = $v->topic;
                $insertData['is_confrimed'] = 1;
                $insertData['created_at'] = $v->add_time;
                $ret = \DB::table("saas_tb_order_message")->insert($insertData);

            }

            //Redis::set('tborders_time',$v->add_time);
        }

    }

    public function syncSkuProNo()
    {
        $saasSkuSql = "SELECT * FROM saas_products_sku ps left join saas_products p on p.prod_id=ps.prod_id where p.deleted_at is null";
        $skuList = \DB::connection('mysql')->select($saasSkuSql);
        foreach ($skuList as $k=>$v)
        {
            $ishopSql = "SELECT * FROM is_products where factory_code='{$v->prod_supplier_sn}' AND products_no is not null";
            $ishopList = \DB::connection('ishop_mysql')->select($ishopSql);
            $product_no = isset($ishopList[0]->products_no) ? $ishopList[0]->products_no:'';
            if (!empty($product_no)) {
                echo $product_no.'<br>';
                $up_sql = "UPDATE saas_products_sku SET prod_sku_sn='{$product_no}',prod_process_code='{$v->prod_supplier_sn}' WHERE prod_supplier_sn='{$v->prod_supplier_sn}'";
                \DB::connection('mysql')->update($up_sql);
            }
        }
    }

    protected function getNewmyConnection()
    {

    }
}