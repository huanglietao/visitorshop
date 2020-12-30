<?php
namespace App\Repositories;
use App\Exceptions\CommonException;
use App\Models\SaasAreas;
use App\Services\Helper;

/**
 * 仓库模板
 * 仓库模板
 * @author: daiyd
 * @version: 1.0
 * @date: 2020/3/2
 */
class SaasAreasRepository extends BaseRepository
{

    public function __construct(SaasAreas $model)
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
        }else{
            $query =  $query->where($where)->where('level',1);
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
        if(empty($data['id'])) {
            unset($data['id']);
            $data['created_at'] = time();
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['id'];
            $data['updated_at'] = time();
            unset($data['id']);
            $ret =$this->model->where('area_id',$priKeyValue)->update($data);
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
     *  所属上级地区转换成地区名称
     *
     * @return array
     */
    public function getPidList()
    {
        $list =  $this->model->get();
        $pidList = [];
        foreach ($list as $k=>$v){
            $pidList[$v['area_id']] = $v['area_name'];
        }

        return $pidList;
    }
    /**
     *  获取地区的id并查找出pid
     *
     * @return array
     */

    public function getAreaIdList($id)
    {
        $pid =  $this->model->where('area_id',$id)->first();
        return $pid;
    }
    /**
     *  获取省市区地区的联动数组
     *
     * @return array
     */
    public function getAreasLists($id)
    {
        if($id != 0){
            $list = $this->model->where('pid',$id)->select('area_id', 'area_name')->get();
        }elseif ($id == -1){
            return '';
        }
        else{
            $list = $this->model->where('level',1)->select('area_id', 'area_name')->get();
        }

        return $list;
    }
    /**
     *  添加地址后对省市区的处理成相对应的pid
     *
     * @return array
     */
    public function logicData($data)
    {
        $newData = [];
        if($data['province']==0) {
            $newData['pid']= 100000;
            unset($data['province']);
            unset($data['city']);
            unset($data['district']);
        } elseif($data['city']==-1) {
            $newData['pid']= $data['province'];
            unset($data['province']);
            unset($data['city']);
            unset($data['district']);
        }else{
            $newData['pid']= $data['city'];
            unset($data['province']);
            unset($data['city']);
            unset($data['district']);
        }
        $data = $data+$newData;
        return $data;
    }

    /**
     * 省份名称转省份编码
     * @param $provinceName
     * @return mixed
     */
    public function provinceNameToCode($provinceName)
    {
        if (empty($provinceName)) {
            Helper::EasyThrowException('16001',__FILE__.__LINE__);
        }

        //如果是数字的情况,看是否是有效的编码
        if(is_numeric($provinceName)) {
            $info = $this->getByIdFromCache($provinceName);

            if (empty($info)) {  //查不到编码对应的记录
                Helper::EasyThrowException('16002',__FILE__.__LINE__);
            }

            return $provinceName;
        } else {
            $special_arr = ['内蒙古自治区','广西壮族自治区','西藏自治区','宁夏回族自治区','新疆维吾尔自治区','香港特别行政区','澳门特别行政区'];
            $spec_pro = [];


            //数组地址（省市区）
            if(strstr($provinceName, '省')) {
                $province = mb_substr($provinceName, 0, -1);
            }elseif (strstr($provinceName, '市')){
                $province = mb_substr($provinceName, 0, -1);
            }

            //自治区特别行政区的模糊匹配
            foreach ($special_arr as $k=>$v){
                if (strstr( $v , $provinceName ) !== false ){
                    array_push($spec_pro, $v);
                }
            };
            if (!empty($spec_pro)){
                $province = $spec_pro[0];
            }
        }

        if (empty ($province)) {
            $province = $provinceName;
        }

        //查询其对应的记录//可以考虑缓存
        $areaInfo = $this->model->where('area_name', 'like', $province.'%')->where('level', 1)->first();

        if (empty($areaInfo)) {  ////无效的省份
            Helper::EasyThrowException('16003',__FILE__.__LINE__);
        }

        return $areaInfo['area_id'];
    }

    /**
     * 城市名转城市编码
     * @param $cityName 城市名称
     */
    public function cityNameToCode($cityName)
    {
        preg_match('/((.*?)(市|自治州|地区|区划|县))/', $cityName, $matches);

        if (count($matches) > 1) {
            $city = $matches[count($matches) - 2];
        } else {
            $city = $cityName;
        }
        $areaInfo = $this->model->where('area_name', 'like', $city.'%')->where('level', 2)->first();

        if (empty($areaInfo)) {  ////无效的城市
            Helper::EasyThrowException('16004',__FILE__.__LINE__);
        }

        return $areaInfo['area_id'];
    }

    /**
     * 区/县名转编码
     * @param $districtName
     */
    public function districtNameToCode($districtName)
    {
        //先精确搜索一遍，有则直接返回，没有则做解析判断
        $areaInfo = $this->model->where('area_name',$districtName)->where('level', 3)->first();
        if (!empty($areaInfo)) {
            return $areaInfo['area_id'];
        }
        preg_match('/((.*?)(市|区|县|镇|乡|街道))/', $districtName, $matches);
        if (count($matches) > 1) {
            $district = $matches[count($matches) - 2];
        }
        if(!isset($district)){
            return '900009';//返回其他区代码
            //无效的区/县
            Helper::EasyThrowException('16005',__FILE__.__LINE__);
        }
        $areaInfo = $this->model->where('area_name', 'like', $district.'%')->where('level', 3)->first();
        if (empty($areaInfo)) {  ////无效的区/县
            return '900009';//返回其他区代码
            Helper::EasyThrowException('16005',__FILE__.__LINE__);
        }

        return $areaInfo['area_id'];

    }

    //根据等级获取地区的名称
    public function getAreaByLevel($level)
    {
        $provinces = $this->model->where(['level'=>$level])->get()->toArray();
        $helper = app(Helper::class);
        $provList = $helper->ListToKV('area_id','area_name',$provinces);
        return $provList;
    }

    //解析地址返回省市区code
    public function parseAddressToCode($address)
    {
        //$address 格式示范：浙江省 杭州市 西湖区
        $em_arr = [' ',' ',',','，'];
        foreach ($em_arr as $ke=>$ve){
            if (strstr( $address ,$ve ) !== false){
                $areaArr = explode($ve,$address);
                if (count($areaArr)==3){
                    $addressArr = ['p' => $areaArr[0], 'c' => $areaArr[1], 'a' =>$areaArr[2]];
                }else{
                    return [
                        'code' => 0,
                        'msg'  => "格式错误,无法解析"
                    ];
                }
            }
        }
        $error_msg = "";
        $areaCode = -1; //便于使用地区组件
        //转换区
        try{
            if (!empty($addressArr['a']))
            {
                //转换区
                $areaCode = $this->districtNameToCode($addressArr['a']);
            }
        }catch (CommonException $e){
            //区转换出错

        }
        $cCode = "";
        if (!empty($areaCode) && $areaCode!=-1){
            //获取市跟省级code,便于后面比较
            $cCode = $this->model->where('area_id',$areaCode)->value('pid');
        }

        $cityCode = -1;
        //转换市
        try{
            if (!empty($addressArr['c']))
            {
                //转换市
                $cityCode = $this->cityNameToCode($addressArr['c']);
            }
        }catch (CommonException $e){
            //市转换出错

        }
        $pCode = "";
        if (!empty($cityCode) || $cityCode!=-1){
            //比较是否区级与市级是否匹配
            if (!empty($areaCode) && $areaCode!=-1 && $cCode!=$cityCode)
            {
                $error_msg = "区级与市级不匹配,地址解析失败";
            }
            //获取省级code,便于后面比较
            $pCode = $this->model->where('area_id',$cityCode)->value('pid');
        }
        $provCode ="";
        //转换省
        try{
            if (!empty($addressArr['p']))
            {
                //转换市
                $provCode = $this->provinceNameToCode($addressArr['p']);
            }
        }catch (CommonException $e){
            //市转换出错
            $provCode = "";
        }

        if (!empty($provCode)){
            //比较是否区级与市级是否匹配
            if (!empty($cityCode)&& $cityCode!=-1 && $pCode!=$provCode)
            {
                $error_msg = "市级与省级不匹配,地址解析失败";
            }
        }
        $addressArr = ['p' => $provCode, 'c' => $cityCode, 'a' =>$areaCode];
        //判断是否出错
        if ($error_msg != ""){
            return [
                'code' => 2,
                'msg'  => $error_msg,
                'data' => $addressArr,
            ];
        }else{
            return [
                'code' => 1,
                'msg'  => 'ok',
                'data' => $addressArr,
            ];
        }
    }

}
