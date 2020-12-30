<?php
namespace App\Services\Works;
use App\Exceptions\ApiException;
use App\Repositories\SaasProductsPrintRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProjectPageRepository;
use App\Repositories\SaasProjectsOrderTempRepository;
use App\Repositories\SaasProjectsRepository;
use App\Services\ChanelUser;
use App\Services\Common\Mongo;
use App\Services\Goods\Info;
use App\Services\Helper;
use Illuminate\Support\Facades\Redis;

/**
 * diy作品抽像类
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/9
 */
class WorksAbstract
{
    protected $works_id;          //作品id
    protected $user_id;           //用户id
    protected $agent_id;          //分销则是分销id
    protected $temp_id;           //模板id
    protected $mid;               //商户id
    protected $sub_system_id;     //商户id
    protected $product_id;        //货品id
    protected $work_name;         //作品名称
    protected $work_extra;        //    不同子系统或操作块有不同的额外信息
    protected $mask_count;        //所有相框的数量
    protected $pages;             //子页数据集合
    protected $is_submit;         //是否已提交
    protected $sub_system;         //子系统标识
    protected $sys_version;        //软件版本号
    protected $project_id;         //冲印项目号
    protected $remark;             //用户备注
    protected $exception;          //作品异常信息
    protected $p_nums;             //p数
    protected $theme_id;            //模板主题
    protected $is_mobile = 0;           //是否为手机作品
    protected $empty_mask_count = 0;  //画框空图数量
    protected $bad_px_count = 0;         //像素不足图片数量

    protected $repoWorks;
    protected $repoChanel;
    protected $repoGoods;
    protected $repoPrint;
    protected $repoPages;
    protected $repoWorksExt; //作品额外数据
    public function __construct(SaasProjectsRepository $works, ChanelUser $chanelUser, SaasProductsRepository $repoGoods,
        SaasProductsPrintRepository $print, SaasProjectPageRepository $repoPages, SaasProjectsOrderTempRepository $worksExt)
    {
        $this->repoWorks = $works;
        $this->repoChanel = $chanelUser;
        $this->repoGoods = $repoGoods;
        $this->repoPrint =$print;
        $this->repoPages = $repoPages;
        $this->repoWorksExt = $worksExt;
    }

    /**
     * 生成作品id
     */
    public function getWorksId()
    {
        $data['prj_sn'] = $this->createWorksNo();
        $data['created_at'] = time();
        $ret = $this->repoWorks->insert($data);
        return $ret['prj_id'];
    }

    /**
     * 生成作品编号
     */
    public function createWorksNo()
    {
        $no =  'W'.date('d').strtoupper(substr(uniqid(),8)).mt_rand(1,10);
        if (Redis::sismember("works_no", $no)) {
            $no = $this->createWorksNo();
        } else {
            Redis::sadd("works_no", $no);
        }
        return $no;
    }

    /**
     * 获取作品的p数
     * @param $arr_pages 子页数据集合
     * @return mixed
     */
    public function worksGetP($arr_pages)
    {
        $p = 0;
        if(empty($arr_pages)) {
            return 0;
        }
        foreach ($arr_pages as $k=>$v) {
            if($v['type'] == 1) { //取内页
                if($v['spread'] == 1) {
                    $p+=2;
                } else {
                    $p+=1;
                }
            }
        }
        return $p;
    }

