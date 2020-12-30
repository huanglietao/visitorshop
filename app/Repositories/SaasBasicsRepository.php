<?php

namespace App\Repositories;

use App\Models\SaasSystemSetting;

/**
 * 系统设置仓库模板
 * @author:cjx
 * @version: 1.0
 * @date:2020/03/30
 */
class SaasBasicsRepository extends BaseRepository
{

    public function __construct(SaasSystemSetting $model)
    {
        $this->model =$model;
    }

    /**
     * @param $adminId 管理员id
     * @return mixed
     */
    public function getInfo($adminId)
    {
        $list = $this->model->where("admin_id",$adminId)->first();
        $list = empty($list) == true ? null : $list->toArray();

        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['setting_id'])) {
            unset($data['setting_id']);
            $data['created_at'] = time();

            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['setting_id'];
            unset($data['setting_id']);
            unset($data['_token']);
            $data['updated_at'] = time();

            $ret =$this->model->where('setting_id',$priKeyValue)->update($data);
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

}
