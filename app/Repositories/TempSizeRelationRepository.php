<?php
namespace App\Repositories;

use App\Models\TempSizeRelation;
use App\Services\Helper;

/**
 * 仓库模板
 * 商印模板关联规格仓库
 * @author: david
 * @version: 1.0
 * @date: 2020/8/20
 */
class TempSizeRelationRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(TempSizeRelation $model)
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
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model;
        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);
        return $list;
    }




}
