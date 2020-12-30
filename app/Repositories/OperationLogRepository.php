<?php
namespace App\Repositories;

use DB;
use App\Models\CmsAdmin;
/**
 * Created by PhpStorm.
 * User: hlt
 * Date: 2020/3/1 0001
 * Time: 21:11
 */
class OperationLogRepository extends BaseRepository
{
    /**
     *
     * @param $tables collection集合
     * @return mixed
     */
    public function getOperatorName($data)
    {
        $model = app(CmsAdmin::class);

        foreach ($data as $k => $v){
            $user_info = json_decode($model->where(['cms_adm_id' => $v['operator_id']])->get(),true);
            $data[$k]['operator_name'] = $user_info[0]['cms_adm_username']??"";
            $data[$k]['add_time_val'] = date( "Y-m-d H:i:s",$v['add_time']);
        }
        return $data;

    }
    /**
     *
     * @param $tables collection集合
     * @return mixed
     */
    public function getOperatorId($name)
    {

            $model = app(CmsAdmin::class);
            $user_info = json_decode($model->where(['cms_adm_username' => $name])->get(),true);
            if (!empty($user_info))
            {
                $id = $user_info[0]['cms_adm_id'];
            }else{
                //查无该操作人
                $id = 9999999999;
            }
          return $id;

    }



}