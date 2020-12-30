<?php
namespace App\Repositories;

use App\Models\SaasCompoundQueue;
use App\Services\Helper;

/**
 * 合成队列仓库
 *
 * 合成队列仓库
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/23
 */
class SaasCompoundQueueRepository extends BaseRepository
{
    private $timeout = 60*60;
    private $limit = 10;
    public function __construct(SaasCompoundQueue $model)
    {
        $this->model = $model;
    }


    /**
     * 获取合成队列
     * @param $condition
     * @return mixed
     */
    public function getQueueList($condition)
    {
        //是否存在商户参数
        $where = [];
        $query = $this->model;
        if (isset($condition['sp_id']))
            $condition['sp_id'] = $condition['sp_id'] == -1 ? 0 : $condition['sp_id'];
        else
            $condition['sp_id'] = 0;
        if(!empty($condition['sp_id'])) {
            $where[] = ['mch_id',$condition['sp_id']];
        }

        if (!empty($condition['server_id'])) {
            $where[] = ['comp_queue_serv_id',$condition['server_id']];
        }

        $isAppoint = PUBLIC_NO;
        //指定记录查询
        if (!empty($condition['wid'])) {
            $where[]              = ['works_id', $condition['wid']];
            $where[]     = ['comp_queue_status', '!=' , 'error'];
            $isAppoint = PUBLIC_YES;
        } elseif(!empty($condition['project_sn'])) {
            $where[]            = ['project_sn', $condition['project_sn']];
            $where[]     = ['comp_queue_status', '!=' , 'error'];
            $isAppoint = PUBLIC_YES;
        }

        if (isset($condition['type']))
            $where['type'] = $condition['type'];
        else
            $where['type'] = 1;

        if (!empty($where)) {
            $query = $query->where($where);
        }

        //不指定的情况
        if (empty($isAppoint)) {
            $query = $query->where(function($q) use ($where) {
                $timeout = time() - $this->timeout;
                $q->whereRaw("(comp_queue_status=?) OR (timeline<".$timeout." AND comp_queue_status=?)", ['ready', 'progress']);
            });
        }
        $count = 0;
        if (!empty($condition['size'])) {
            $count = $query->count();
        }

        $query = $query->orderby('timeline','asc');

        if (empty($condition['size']) ) {
            $list  = $query->limit($this->limit)->get()->toArray();
        } else {
            if ($condition['size'] == -1) {
                $list  = $query->get()->toArray();
            } else {
                $list  = $query->limit($condition['size'])->get()->toArray();
            }

        }
        //dd($list);

       // $list  = $query->orderby('timeline','asc')->limit($this->limit)->get()->toArray();

        return ['list' => $list, 'count' => $count];
    }

    /**
     *  获取列表数据
     *
     * @return array
     */
    public function getTableList($where=null, $order=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);

