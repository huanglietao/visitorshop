<?php
namespace App\Repositories;
use App\Models\SaasProductsAttribute;
use App\Models\SaasAttributeValues;
use App\Exceptions\CommonException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Helper;

/**
 * 商品属性仓库模板
 * 商品属性仓库模板
 * @author: hlt <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/07
 */
class SaasProductsAttributeRepository extends BaseRepository
{

    public function __construct(SaasProductsAttribute $model,SaasAttributeValues $valueModel)
    {
        $this->model = $model;
        $this->valueModel = $valueModel;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order='created_at desc',$mid = PUBLIC_CMS_MCH_ID)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);
        $where['mch_id'] = $mid;

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model->with('attributeValueAttach')->with('categoryAttach');
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
        /*$list = $query->get()->toArray();
        var_dump($list);
        die;*/
        $list = $query->paginate($limit)->toArray();
        foreach ($list['data'] as $k => $v){
            if ($v['attr_flag'] == PAGE_FLAG){
                $list['data'][$k]['attribute_value_attach'] = array_values($this->arraySort($list['data'][$k]['attribute_value_attach'],'attr_val_name'));
            }
        }
        return $list;
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
            $data['prod_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {

        //判断是否有属性值
        if (!isset($data['attrValue']) || (isset($data['attrValue']) && empty($data['attrValue'])))
        {
            $ret = [
                'code' => 0,
                'msg'  => "属性值不能为空",
            ];
            return $ret;
        }


        //建立属性值验证
        $rules = [
            'attr_val_name'=>'required|max:255',
        ];
        $messages = [
            'attr_val_name.required'=>'请填写值名称',
        ];
        try {

            \DB::beginTransaction();
        if(empty($data['id'])) {
            unset($data['id']);

            //插入属性表
            $inertAttrArr = [
                'cate_id' => $data['cate_id'],
                'attr_name' => $data['attr_name'],
                'created_at' => time(),
            ];

            $newAttrId = $this->model->insertGetId($inertAttrArr);

        } else {
            $priKeyValue = $data['id'];
            $data['updated_at'] =time();
            $attrData = [
                'cate_id' => $data['cate_id'],
                'attr_name' => $data['attr_name'],
                'updated_at' => time()
            ];
            //判断父类是否选择了自身
            unset($data['id']);
            $ret =$this->model->where('attr_id',$priKeyValue)->update($attrData);
        }
            //插入/更新属性值表
            foreach ($data['attrValue']['attr_val_name'] as $k => $v)
            {
                $insertAttrValueArr['attr_val_name'] = $v;
                if (isset($data['attrValue']['sort']))
                {
                    $insertAttrValueArr['sort']          = $data['attrValue']['sort'][$k]??0;
                }else{
                    $insertAttrValueArr['sort']          = 0;
                }

                //判断是否有属性值图片

                if (isset($data['attrValue']['attr_val_icon']))
                {
                    $insertAttrValueArr['attr_val_icon']          = $data['attrValue']['attr_val_icon'][$k]??"";
                }else{
                    $insertAttrValueArr['attr_val_icon']          = "";
                }

                $validator = Validator::make($insertAttrValueArr,$rules,$messages,[
                    'attr_val_name' => '属性值名称'
                ]);
                if ($validator->fails()) {
                    $error_arr = json_decode($validator->errors(),true);
                    $error = [];
                    foreach ($error_arr as $k => $v)
                    {
                        foreach ($v as $kk=>$vv){
                            $error[] = $vv;
                        }
                    }
                    $data = [
                        'code' => 0,
                        'msg' => $error[0]
                    ];
                    \DB::rollBack();
                    return $data;


                }

                if (isset($data['attrValue']['attr_val_id'] ))
                {
                    //拥有属性id
                    if (isset($data['attrValue']['attr_val_id'][$k]))
                    {
                        //更新已有属性值
                        $this->valueModel->where('attr_val_id',$data['attrValue']['attr_val_id'][$k])->update($insertAttrValueArr);
                    }else{
                        //生成新加属性值
                        if (!isset($priKeyValue)){
                            $data = [
                                'code' => 0,
                                'msg' => "未获取到属性id,属性值生成失败"
                            ];
                            \DB::rollBack();
                            return $data;
                        }
                        $insertAttrValueArr['attr_id']       = $priKeyValue;
                        //插入新表
                        $this->valueModel->insert($insertAttrValueArr);

                    }

                }else{
                    //拥有属性id 新生属性值表数据
                    if (!isset($newAttrId)){
                        $data = [
                            'code' => 0,
                            'msg' => "未获取到属性id,属性值生成失败"
                        ];
                        \DB::rollBack();
                        return $data;
                    }
                    $insertAttrValueArr['attr_id']       = $newAttrId;
                    //插入新表
                    $this->valueModel->insert($insertAttrValueArr);
                }



            }
            \DB::commit();
            return [
                'code' => 1,
                'msg' => 'ok',
            ];
        } catch (CommonException $e) {

            \DB::rollBack();
            return [
                'code' => 0,
                'msg'  => $e->getMessage()

            ];
        }
    }

    //获取列表 by hlt
    public function getList($where=[], $order='created_at', $sort = "desc")
    {
        return parent::getList($where, $order, $sort); // TODO: Change the autogenerated stub
    }

    //获取增减p属性列表
    public function getAttribute($mid=0,$cateId=0,$isAddPage =1)
    {
        //组织查询条件
        $where=[];
        if ($mid){
            $where['mch_id'] = $mid;
        }else{
            //大后台
            $where['mch_id'] = PUBLIC_CMS_MCH_ID;
        }


        //判断有没有分类
        if($cateId){
            $where['cate_id'] = $cateId;
        }

        if ($isAddPage == ADD_PAGE)
        {
            $attrList = $this->model->where(['cate_id' => $cateId,'mch_id'=>PUBLIC_CMS_MCH_ID])->whereRaw("attr_flag!='".PAGE_FLAG."'")->get()->toArray();
        }else{
            $attrList = $this->model->where(['cate_id' => $cateId,'mch_id'=>PUBLIC_CMS_MCH_ID])->get()->toArray();
        }

        return $attrList;
    }


    /**
     * 获取P数对应的属性记录
     */
    public function getPageAttr()
    {
        return $this->getRow(['attr_flag' => GOODS_ATTR_PAGE_FLAG]);
    }

}

