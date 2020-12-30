<?php
namespace App\Repositories;
use App\Models\AgentUserAccount;
use App\Models\DmsAuthGroup;
use App\Models\DmsMerchantAccount;
use App\Models\SaasArticle;
use App\Models\SaasCustomerLevel;
use App\Models\SaasOrders;
use App\Models\SaasProjects;
use App\Models\SaasSalesChanel;
use App\Services\Helper;

/**
 * 分销商相关数据仓库
 *
 * 提供分销信息及分销账号的模型数据
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/1
 */
class AgentRepository extends BaseRepository
{
    protected $mch_id;

    public function __construct(DmsMerchantAccount $dmsMerchantAccount,DmsAuthGroup $authGroup,SaasSalesChanel $chanel,SaasCustomerLevel $customerLevel,
                                SaasArticle $article,SaasOrdersRepository $ordersRepository,SaasProjects $projects,SaasOrders $orders)
    {
        $this->mch_id = session("admin")['mch_id'];
        $this->agent_info_id = session("admin")['agent_info_id'];

        $this->model =$dmsMerchantAccount;
        $this->dmsAuthGroupModel = $authGroup;
        $this->chanelModel = $chanel;
        $this->customerLevel = $customerLevel;
        $this->article = $article;
        $this->ordersRepository = $ordersRepository;
        $this->projects = $projects;
        $this->orders = $orders;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        if(isset($where['dms_adm_status'])){
            $where['dms_adm_status'] = intval($where['dms_adm_status']);
        }
        $where = $this->parseWhere($where);
        $where['agent_info_id'] = $this->agent_info_id;

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        $where_info = [];
        if(isset($where['agent_type'])){
            $where_info['agent_type'] = $where['agent_type'];
            unset($where['agent_type']);
        }

        $query = $this->model->orWhereHas(
            'info',function ($query) use ($where_info){
                if (!empty($where_info)) {
                    return $query->where($where_info);
                }
            }
        )->with('info');

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
        //密码盐
        $data['dms_adm_salt'] = Helper::build();

        if(empty($data['id'])) {
            unset($data['id']);
            unset($data['confirm_password']);

            $data['dms_adm_password'] = $this->setPassword($data['dms_adm_password'],$data['dms_adm_salt']);
            $data['created_at'] = time();
            $data['mch_id'] = $this->mch_id;
            $data['agent_info_id'] = session('admin')['agent_info_id'];
            $data['is_main'] = PUBLIC_NO; //是否为主账号:否

            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            unset($data['confirm_password']);

            if(!empty($data['dms_adm_password'])){
                $data['dms_adm_password'] = $this->setPassword($data['dms_adm_password'],$data['dms_adm_salt']);
            }else{
                unset($data['dms_adm_password']);
                unset($data['dms_adm_salt']);
            }
            $data['updated_at'] = time();

            $ret =$this->model->where('dms_adm_id',$priKeyValue)->update($data);
        }
        return $ret;

    }