    /**
     * 格式化传入的数据(默认以分销的数据为主)
     * @param $params
     * @throws ApiException
     */
    public function  formatParams($params)
    {
        $this->works_id = isset($params['wid']) ? $params['wid']: 0;
        $this->user_id = isset($params['uid']) ? $params['uid']: 0;
        $this->user_id = $this->user_id === 'null' ? 0 : $this->user_id;

        $this->agent_id = isset($params['agent_id']) ? $params['agent_id']: 0;
        $this->temp_id = isset($params['tsid']) ? $params['tsid']: 0;
        $this->mid = isset($params['sp_id']) ? $params['sp_id']: 0;
        $this->product_id = isset($params['pid']) ? $params['pid']: 0;
        $this->work_name = isset($params['work_name']) ? $params['work_name']: '';
        $this->work_extra = isset($params['work_extra_info']) ? $params['work_extra_info']: '';
        $this->mask_count = isset($params['mask_total_count']) ? $params['mask_total_count']: 0;
        $this->pages = isset($params['pages']) ? $params['pages']: 0;
        $this->is_submit = isset($params['is_submit']) ? $params['is_submit']: 0;
        $this->sub_system = isset($params['sub_system']) ? $params['sub_system']: '';
        $this->p_nums = isset($params['p_nums']) ? $params['p_nums']: 0;
        $this->theme_id = isset($params['theme_id']) ? $params['theme_id']: 10;
        $this->is_mobile =  isset($params['is_mobile']) ? $params['is_mobile']: 0;

        $chanelInfo = $this->repoChanel->getChanelInfo(['short_name' => $this->sub_system]);

        $this->sub_system_id  = !empty($chanelInfo) ?$chanelInfo['cha_id'] :0;
        $this->empty_mask_count = isset($params['mask_empty_count']) ? $params['mask_empty_count']: 0;
        $this->bad_px_count = isset($params['bad_px_count']) ? $params['bad_px_count']: 0;

        if (empty($this->sub_system_id)) {
            throw new ApiException('子系统不存在', '50003');
        }

        $this->sys_version = isset($params['sys_version']) ? $params['sys_version']: '';
        $this->project_id = isset($params['project_id']) ? $params['project_id']: 0;
        $this->remark = isset($params['remark']) ? $params['remark']: '';
        $this->exception = isset($params['exception']) ? $params['exception']: '';
    }


    /**
     *保存作品主表
     */
    public function saveWorksMain()
    {
        //用户id
        if(!empty($this->user_id)) {
            $saveData['user_id']  = $this->user_id;
        } else {
            $saveData['user_id']  = $this->agent_id;
        }

        //备注信息
        if(!empty($this->remark)) {
            $saveData['remark']  = $this->remark;
        }

        $saveData['mch_id'] = $this->mid;
        $saveData['prj_tpl_id'] = $this->temp_id;
        $saveData['sku_id'] = $this->product_id;
        $saveData['prj_name'] = $this->work_name;
        $saveData['is_mobile'] = $this->is_mobile;

        $productInfo = app(Info::class)->getGoodsBySku($this->product_id);
        $goodsId = $productInfo['prod_id'];

        $saveData['prod_id'] = $goodsId;
        $saveData['prj_project_id'] = $this->project_id;
        $saveData['prj_photo_count'] = $this->mask_count;
        $saveData['theme_id'] = $this->theme_id;

        if ($this->is_submit == 1){
            $saveData['prj_status'] = WORKS_DIY_STATUS_WAIT_CONFIRM;
        } else {
            $saveData['prj_status'] = WORKS_DIY_STATUS_MAKING;
        }
        //取pages里内页数
        $inner = array_filter($this->pages, function($var){
            return $var['type'] == 1;
        });

        if(empty($this->p_nums)) {

            $goodsInfo = $this->repoGoods->getRow(['prod_id' => $goodsId, 'prod_onsale_status' => 1]);

            $goodsPrintInfo = $this->repoPrint->getRow(['prod_id' => $goodsInfo['prod_id']])->toArray();

            $standard = app(Info::class)->getGoodSizeInfo($goodsPrintInfo['prod_size_id'], $goodsId);

            $sizeDetail = $standard['detail_list'];
            if (empty($sizeDetail)) {
                Helper::apiThrowException('40015',__FILE__.__LINE__);
            }
            $isCrossPage = 0;
            foreach ($sizeDetail as $k=>$v) {
                if($v['size_type'] == GOODS_SIZE_TYPE_INNER) {
                    if (!empty($v['size_is_cross'])) {
                        $isCrossPage = 1;
                        break;
                    }
                }
            }
            //内页数量，也就是作品的数
            if(!empty($isCrossPage)) { //双页
                $saveData['prj_page_num'] = count($inner) *2;
            } else {
                $saveData['prj_page_num'] = count($inner);
            }

        } else {
            $saveData['prj_page_num'] =  $this->p_nums;
        }
        $thickness = app(Info::class)->getGoodsSpineThickness($this->product_id,  $saveData['prj_page_num']);
        $saveData['prj_thickness'] = $thickness;

        $saveData['cha_id'] =  $this->sub_system_id;

        //空图和像素不足情况
        $saveData['empty_mask_count'] = $this->empty_mask_count;
        $saveData['bad_px_count']     = $this->bad_px_count;
        $saveData['sys_version']     = $this->sys_version;

        $saveData['submit_time'] = time();
        if (!empty($this->works_id)) {
            $saveData['updated_at'] = time();
            $ret = $this->repoWorks->update(['prj_id' => $this->works_id],$saveData);
            $wid = $this->works_id;
        } else {
            $saveData['created_at'] = time();
            $saveData['updated_at'] = time();
            $wid = $this->repoWorks->insert($saveData);
        }

        return $wid;
    }

