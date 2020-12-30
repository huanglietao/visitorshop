<?php
namespace App\Repositories;
use App\Models\SaasCart;
use App\Models\SaasProductsCollect;
use App\Models\SaasProductsSku;
use App\Models\SaasProjects;
use App\Models\SaasSalesChanel;
use App\Services\Goods\Info;
use App\Services\Goods\Price;
use App\Exceptions\CommonException;

/**
 * 仓库模板
 * 仓库模板
 * @author:
 * @version: 1.0
 * @date:
 */
class SaasCartRepository extends BaseRepository
{

    public function __construct(SaasCart $model)
    {
        $this->model = $model;
        $this->mch_id = session("admin")['mch_id'];
        $this->agent_id = isset(session('admin')['agent_info_id']) ? session('admin')['agent_info_id'] : '';
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where = null, $order = null)
    {

        $projectModel = app(SaasProjects::class);
        $relationAttrRepository = app(SaasProductsRelationAttrRepository::class);
        $dmsAgentInfoRepository = app(DmsAgentInfoRepository::class);
        $price = app(Price::class);


        $productsRepositories = app(SaasProductsRepository::class);
        $mediasRepositories = app(SaasProductsMediaRepository::class);
        $skuModel = app(SaasProductsSku::class);
        $limit = isset($where['limit']) ? $where['limit'] : config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);



        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if (count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model;
        if (!empty ($where)) {
            $query = $query->where($where);
        }

        if (!empty($order)) {
            $query = $query->orderBy($orderBy[0], $orderBy[1]);
        }


        $list = $query->first();

        $project_arr = [];

        if (!empty($list)) {
            $list = $list->toArray();
            //组织数据
            $prj_id = json_decode($list['cart_info'], true);
           if (!$prj_id){
               $project_arr['data'] = [];
               $project_arr['count'] = count($project_arr['data']);
               return $project_arr;
           }
            $project_arr['data'] = [];
            $total_price = 0;
            foreach ($prj_id as $k => $v) {
                $project_arr['data'][$k]['project_id'] = $v['projects_id'];
                $project_arr['data'][$k]['num'] = $v['num'];
                $project_arr['data'][$k]['cart_id'] = $list['cart_id'];
                $project_arr['data'][$k]['sku_id'] = $v['sku_id'];

                //获取作品信息(可能为空)
                $project = $projectModel->where(['prj_id' => $v['projects_id']])->first();

                //获取sku商品信息
                $sku_arr = $skuModel->find($v['sku_id'])->toArray();

                //获取商品货号
                $project_arr['data'][$k]['prod_sn'] = $sku_arr['prod_sku_sn']??"";

                //获取商品信息
                $product_info = $productsRepositories->getById($sku_arr['prod_id']);
                if (!$product_info)
                {
                    $project_arr['data'][$k]['prod_id'] = "";
                    $project_arr['data'][$k]['prod_name'] = "";
                    $project_arr['data'][$k]['prod_photo'] = "";
                 }else{
                    $product_info = $product_info->toArray();
                    $project_arr['data'][$k]['prod_name'] = $product_info['prod_name'];
                    $project_arr['data'][$k]['prod_id'] = $sku_arr['prod_id'];
                    //获取商品图片
                    $prod_photo = $mediasRepositories->getProductPhoto($product_info['prod_id']);
                    if (!empty($prod_photo)) {
                        $project_arr['data'][$k]['prod_photo'] = $prod_photo[0]['prod_md_path'];
                    } else {
                        $project_arr['data'][$k]['prod_photo'] = "";
                    }
                 }




                //获取作品中货品的属性
                $sku_attr = $relationAttrRepository->getProductAttr($v['sku_id']);
                if ($sku_attr==" : ")
                {
                    $project_arr['data'][$k]['sku_attr'] = [];
                }else{
                    $project_arr['data'][$k]['sku_attr'] = explode("，", $sku_attr);
                }


                //获取货品的价格
                $cust_lv_id = $dmsAgentInfoRepository->getCustLvId($this->agent_id);
                if (empty($project))
                {
                    //无作品情况
                    $sku_price = $price->getChanelPrice($v['sku_id'], $cust_lv_id, 0);
                }else{
                    //有作品情况
                    $sku_price = $price->getChanelPrice($v['sku_id'], $cust_lv_id, $project['prj_page_num']);
                }
                $project_arr['data'][$k]['sku_price'] = $sku_price;

                //总价
                if (isset($v['num'])) {
                    $project_arr['data'][$k]['total_price'] = $v['num'] * $sku_price;
                } else {
                    $project_arr['data'][$k]['total_price'] = $sku_price;
                }

            }
        } else {
            $project_arr['data'] = [];
        }
        $project_arr['count'] = count($project_arr['data']);

        return $project_arr;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if (empty($data['id'])) {
            $ret = $this->model->create($data);
        } else {
            $ret = $this->model->where('id', $data['id'])->update($data);
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

        if ($model->trashed()) {
            return true;
        } else {
            return true;
        }
    }

    //添加商品到购物车
    //传入标准数组
    public function addCartGoods($data)
    {
        /*$data = [
            'user_id' => 4,//用户id
            'cha_id'  => 1,//渠道id
            'cart_info' => [
                [
                    'projects_id' => 18, //作品id 没有则传0
                    'sku_id' => 113,      //skuid
                    'num'    => 4         //商品数量
                ],
                [
                    'projects_id' => 0, //作品id 没有则传0
                    'sku_id' => 110,      //skuid
                    'num'    => 3         //商品数量
                ],
            ]
        ];*/
        $channleModel = app(SaasSalesChanel::class);
        //获取用户类型
        $data['user_type'] = $channleModel->where(['cha_id' => $data['cha_id']])->value('cha_flag');


        //获取该用户的购物车列表
        $userCart = $this->model->where(['user_id' => $data['user_id'],'cha_id' => $data['cha_id']])->first();

        if (empty($userCart))
        {
            $data['cart_info'] = json_encode($data['cart_info']);
            $data['created_at'] = time();
            //添加新购物车数据
            $res = $this->model->insert($data);
            if(!$res){
                return false;
            }
        }else{
            $userCartInfo = $userCart->toArray();
            if (empty($userCartInfo['cart_info']))
            {
                $oldCartInfo = [];
            }else{
                $oldCartInfo = json_decode($userCartInfo['cart_info'],true);
            }
                foreach ($data['cart_info'] as $kk => $vv)
                {
                    $theSame = 0;
                    foreach ($oldCartInfo as $k => $v)
                    {
                    //判断是否有该商品记录
                         if ($v['sku_id'] == $vv['sku_id'] && $v['projects_id'] == $vv['projects_id'])
                         {
                            $theSame = 1;
                            //增加数量
                            $oldCartInfo[$k]['num'] +=$vv['num'];
                         }
                     }
                     if (!$theSame){
                        //添加记录
                         $oldCartInfo[] = $vv;
                     }
                }
            //更新购物车数据
            $userCartInfo['cart_info'] = json_encode($oldCartInfo);
            $res = $this->model->where(['cart_id' => $userCartInfo['cart_id']])->update($userCartInfo);
            if(!$res){
                return false;
            }
        }

        return true;

    }



    //修改购物车数据
    public function changeCartGoodsNum($post)
    {
        $cartArr = $this->model->find($post['cart_id'])->toArray();
        if (!empty($cartArr)) {
            $cartData = json_decode($cartArr['cart_info'], true);
            foreach ($cartData as $k => $v) {
                if ($v['sku_id'] == $post['sku_id']) {
                    $cartData[$k]['num'] = $post['num'];
                }
            }
            $cartData = json_encode($cartData);
            $data = [
                'cart_info' => $cartData
            ];
            $this->model->where(['cart_id' => $post['cart_id']])->update($data);
        }

    }

    //删除购物车商品
    public function delCartGoods($cart_id, $project_id,$sku_id)
    {
        $cartArr = $this->model->where(['cart_id' => $cart_id])->first()->toArray();
        $data = json_decode($cartArr['cart_info'], true);

        //修改json串改商品
        foreach ($data as $k => $v) {
            if ($v['projects_id'] == $project_id && $v['sku_id'] == $sku_id) {
                unset($data[$k]);
            }
        }
        $json_data = [
            'cart_info' => json_encode($data)
        ];
        $this->model->where(['cart_id' => $cart_id])->update($json_data);

        return true;

    }

    //收藏购物车商品
    public function collectCartGoods($sku_id,$cart_id)
    {
        $sku_id = explode(",", $sku_id);
        $projectModel = app(SaasProjects::class);
        $channleModel = app(SaasSalesChanel::class);
        $collectModel = app(SaasProductsCollect::class);
        $skuModel = app(SaasProductsSku::class);
        $cartModel = app(SaasCart::class);
        //获取sku数据
        $skuArr = $skuModel->whereIn('prod_sku_id', $sku_id)->get()->toArray();
        //获取购物车数据
        $cartArr = $cartModel->find($cart_id)->toArray();


        foreach ($skuArr as $k => $v) {
            //判断是否已经在收藏表中
            $isset = $collectModel->where(['prod_id' => $v['prod_id'], 'user_id' => $cartArr['user_id'],])->first();
            if (empty($isset)) {
                $data = [
                    'prod_id' => $v['prod_id'],
                    'user_id' => $cartArr['user_id'],
                    'created_at' => time(),
                ];
                //获取用户类型
                $data['user_type'] = $channleModel->where(['cha_id' => $cartArr['cha_id']])->value('cha_flag');
                $res = $collectModel->insert($data);
                if (!$res) {
                    return [
                        'code' => 0,
                        'msg' => "收藏失败"

                    ];
                }
            }
        }
        return true;

    }
    //批量删除购物车商品
    public function batchDelCartGoods($projectArr)
    {
        foreach ($projectArr as $k=>$v)
        {
            $cartArr = $this->model->find($k)->toArray();

            if (!empty($cartArr))
            {
                //循环购物车的明细数组
                $infoArr = json_decode($cartArr['cart_info'],true);
            foreach ($infoArr as $kk=>$vv)
            {
                if (in_array($vv['sku_id'],$v)){
                    //删除改商品
                    unset($infoArr[$kk]);
                }
            }
            if (empty($infoArr)){
                $json_data = [
                    'cart_info' => ""
                ];
            }else{
                $json_data = [
                    'cart_info' => json_encode($infoArr)
                ];
            }
                $this->model->where(['cart_id' => $k])->update($json_data);

                return true;
            }

        }
        return true;
    }


    //根据作品id构建购物车数据
    public function shoppingCar($data)
    {
        $prjIds = explode(",",$data);
        $orderProductsRepository = app(SaasOrderProductsRepository::class);
        $projectsRepositories = app(SaasProjectsRepository::class);
        foreach ($prjIds as $key=>$value) {
            //如果是实物
            if(strpos($value,'-') !== false){
                $ids = explode("-",$value);
                $value = $ids[0];
                $prjInfo = $orderProductsRepository->getTableList(['ord_prod_id'=>$ids[1]])->toArray();
            }else{
                $prjInfo = $projectsRepositories->getTableList(['prj_id' => $value])->toArray();
            }
            if(empty($prjInfo)){
                $ret_data = [
                    'status'=>false,
                    'msg'=>"找不到对应的作品信息"
                ];
                return $ret_data;
            }
            $prjInfo = $prjInfo['data'][0];
            $num=1;
            if(!empty($prjInfo['prj_temp'])){
                $num = $prjInfo['prj_temp']['ord_quantity'];
            }
            $car_info[$key] = [
                'projects_id'=>$value,
                'sku_id'=>$prjInfo['sku_id'],
                'num'=>$num,
            ];
        }
        $car_data['user_id'] = $this->agent_id;

        //渠道id
        $salesChanel = app(SaasSalesChanelRepository::class);
        $cha_id = $salesChanel->getAgentChannleId();
        $car_data['cha_id'] = $cha_id;
        $car_data['cart_info'] = $car_info;

        $ret_data = [
            'status'=>true,
            'data'=>$car_data
        ];
        return $ret_data;
    }





}