    /**
     * 生成密码
     * @param $pwd 明文密码
     * @param string $salt 密码盐值
     * @return string
     */
    public function setPassword($pwd, $salt = '')
    {
        return md5($pwd.$salt);
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

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 获取DMS角色组(不带树级分类)
     * @return array
     */
    public function getGroupList($agent_id=null,$flag=null)
    {
        if(!empty($agent_id)){
            if(empty($flag)){
                $where = ['agent_id'=>$agent_id,'dms_group_status'=>PUBLIC_ENABLE];
            }else{
                $where = ['agent_id'=>$agent_id];
            }
            $groupList = $this->dmsAuthGroupModel->where($where)->get()->toArray();
        }else{
            $groupList = $this->dmsAuthGroupModel->get()->toArray();
        }
        $data = [];

        foreach ($groupList as $k=>$v){
            $data[$v['dms_group_id']] = $v['dms_group_name'];
        }

        return $data;
    }

    /**
     * 获取DMS账户信息、公告(控制台)
     * param $user_id 分销id
     * @return array
     */
    public function getAccountInfo($user_id)
    {
        //账户信息
        $res = $this->model->where(['dms_adm_id'=>$user_id])->select('agent_info_id','mch_id','dms_adm_username','dms_adm_group_id','dms_adm_logintime','dms_adm_avatar')->with('info')->first();

        //管理员分组名称
        $res['dms_adm_group_name'] = $this->dmsAuthGroupModel->where(['dms_group_id'=>$res['dms_adm_group_id']])->value('dms_group_name');

        //等级名称
        $res['level_name'] = $this->customerLevel->where(['cust_lv_id'=>$res['info']['cust_lv_id']])->value('cust_lv_name');

        //公告
        $res['news'] = $this->article->where(['mch_id'=>$res['mch_id'],'art_sign'=>GOODS_MAIN_CATEGORY_ANNOUNCE])->select('art_title','created_at')->orderBy('created_at','desc')->limit(7)->get()->toArray();
        return $res;
    }

    /**
     * 获取订单销售数据(控制台)
     * param $user_id 分销id $mch_id 商户id
     * @return array
     */
    public function getSalesInfo($user_id,$mch_id)
    {
        $s_time = strtotime('00:00:00')-24*60*60;
        $e_time = strtotime('23:59:59')-24*60*60;
        //昨日成交总金额
        $data['total_amount'] = $this->ordersRepository->getTodayAmount($mch_id,$s_time,$e_time,$user_id);

        //昨日成交订单数
        $data['total_order'] = $this->ordersRepository->getTodayOrderCount($mch_id,$s_time,$e_time,$user_id);

        //昨日总作品数
        $data['total_work'] = $this->projects->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', [$s_time,$e_time])->count();

        return $data;
    }

    /**
     * 获取DMS控制台图表数据
     * param $user_id 分销id $mch_id 商户id
     * @return array
     */
    public function getChartData($user_id,$mch_id)
    {
        //图表数据
        $time_arr = $this->getTimeStamp();

        //交易金额
        $data['money']['zero_four'] = floatval($this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['zero_four'])->sum('order_real_total'));
        $data['money']['four_eight'] = floatval($this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['four_eight'])->sum('order_real_total'));
        $data['money']['eight_twelve'] = floatval($this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['eight_twelve'])->sum('order_real_total'));
        $data['money']['twelve_sixteen'] = floatval($this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['twelve_sixteen'])->sum('order_real_total'));
        $data['money']['sixteen_twenty'] = floatval($this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['sixteen_twenty'])->sum('order_real_total'));
        $data['money']['twenty_twenty_four'] = floatval($this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['twenty_twenty_four'])->sum('order_real_total'));

        //成交订单数
        $data['order']['zero_four'] = $this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['zero_four'])->count();
        $data['order']['four_eight'] = $this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['four_eight'])->count();
        $data['order']['eight_twelve'] = $this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['eight_twelve'])->count();
        $data['order']['twelve_sixteen'] = $this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['twelve_sixteen'])->count();
        $data['order']['sixteen_twenty'] = $this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['sixteen_twenty'])->count();
        $data['order']['twenty_twenty_four'] = $this->orders->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['twenty_twenty_four'])->count();

        //作品数
        $data['work']['zero_four'] = $this->projects->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['zero_four'])->count();
        $data['work']['four_eight'] = $this->projects->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['four_eight'])->count();
        $data['work']['eight_twelve'] = $this->projects->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['eight_twelve'])->count();
        $data['work']['twelve_sixteen'] = $this->projects->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['twelve_sixteen'])->count();
        $data['work']['sixteen_twenty'] = $this->projects->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['sixteen_twenty'])->count();
        $data['work']['twenty_twenty_four'] = $this->projects->where(['mch_id'=>$mch_id,'user_id'=>$user_id])->whereBetween('created_at', $time_arr['twenty_twenty_four'])->count();
        return $data;
    }

    //返回昨天每隔4小时的时间戳
    public function getTimeStamp()
    {
        $time_arr['zero_four']  = [strtotime('00:00:00')-24*60*60,strtotime('03:59:59')-24*60*60];
        $time_arr['four_eight'] = [strtotime('04:0:00')-24*60*60,strtotime('07:59:59')-24*60*60];
        $time_arr['eight_twelve']  = [strtotime('08:00:00')-24*60*60,strtotime('11:59:59')-24*60*60];
        $time_arr['twelve_sixteen']  = [strtotime('12:00:00')-24*60*60,strtotime('15:59:59')-24*60*60];
        $time_arr['sixteen_twenty']   = [strtotime('16:00:00')-24*60*60,strtotime('19:59:59')-24*60*60];
        $time_arr['twenty_twenty_four']   = [strtotime('20:00:00')-24*60*60,strtotime('23:59:59')-24*60*60];
        return $time_arr;
    }


}