    /**
     * 保存子页
     * @param $wid 作品id
     * @param $pages 子页数据
     * @throws  ApiException
     * @return array $arr_stage 舞台数组
     */
    public function saveWorksChild($wid , $pages)
    {
        $childPages = $this->repoPages->getRows(['prj_id' =>$wid], 'prj_page_id', 'asc')->toArray();
        $arrExistsPage =  array_column($childPages, 'prj_page_id');

        $arrInsert = [];  //临时中转
        $arrStage = [];
        $postIds = [];     //提交过来的id集合
        $insertData = [];  //插入的数据
        $updateData = [];  //更新的数据
        foreach($pages as $k=>$v){
            //$arr_insert[$k]['id'] = $v['id'];
            $postIds[] =  $v['id'];

            $arrInsert['prj_id'] = $wid;
            $arrInsert['main_temp_page_id'] = !empty($v['tid']) ? $v['tid']: 0;

            $type = $v['type'] == 0 ? 1 :2;
            $arrInsert['prj_page_type']  = $type;
            $arrInsert['prj_page_photo_count'] = $v['mask_count'];
            $arrInsert['prj_page_name'] = isset($v['name']) ? $v['name']:'';
            $arrInsert['prj_page_sort'] = $v['index'];
            $arrInsert['relation_id'] = $v['pid'];
            $arrInsert['mask_empty_count'] = isset($v['mask_empty_count']) ? $v['mask_empty_count']:0;//.by david
            $arrInsert['maks_badpx_count'] = isset($v['maks_badpx_count']) ? $v['maks_badpx_count']:0;
            $arrStage[$v['index']] = $v['stage_content'];


            if(empty($v['id'])) {  //需要新增的
                $insertData[$k] = $arrInsert;
            } else { //数据存在则更新
                $updateData[$k] = $arrInsert;
                $updateData[$k]['prj_page_id'] = $v['id'];
            }

        }
  
        //删除的项
        $deleteData = array_diff($arrExistsPage, $postIds);

        //id为0或空的是插入、id存在的为更新，传过来没有数据库里有的是删除

        if(!empty($deleteData)) {
            foreach ($deleteData as $k => $v) {
                $this->repoPages->delete($v);
            }
        }

        if(!empty($updateData)) {
            $ret = $this->repoPages->batchUpdate('saas_projects_page', $updateData);

        }

        if(!empty($insertData)) {
            $ret = $this->repoPages->batchInsert('saas_projects_page',$insertData);
        }
        if(empty($ret)){
            Helper::apiThrowException('60033',__FILE__.__LINE__);
        }

        return $arrStage;

    }

    /**
     * mongodb保存作品文件
     * @param $works_id 作品id
     * @param $content  舞台数据
     * @param int $type 1插入 2更新
     * @return boolean
     */
    public function saveDataByMongo($works_id , $content, $type =1)
    {
        $mongo = new Mongo();

        $new = array_map(function($v){
            return json_decode($v, true);
        },$content);


        $data = ['works_id' => $works_id, 'stage' => $new];
        if($type == 1) {
            $ret = $mongo->insert('prj_stage', $data);
        } else {
            $ret = $mongo->update('prj_stage',['stage' => $new],['works_id'=>$works_id] );
        }
        return $ret;
    }

    /**
     * 获取舞台数据
     * @param $works_id
     * @return array
     */
    public function getWorksStage($works_id)
    {

        //从mongodb取
        $mongo = new Mongo();
        $query =  new \MongoDB\Driver\Query(['works_id'=>$works_id],[]);
        $cursor = $mongo->manager->executeQuery(config('common.mongo.db').'.prj_stage', $query);
        $cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);

        $arrInfo = $cursor->toArray();
        $arr_stage = $arrInfo[0]['stage'];

        $stage = [];
        foreach ($arr_stage as $k=>$v) {
            $stage[$k] = json_encode($v);
        }
        return $stage;
    }


    /**
     * 保存操作日志（mongo）,子类实现
     * @return boolean
     */
    public function insertActLog($table,$data)
    {
        $mongo = new Mongo();
        $mongo->insert($table,$data);
        return true;
    }

    /**
     * 后置处理
     * @param $flag
     */
    public function afterSave($flag)
    {
        return ;
    }


}