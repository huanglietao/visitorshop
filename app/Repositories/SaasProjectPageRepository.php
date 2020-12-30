<?php
namespace App\Repositories;

use App\Models\SaasProjectPage;

/**
 * 作品子页仓库
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/9
 */
class SaasProjectPageRepository extends BaseRepository
{
    public function __construct(SaasProjectPage $model)
    {
        $this->model = $model;
    }


    //克隆作品
    public function clonePage($prj_id,$new_prj_id)
    {
        $page = $this->model->where(['prj_id'=>$prj_id])->get()->toArray();

        foreach ($page as $k=>$v){
            unset($v['prj_page_id']);
            $v['created_at'] = time();
            $v['updated_at'] = time();
            $v['prj_id'] = $new_prj_id;
            $ret = $this->model->insertGetId($v);
        }
        return $ret;
    }
}