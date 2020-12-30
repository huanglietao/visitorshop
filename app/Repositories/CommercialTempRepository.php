<?php
namespace App\Repositories;
use App\Models\SaasCategory;
use App\Models\SaasMainTemplates;
use App\Models\SaasMainTemplatesPages;
use App\Models\SaasTemplatesAttachment;
use App\Models\TempSizeRelation;
use App\Services\Outer\CommonApi;
use App\Services\Template\Attachment;
use App\Services\Template\BlankMain;
use App\Services\Template\Main;
use App\Services\Helper;
use Illuminate\Support\Facades\Redis;

/**
 * 仓库模板
 * 商业模板仓库模板
 * @author: dai
 * @version: 1.0
 * @date: 2020/6/22
 */
class CommercialTempRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasMainTemplates $model,TempSizeRelation $sizeRelationModel,SaasProductsSizeRepository $sizeProduct)
    {
        $this->model =$model;
        $this->srModel = $sizeRelationModel;
        $this->sizeRepo = $sizeProduct;
    }

    /**
     * @param null $where
     * @param null $order
     * @param null $setlimit (传条数)
     * @return mixed
     */
    public function getTableList($where=null, $order=null)
    {
        //存入redis
        $redisKey = 'temp_'.md5("template/previewList?limit=9999&page=998&order=asc&offset=9988");
        /*if(!empty($where)){
            $redisKey = 'temp_'.md5("template/previewList?limit=9999&page=998&order=asc&offset=9988".$where['temp_cateid']);
        }*/

        if(Redis::exists($redisKey)==0)
        {
            $apiService = app(CommonApi::class);
            $tempCate = $apiService->request(config("template.coml_url").'/template/classList',[],'GET');
            $tempCateName = Helper::ListToKV('tplClassId','name',$tempCate);
            $lists = [];
            foreach ($tempCate as $k=>$v)
            {
                $tempList = $apiService->request(config("template.coml_url").'/template/previewList?limit=9999&page=1&order=asc&tplClassId='.$v['tplClassId'],[],'GET');
                $lists[$v['tplClassId']]['list'] = $tempList['page']['list'];
            }
            /*if(empty($where)){

            }else{
                $tempCateName = [$where['temp_cateid']=>$where['temp_catename']];
                $lists = [];
                $tempList = $apiService->request(config("template.coml_url").'/template/previewList?limit=9999&page=1&order=asc&tplClassId='.$where['temp_cateid'],[],'GET');
                $lists[$where['temp_cateid']]['list'] = $tempList['page']['list'];
            }*/

            $result =[];
            $i=0;
            foreach ($lists as $k=>$v)
            {
                foreach ($v['list'] as $kk=>$vv)
                {
                    $result[$i]['cid'] = $vv['tplClassId'];
                    $result[$i]['tid'] = $vv['tplId'];
                    $result[$i]['temp_name'] = $vv['title'];
                    $result[$i]['cate_name'] = $tempCateName[$vv['tplClassId']];
                    $url = json_decode($vv['preview'],true);
                    if($url[0]['url']){
                        $result[$i]['thumb'] = $url[0]['url'];
                    }else{
                        $result[$i]['thumb'] = '';
                    }

                    $i++;
                }
            }

            Redis::setex($redisKey,1800,serialize($result));
        }else{
            $result = unserialize(Redis::get($redisKey));
        }

        foreach ($result as $k=>$v)
        {
            $sizeRelation = $this->srModel->where('tid',$v['tid'])->first();
            if($sizeRelation){
                $result[$k]['spec']= $sizeRelation['size_id'];
            }else{
                $result[$k]['spec']= ZERO;
            }
        }

        return $result;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        //先查规格绑定是否有数据
        $sizeRelation = $this->srModel->where('tid',$data['id'])->first();
        if(empty($sizeRelation)) { //添加
            $params = [
                'temp_cate'   => $data['cid'],
                'tid'       => $data['id'],
                'size_id'   => $data['size_id'],
                'created_at'=> time(),
            ];
            $ret = $this->srModel->insertGetId($params);
        } else { //更新
            $params = [
                'tid'       => $data['id'],
                'size_id'   => $data['size_id'],
                'updated_at'=> time(),
            ];
            $ret = $this->srModel->where('temp_size_id',$sizeRelation['temp_size_id'])->update($params);
        }

        return $ret;

    }








}