        $workwhere = [];
        if(!empty($where['work_no'])){
            $workwhere['prj_sn'] = $where['work_no'];
            unset($where['work_no']);
        }

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        $query = $this->model->WhereHas(
            'project',function($query) use ($workwhere) {
            if (!empty($workwhere)) {
                $query->where($workwhere);
            }
        })->with(['project']);
        //查询时间
        if(isset($where['compound_time'])){
            $compound_time = $where['compound_time'];
            $time_list = Helper::getTimeRangedata($compound_time);
            $query = $query->where("start_time",">=",$time_list['start'])->where('end_time','<=',$time_list['end']);
            unset($where['compound_time']);
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
        if(empty($data['id'])) {
            unset($data['id']);
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('comp_queue_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['comp_queue_id'] = $priKeyValue;
            //将数据写入缓存
            $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
        }
        return $ret;

    }

    /**
     *  统计队列状态
     *
     * @return array
     */
    public function getQueueStatus()
    {
        $progressStatus = $this->model->where(['comp_queue_status'=>'progress'])->get()->toArray();
        $finishStatus = $this->model->where(['comp_queue_status'=>'finish'])->get()->toArray();
        $errorStatus = $this->model->where(['comp_queue_status'=>'error'])->get()->toArray();
        $list = [];
        $list['progress'] = count($progressStatus);
        $list['finish']   = count($finishStatus);
        $list['error']    = count($errorStatus);

        return $list;
    }

    /**
     *  改变队列状态
     * @param $data
     * @return bool
     */
    public function updateQueueStatus($data)
    {
        if(!empty($data)) {
            $this->model->where('comp_queue_id',$data['id'])->update(['comp_queue_status'=>$data['status']]);
            return true;
        }else{
            return false;
        }

    }
    //获取时间段作品合成各个状态的数量
    public function projectCompoundCount($timeArr,$merchant)
    {
        $where = [];
        if (!empty($merchant) && $merchant!='all')
        {
            $where['mch_id'] = $merchant;
        }
        //获取待合成数量集合
        //获取0-2小时内未合成成功的作品数量
        $data['waiting']['now_hours'] = $this->model->where($where)->whereIn('comp_queue_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['two_hours'],time()])->count();
        //获取2-4小时内未合成成功的作品
        $data['waiting']['two_hours'] = $this->model->where($where)->whereIn('comp_queue_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['four_hours'],$timeArr['two_hours']])->count();
        //获取4-6小时内未合成成功的作品
        $data['waiting']['four_hours'] = $this->model->where($where)->whereIn('comp_queue_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['six_hours'],$timeArr['four_hours']])->count();
        //获取6-12小时内未合成成功的作品
        $data['waiting']['six_hours'] = $this->model->where($where)->whereIn('comp_queue_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['tew_hours'],$timeArr['six_hours']])->count();
        //获取12-24小时内未合成成功的作品
        $data['waiting']['tew_hours'] = $this->model->where($where)->whereIn('comp_queue_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['tf_hours'],$timeArr['tew_hours']])->count();
        //获取24小时以外未合成成功的作品
        $data['waiting']['tf_hours'] = $this->model->where($where)->whereIn('comp_queue_status',['ready','progress','error'])->where('created_at', '<',$timeArr['tf_hours'])->count();

        /*//获取合成中数量集合
        //获取2-4小时内合成中的作品
        $data['progress']['two_hours'] = $this->model->where($where)->where('comp_queue_status','progress')->whereBetween('start_time', [$timeArr['four_hours'],$timeArr['two_hours']])->count();
        //获取4-6小时内合成中的作品
        $data['progress']['four_hours'] = $this->model->where($where)->where('comp_queue_status','progress')->whereBetween('start_time', [$timeArr['six_hours'],$timeArr['four_hours']])->count();
        //获取6-12小时内合成中的作品
        $data['progress']['six_hours'] = $this->model->where($where)->where('comp_queue_status','progress')->whereBetween('start_time', [$timeArr['tew_hours'],$timeArr['six_hours']])->count();
        //获取12-24小时内合成中的作品
        $data['progress']['tew_hours'] = $this->model->where($where)->where('comp_queue_status','progress')->whereBetween('start_time', [$timeArr['tf_hours'],$timeArr['tew_hours']])->count();
        //获取24小时以外合成中的作品
        $data['progress']['tf_hours'] = $this->model->where($where)->where('comp_queue_status','progress')->where('start_time', '<',$timeArr['tf_hours'])->count();

        //获取合成出错数量集合
        //获取2-4小时内合成出错的作品
        $data['error']['two_hours'] = $this->model->where($where)->where('comp_queue_status','error')->whereBetween('start_time', [$timeArr['four_hours'],$timeArr['two_hours']])->count();
        //获取4-6小时内合成出错的作品
        $data['error']['four_hours'] = $this->model->where($where)->where('comp_queue_status','error')->whereBetween('start_time', [$timeArr['six_hours'],$timeArr['four_hours']])->count();
        //获取6-12小时内合成出错的作品
        $data['error']['six_hours'] = $this->model->where($where)->where('comp_queue_status','error')->whereBetween('start_time', [$timeArr['tew_hours'],$timeArr['six_hours']])->count();
        //获取12-24小时内合成出错的作品
        $data['error']['tew_hours'] = $this->model->where($where)->where('comp_queue_status','error')->whereBetween('start_time', [$timeArr['tf_hours'],$timeArr['tew_hours']])->count();
        //获取24小时以外合成出错的作品
        $data['error']['tf_hours'] = $this->model->where($where)->where('comp_queue_status','error')->where('start_time', '<',$timeArr['tf_hours'])->count();*/

        return $data;
    }




}