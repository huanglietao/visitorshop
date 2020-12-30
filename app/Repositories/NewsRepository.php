<?php
namespace App\Repositories;
use App\Models\NewsInfo;
use App\Services\Helper;

/**
 * 分销商相关数据仓库
 *
 * 提供分销信息及分销账号的模型数据
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/1
 */
class NewsRepository extends BaseRepository
{
    protected $newsInfo;

    public function __construct(NewsInfo $newsInfo)
    {
        $this->newsInfo =$newsInfo;
    }

    /**
     * 获取AgentUserAccount模型的列表
     * @param mixed $where 查询条件
     * @param mixed $order 排序
     * @return array
     */
    public function getNewsList($where=null, $order=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:10;  //这个10取配置里的
        if(empty($where) || $where['newstype']=='all'){
            $list = $this->newsInfo->where('mid',59)->paginate($limit);
        }else{
            $list = $this->newsInfo->where('type',$where['newstype'])->paginate($limit);
        }

        return $list;
    }

    /**
     * 创建账号
     * @param $data
     */
    public function getNewsDetail($id)
    {
        $news_detail = $this->newsInfo->where('id',$id)->get();
       // dump($news_detail);exit;
        return $news_detail;
        $data['salt'] = Helper::build('alnum', 6);
        $data['password'] = $this->setPassword($data['password'],$data['salt']);

        $ret = $this->agentUserAccount->create($data);


    }

}