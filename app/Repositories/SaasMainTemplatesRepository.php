<?php
namespace App\Repositories;
use App\Models\SaasCategory;
use App\Models\SaasMainTemplates;
use App\Models\SaasMainTemplatesPages;
use App\Models\SaasTemplatesAttachment;
use App\Services\Template\Attachment;
use App\Services\Template\BlankMain;
use App\Services\Template\Main;
use App\Services\Helper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * 仓库模板
 * 仓库模板
 * @author: dai
 * @version: 1.0
 * @date: 2020/4/15
 */
class SaasMainTemplatesRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasMainTemplates $model,SaasMainTemplatesPages $mpagesModel,SaasTemplatesAttachmentRepository $attachModel,SaasCategoryRepository $cateRepositories)
    {
        $this->model =$model;
        $this->mainPagesModel = $mpagesModel;
        $this->tempAttachModel = $attachModel;
        $this->cateRepo = $cateRepositories;
    }

    /**
     * @param null $where
     * @param null $order
     * @param null $setlimit (传条数)
     * @return mixed
     */
    public function getTableList($where=null, $order=null,$setlimit=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        if($setlimit){
            $limit = $setlimit;
        }
        $where = $this->parseWhere($where);

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model->with('mainPages');
        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }
        if(isset($where['mch_id'])){
            $query = $query->whereIn('mch_id',$where['mch_id']);
            unset($where['mch_id']);
        }

        //模板标签
        if(isset($where['temp_tag'])){
            $query = $query->whereRaw('FIND_IN_SET('.$where['temp_tag'].',temp_tag)');
            unset($where['temp_tag']);
        }

        //模板名称
        if(isset($where['main_temp_name']) && !empty($where['main_temp_name'])){
            $query = $query->where('main_temp_name', 'like', '%'.$where['main_temp_name'].'%');
            unset($where['main_temp_name']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);

        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {

        //根据商品分类id查询该对应标识分类
        $goodsCate = SaasCategory::where(['cate_id'=>$data['goods_type_id']])->select('cate_flag')->first();
        if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_CALENDAR && $data['main_temp_start_year']==''){
            return ['code'=>ZERO,'msg'=>'请选择起始年份'];
        }
        //转化数据库不存在的字段
        if(isset($data['main_background']) || isset($data['main_decorate']) || isset($data['main_frame'])){
            $attach['main_background'] = $data['main_background'];
            $attach['main_decorate'] = $data['main_decorate'];
            $attach['main_frame'] = $data['main_frame'];
            $attach['main_templet'] = $data['main_templet'];
        }
        unset($data['main_background'],$data['main_decorate'],$data['main_frame'],$data['main_templet']);

        if(empty($data['id'])) {
            unset($data['id']);
            $data['created_at']= time();
            //模板关联标签
            if(isset($data['temp_tag'])){
                $data['temp_tag'] = implode(",",$data['temp_tag']);
            }

            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;

            //添加时插入模板子页数据
            $mainLogic = new Main(); //封面内页全为空时
            $logicRet = $mainLogic->addChild($priKeyValue, $data['cover_temp_id'],$data['inner_temp_id'],$data['specifications_id']);

            if(empty($logicRet)) {
                if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_ALBUM) {
                    $logicMain = new Main();
                    //添加主模板库时自动添加照片书子页
                    $logicMain->addMainPhotoBookPages($data,$priKeyValue);
                } else if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_CALENDAR){
                    $logicMain = new Main();
                    //在添加主模板库时自动生成台历子页
                    $logicMain->addMainCalendarPages($data, $priKeyValue);
                }else{
                    $logicMain = new Main();
                    //添加主模板库时自动添加其他商品类型子页
                    $logicMain->addMainOtherPages($data, $priKeyValue,$data['cover_temp_id'],$data['inner_temp_id']);
                }

            }

        } else {
            $pagesCount = $this->mainPagesModel->where(['main_temp_page_tid'=>$data['id']])->count();

            $priKeyValue = $data['id'];
            $id = $data['id'];
            unset($data['id']);

            //编辑时子页数为空时从新生成子页数据
            if(empty($pagesCount)){
                $mainLogic = new Main();  //封面内页全为空时
                $logicRet = $mainLogic->addChild($priKeyValue, $data['cover_temp_id'],$data['inner_temp_id'],$data['specifications_id']);

                if(empty($logicRet)) {

                    if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_ALBUM) {
                        $logicMain = new Main();
                        //添加主模板库时自动添加照片书子页
                        $logicMain->addMainPhotoBookPages($data,$priKeyValue);
                    } else if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_CALENDAR){
                        $logicMain = new Main();
                        //在添加主模板库时自动生成台历子页
                        $logicMain->addMainCalendarPages($data, $priKeyValue);
                    }else {
                        $logicMain = new Main();
                        //添加主模板库时自动添加其他商品类型子页
                        $logicMain->addMainOtherPages($data, $priKeyValue,$data['cover_temp_id'],$data['inner_temp_id']);
                    }
                }
            }

            //模板关联标签
            if(isset($data['temp_tag'])){
                $data['temp_tag'] = implode(",",$data['temp_tag']);
                //获取名称查询其他同名的模板是否含一样的标签
                $tempList = $this->model
                    ->where(['main_temp_name'=>$data['main_temp_name']])
                    ->where(['mch_id'=>ZERO,'main_temp_status'=>ONE,'main_temp_check_status'=>TEMPLATE_STATUS_VERIFYED])->get()->toArray();
                if($tempList){
                    foreach ($tempList as $k=>$v){
                        if($v['temp_tag']!=$data['temp_tag']){
                            $parm['temp_tag'] =$data['temp_tag'];
                            $this->model->where('main_temp_id',$v['main_temp_id'])->update($parm);
                        }
                    }
                }
            }else{
                $data['temp_tag'] = '';
            }

            $data['updated_at']= time();
            $ret =$this->model->where('main_temp_id',$priKeyValue)->update($data);

        }

        //处理图片逻辑
        $attachLogic = new Attachment();

        if(empty($attach['main_background']) && empty($attach['main_decorate']) && empty($attach['main_frame']) && empty($attach['main_templet'])) {
            $attaIds = [];
        } else{
            //操作相关的素材
            $attachment = $attach['main_background'].','.$attach['main_decorate'].','.$attach['main_frame'].','.$attach['main_templet'];
            $arrAtta = explode(',', trim($attachment, ','));

            $attaIds = [];
            foreach ($arrAtta as $k=>$v) {
                if(!empty($v) && $k%2==0) {
                    $attaIds[] = $v;
                }
            }
        }

        //处理模板与附件的关联关系
        $attachLogic->relationTemplate($priKeyValue , $attaIds);

        //处理图片删除,删除不作事务，不影响其他操作
        if(isset($id)) {
            $attachLogic->removeRelation($priKeyValue, $attaIds, TEMPLATE_PAGE_MAIN);
        }


        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['main_temp_id'] = $priKeyValue;
             //将数据写入缓存
             $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
         }
        return ['code'=>$ret,'msg'=>'操作成功'];

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

        //删除缓存数据
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['main_temp_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     *  改变字段vip和前端显示
     * @param $flag
     * @return bool
     */
    public function changeUpdateField($flag)
    {
        if($flag['flag'] == 'main_temp_is_vip=0') {
            $ret = $this->model->where('main_temp_id',$flag['id'])->update(['main_temp_is_vip'=>1]);
            return ['flag'=>1];
        }
        if($flag['flag'] == 'main_temp_is_vip=1') {
            $ret = $this->model->where('main_temp_id',$flag['id'])->update(['main_temp_is_vip'=>0]);
            return ['flag'=>0];
        }
        if($flag['flag'] == 'main_temp_is_ads_display=0') {
            $ret = $this->model->where('main_temp_id',$flag['id'])->update(['main_temp_is_ads_display'=>1]);
            return ['flag'=>1];
        }
        if($flag['flag'] == 'main_temp_is_ads_display=1') {
            $ret = $this->model->where('main_temp_id',$flag['id'])->update(['main_temp_is_ads_display'=>0]);
            return ['flag'=>0];
        }


    }

    /**
     *  改变审核状态
     * @param $data
     * @return bool
     */
    public function changeCheckStatus($data)
    {
        if(!empty($data)) {
            $this->model->where('main_temp_id',$data['id'])->update(['main_temp_check_status'=>$data['status']]);
            return true;
        }else{
            return false;
        }

    }

    /**
     *  获取同名称分类下的主模板数据使用次数
     * @param $data
     * @return array
     * 2020/7/9  修改成获取模板使用量
     */
    public function getSameTempTimes($where=null, $order=null,$setlimit=false)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        if($setlimit == 'true'){
            $limit = 9999;
        }
        $where = $this->parseWhere($where);
        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        /*$answers = Answer::whereIn('user_id', $classmateIdArr)
            ->where('start_time', '>', $lastYear)
            ->groupBy('user_id')
            ->selectRaw('user_id')
            ->selectRaw('sum(unknown) as unknownCount')
            ->selectRaw('sum(important) as importantCount')
            ->selectRaw('sum(important+unknown) as allCount')
            ->having('allCount', '>', '0')
            ->orderBy('allCount', 'desc')
            ->distinct()
            ->take(10)->get();*/
        $query = $this->model;
        //模板名称
        if(isset($where['main_temp_name']) && !empty($where['main_temp_name'])){
            $query = $query->where('main_temp_name', 'like', '%'.$where['main_temp_name'].'%');
            unset($where['main_temp_name']);
        }
        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        $query =  $query->groupBy('main_temp_name','main_temp_theme_id')
            ->selectRaw('main_temp_theme_id,main_temp_name,sum(main_temp_use_times) as timesCount')
            ->orderBy('timesCount', 'desc');
        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);
        return $list;

    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getMobileTemplateTableList($where=null, $order=null)
    {

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
        if(isset($where['mch_id'])){
            $query = $query->whereIn('mch_id',$where['mch_id']);
            unset($where['mch_id']);
        }
        if(!empty ($where)) {
            $query =  $query->where($where);
        }
        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }
        $list = $query->get();
        return $list;
    }


    /**
     * 获取同一模板主题下的模板的数量
     */
    public function getCountThemeid($mch_id,$goods_type_id,$theme_id,$prod_size_id)
    {
        $temp = $this->model->whereIn('mch_id',$mch_id)->where(['goods_type_id'=>$goods_type_id,'main_temp_theme_id'=>$theme_id,'specifications_id'=>$prod_size_id,'main_temp_check_status'=>TEMPLATE_STATUS_VERIFYED])->get();
        $temp_count =count(json_decode($temp,true));
        return $temp_count;

    }


    public function getTempID($good_type_id)
    {
        $temp = $this->model->where(['goods_type_id'=>$good_type_id,'main_temp_check_status'=>TEMPLATE_STATUS_VERIFYED])
                    ->orderBy("main_temp_sort","desc")->select('main_temp_id')->first();
        return $temp['main_temp_id'];
    }

    /**
     * 临时保存模板
     * @param $data
     * @return boolean
     */
    public function saveBlankTemp($data)
    {

        //根据商品分类id查询该对应标识分类
        $goodsCate = SaasCategory::where(['cate_id'=>$data['goods_type_id']])->select('cate_flag')->first();
        if(empty($goodsCate)){
            return ['code'=>0,'msg'=>'没有此规格商品'];
        }

        $data['created_at']= time();
        if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_CALENDAR) { //台历加入默认当年年份
            $data['main_temp_start_year'] = date("Y");
        }
        $priKeyValue = $this->model->insertGetId($data);

        //添加时插入模板子页数据
        $mainLogic = app(BlankMain::class);
        //先查询子页数据是否存在
        $pagesCount = $this->mainPagesModel->where(['main_temp_page_tid'=>$priKeyValue])->count();

        if(empty($pagesCount)) {
            if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_ALBUM) {
                //添加主模板库时自动添加照片书子页
                $mainLogic->addBanlkMainPhotoBookPages($data,$priKeyValue);
            } else if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_CALENDAR){
                //在添加主模板库时自动生成台历子页
                $data['main_temp_start_year'] = date("Y");
                $mainLogic->addBanlkMainCalendarPages($data, $priKeyValue);
            }else if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_SINGLE || $goodsCate->cate_flag == GOODS_DIY_CATEGORY_CUP || $goodsCate->cate_flag == GOODS_DIY_CATEGORY_STAGE){
                //添加主模板库时自动添加其他商品类型子页
                $mainLogic->addMainOtherPages($data, $priKeyValue,$data['cover_temp_id'],$data['inner_temp_id']);
            }

        }

        return $priKeyValue;

    }

    /**
     * 导出处理
     * @param $param 模板名称
     */
    public function export($param)
    {

        //获取数据
        $list = $this->getSameTempTimes($param,null,true)->toArray();
        $result = $list['data'];

        if(empty($result)){
            echo '暂无记录';
            die;
        }
        $tempThemeList = $this->cateRepo->getTList(GOODS_MAIN_CATEGORY_TEMPLATE);
        $tempThemeList = Helper::ListToKV('cate_id','cate_name',$tempThemeList);
        $data = [];$orderStatus = config('order.supplier_order_status');
        foreach ($result as $k=>$v){
            $data[$k]['name'] = $v['main_temp_name'];
            $data[$k]['theme_id'] = isset($tempThemeList[$v['main_temp_theme_id']]) ? $tempThemeList[$v['main_temp_theme_id']]:'无';
            $data[$k]['times'] = $v['timesCount'];
        }

        $spreadsheet= new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //设置sheet的名字  两种方法
        $spreadsheet->getActiveSheet()->setTitle('模板使用量报表导出');

        //设置自动列宽
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setWidth(50);


        //设置第一行小标题
        $k = 1;
        $sheet->setCellValue('A'.$k, '模板名称');
        $sheet->setCellValue('B'.$k, '模板分类');
        $sheet->setCellValue('C'.$k, '使用次数');

        $k = 2;
        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . $k, $value['name']);
            $sheet->setCellValue('B' . $k, $value['theme_id']);
            $sheet->setCellValue('C' . $k, $value['times']);
            $k++;
        }

        $file_name = '模板使用量报表导出'.date('Y-m-d H:i:s',time());
        $file_name = $file_name . ".xlsx";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file_name.'"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }
    //获取模板使用次数排行
    public function getPopularTemplate($mid=null,$limit=10)
    {
        $query = $this->model;
        if (!is_null($mid) && !is_array($mid))
        {
            $midArr = explode(',',$mid);
            $query = $query->whereIn('mch_id',$midArr);
        }
        $query= $query->select(\DB::raw('SUM(main_temp_use_times) as use_times, main_temp_name'))->groupBy('main_temp_name')->orderBy('use_times','desc')->limit($limit)->get()->toArray();
        return $query;
    }






}
