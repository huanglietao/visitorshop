<?php

namespace App\Repositories;

use App\Models\OmsAgentDeploy;

/**
 * 站点设置仓库模板
 * @author:cjx
 * @version: 1.0
 * @date:2020/05/11
 */
class OmsAgentDeployRepository extends BaseRepository
{

    public function __construct(OmsAgentDeploy $model)
    {
        $this->model =$model;
    }

    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['dms_deploy_id'])) {
            unset($data['dms_deploy_id']);
            $data['created_at'] = time();

            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['dms_deploy_id'];
            unset($data['dms_deploy_id']);
            unset($data['_token']);
            $data['updated_at'] = time();

            $ret =$this->model->where('dms_deploy_id',$priKeyValue)->update($data);
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

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 获取站点配置信息
     * @param $mch_id
     * @return array
     */
    public function getDeployInfo($mch_id)
    {
        $data = $this->model->where('mch_id',$mch_id)->first();
        return $data;
    }

}
