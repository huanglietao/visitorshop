<?php
namespace App\Repositories;

use App\Models\DmsNews;


/**
 * 仓库模板
 * 分销商消息中心仓库数据处理
 * @author: david
 * @version: 1.0
 * @date: 2020/5/26
 */
class DmsNewsRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(DmsNews $model)
    {
        $this->model =$model;
    }


    public function getSaveNews($artid,$agentid)
    {
        $newInfo = $this->model->where(['articles_id'=>$artid,'agent_id'=>$agentid])->first();
        if(empty($newInfo)){
            $data= ['articles_id'=>$artid,'agent_id'=>$agentid];
            $this->model->insertGetId($data);
        }

    }

}
