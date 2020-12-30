<?php
namespace App\Repositories;
use App\Models\BaseException;

/**
 * 仓库模板
 * 仓库模板
 * @author:
 * @version: 1.0
 * @date:
 */
class BaseExceptionRepository extends BaseRepository
{

    public function __construct(BaseException $model)
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
        $exceptionConfig = config("exception");
        $exceptionArr=[];$list = [];
        $i=0;
        foreach ($exceptionConfig as $k =>$v){
            $exceptionArr[$k] =  __($v);
        }
       // dump($exceptionArr);die;
            if(!empty ($where)){//dump(99);die;
            $ecode_arr = array_key_exists($where,$exceptionArr);
            if($ecode_arr==true){
                foreach ($exceptionArr as $k =>$v){
                    if($where==$k){
                        if(is_array($v)==true){
                            $list[$i]['ecode'] = $k;
                            $list[$i]['evalue'] = $v['dev'];
                        }else{
                            $list[$i]['ecode'] = $k;
                            $list[$i]['evalue'] = $v;
                        }
                    }
                    $i++;
                }
                return $list;
            }else{
                return $list;
            }
        }else{//dump(88);
            foreach ($exceptionArr as $k =>$v){
                if(is_array($v)==true){
                    $list[$i]['ecode'] = $k;
                    $list[$i]['evalue'] = $v['dev'];
                    //unset($v);
                }else{
                    $list[$i]['ecode'] = $k;
                    $list[$i]['evalue'] = $v;
                }

                $i++;
            }
            return $list;
        }


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
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $ret =$this->model->where('id',$priKeyValue)->update($data);
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
