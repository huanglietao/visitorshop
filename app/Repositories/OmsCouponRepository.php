<?php
namespace App\Repositories;
use App\Models\OmsCoupon;
use App\Services\Helper;
use Illuminate\Support\Facades\DB;

/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/13
 */
class OmsCouponRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(OmsCoupon $model)
    {
        $this->model =$model;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model;

        //查询时间
        if(isset($where['cou_time'])){
            $created_at = $where['cou_time'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->where("cou_start_time",">=",$time_list['start'])->where('cou_end_time','<=',$time_list['end']);
            unset($where['cou_time']);
        }


        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);
        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['cou_id'])) {
            unset($data['cou_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['cou_id'];
            unset($data['cou_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('cou_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['cou_id'] = $priKeyValue;
             //将数据写入缓存
             $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
         }

        return $ret;

    }

    /**
     * 删除(软删除)
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->model->find($id);
        $model->delete();

        //删除缓存数据
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['cou_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 获取销售渠道
     * @param
     * @return $row
     */
    public function getSalesChanel($cha_id=null)
    {
        if(empty($cha_id)){
            $result = DB::table('saas_sales_chanel')->whereNull('deleted_at')->select('cha_id','cha_name')->get();
            $result=json_decode($result,true);
            foreach ($result as $k=>$v){
                $row[$v['cha_id']] = $v['cha_name'];
            }
        }
        else{
            $result = DB::table('saas_sales_chanel')->where(['cha_id'=>$cha_id])->whereNull('deleted_at')->select('cha_name')->get();
            $result=json_decode($result,true);
            $row = $result[0]['cha_name'];
        }
        return $row;
    }

    /**
     * 获取商品分类名
     * @param
     * @return $row
     */
    public function getGoodsCategory($limits,$category,$mch_id=null)
    {
        $category_list = explode(",",$category);
        if($limits==2){
            foreach ($category_list as $k => $v){
                $result = DB::table('saas_products')->where(['prod_id'=>$v,'mch_id'=>$mch_id])->whereNull('deleted_at')->select('prod_name')->get();
                $result = json_decode($result,true);
                $row[$v] = $result[0]['prod_name'];
            }
        }
        if($limits==3){
            foreach ($category_list as $k => $v){
                $result = DB::table('saas_category')->where(['cate_uid'=>'goods','cate_id'=>$v])->whereNull('deleted_at')->select('cate_name')->get();
                $result = json_decode($result,true);
                $row[$v] = $result[0]['cate_name'];
            }
        }
        return $row;
    }


    //获取商品名称
    public function getGoods($type)
    {
        $data = DB::table('saas_products')->where(['mch_id' => $type])->get();
        $data = json_decode($data,true);
        $array = [];
        foreach ($data as $k=>$v){
            $array[$v['prod_id']] = $v['prod_name'];
        }
        return $array;
    }


    public function saveCouponNum($data,$cou_id)
    {
        if (empty($data['cou_id'])) {
            //生成指定数量优惠券(券码)
            for ($i = 0; $i < $data['cou_nums']; $i++) {
                $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'Y', 'J');
                $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));

                $num_data['cou_id'] = $cou_id;
                $num_data['cou_num_code'] = $orderSn;
                $num_data['cou_num_money'] = $data['cou_denomination'];
                $num_data['created_at'] = time();
                $ret = Db::table("oms_coupon_number")->insert($num_data);
            }
        }else {
            $coupon_number = DB::table('oms_coupon_number')->where(["cou_id"=>$data['cou_id']])->count();
            $ret=1;
            //发放数量相等即只进行编辑操作
            if ($data['cou_nums'] != $coupon_number) {
                //生成指定数量优惠券(券码)
                for ($i = 0; $i < ($data['cou_nums'] - $coupon_number); $i++) {
                    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'Y', 'J');
                    $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));

                    $num_data['cou_id'] = $data['cou_id'];
                    $num_data['cou_num_code'] = $orderSn;
                    $num_data['cou_num_money'] = $data['cou_denomination'];
                    $num_data['created_at'] = time();
                    $ret = Db::table("oms_coupon_number")->insert($num_data);
                }
            }
        }

        return $ret;
    }


}
