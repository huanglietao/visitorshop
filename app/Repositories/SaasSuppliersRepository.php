<?php
namespace App\Repositories;
use App\Models\SaasSuppliers;
use App\Services\Helper;
use Illuminate\Support\Facades\DB;

/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/09
 */
class SaasSuppliersRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasSuppliers $model)
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
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
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
        if(empty($data['sup_id'])) {
            unset($data['sup_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['sup_id'];
            unset($data['sup_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('sup_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['sup_id'] = $priKeyValue;
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
            $data['sup_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }


    /**
     * 获取地区对应的id和名字
     * @param $id
     * @return $row
     */
    public function getArea($id)
    {
        $row = DB::table('saas_areas')->where('area_id',$id)->whereNull('deleted_at')->select('area_id','area_name')->get();
        return $row;
    }

    //获取列表 by hlt
    public function getList($where=[], $order='created_at', $sort = "desc")
    {
        return parent::getList($where, $order, $sort); // TODO: Change the autogenerated stub
    }
    //获取供货商名称
    public function getName($id)
    {
        $name = $this->model->where(['sup_id' => $id])->value('sup_name');
        return $name;
    }

    //获取供应商列表
    public function getSupplierList($mid = PUBLIC_CMS_MCH_ID)
    {
        if ($mid != PUBLIC_CMS_MCH_ID)
        {
            $whereMid = [PUBLIC_CMS_MCH_ID,$mid];
        }else{
            $whereMid = [PUBLIC_CMS_MCH_ID];
        }

        $array = $this->model->whereIn('mch_id',$whereMid)->get()->toArray();


        $areaArr = config("goods.sup_region");
        foreach ($array as $k=>$v)
        {
            //获取地区名
            $regionName = $areaArr[$v['sup_region']]??"";
            //获取省市区
            $province = $this->getArea($v['sup_province']);
            $provinceName = "";
            if (!empty($province))
            {

                $provinceName = $province[0]->area_name;

            }
            //获取主力或备选
            if ($v['sup_type'] == SUPPLIER_TYPE_MAIN)
            {
                $supTyprName = "主力";
            }else{
                $supTyprName = "备选";
            }

            $array[$k]['supplier_new_name'] = $v['sup_code']." ".$regionName." "."(".$provinceName.")"." ".$supTyprName." ".$v['sup_name'];
        }
        return $array;


    }

    /**
     * 更新资料创建账号状态为已创建
     * @param $infoID
     * @return bool
     */
    public function updateIsCreate($sp_id,$mch_id)
    {
        $data=[
            'sup_id'=>$sp_id,
            'mch_id'=>$mch_id,
            'is_create_scm'=>'1'
        ];
        $ret = $this->save($data);
        return $ret;
    }

    /**
     * 获取队列待下载
     * @param $mid,$sid,$size
     * @return array
     */
    public function getDownloadQueues($mid,$sid,$size)
    {
        $ret = $this->model->getSpDownloadQueue($mid,$sid,$size);
        return $ret;
    }

}
