<?php
namespace App\Repositories;

use App\Models\Areas;

/**
 *
 * 提供地区表的模型数据
 * @author: cjx
 * @version: 1.0
 * @date: 2019/8/21
 */
class AreasRepository extends BaseRepository
{
    protected $areas;

    public function __construct(Areas $areas)
    {
        $this->areas =$areas;
    }

    public function getAreasList($id)
    {
        if($id != 0){
            $list = $this->areas->where('pid',$id)->select('id', 'name')->get();
        }elseif ($id == -1){
            return '';
        }
        else{
            $list = $this->areas->where('level',1)->select('id', 'name')->get();
        }

        return $list;
    }


    /**
     * @param string $address 详细地址
     * @return array
     */
    public function parseDetailAddress($address)
    {
        $provinceId = '';
        $cityId = '';
        $areaId = '';

        //地址3空格解析（省 市 区 详细地址）
        //例:辽宁省 辽阳市 灯塔市 佟二堡镇香港工业园6巷4号门卫
        //中英文空格,中英文逗号判断
        $em_arr = [' ',' ',',','，'];
        foreach ($em_arr as $ke=>$ve){
            if (strstr( $address ,$ve ) !== false){
                $areaArr = explode($ve,$address);
                if (count($areaArr)==4){
                    return ['p' => $areaArr[0], 'c' => $areaArr[1], 'a' =>$areaArr[2], 'd' =>$areaArr[3]];
                }

            }
        }




        $bra_address = "";
        $brackets_arr = ['(','（'];
        foreach ($brackets_arr as $kk=>$vv){

            if (strstr( $address ,$vv ) !== false){
                $bra_address = substr($address,strripos($address,$vv));
                $address = str_replace($bra_address, '', $address);
                break;
            }
        }


        //特殊省份
        $specProvinceList = ['香港特别行政区', '香港', '澳门特别行政区', "澳门",
            "上海市", "上海", "重庆市", "重庆", "北京市","北京","天津市","天津"
        ];

        $province = '';
        $city = '';

        foreach ($specProvinceList as $k=>$v){
            if (strstr( $address ,$v ) !== false){
                $str_len = strlen($v);
                $address = substr($address,strripos($address,$v)+$str_len);
                if ($v=="重庆市"||$v=="北京市"||$v=="天津市"||$v=="上海市"){
                    //省级有市时将市去掉
                    $province = rtrim($v,"市");
                }else {
                    $province = $v;
                }
                break;
            }
        }


        if (empty($province)){
            preg_match('/(.*?(省|自治区))/', $address, $matches);

            if (count($matches) > 1) {
                $province = $matches[count($matches) - 2];
                $address = str_replace($province, '', $address);
            }
        }






        //当省级地区为天津，上海，重庆，北京时,上面的判断已经将地址截取到了区级地址字符串
        //为了避免区级的地址字符串出现“市|自治州|地区|区划|县”等字眼干扰到市级判断
        //所以当省级地区为天津，上海，重庆，北京时,这里不做市级判断
        if ($province!="重庆"&&$province!="北京"&&$province!="天津"&&$province!="上海")
        {
            preg_match('/(.*?(市|自治州|地区|区划|县))/', $address, $matches);

            if (count($matches) > 1) {
                $city = $matches[count($matches) - 2];
                $address = str_replace($city, '', $address);
            }

        }


        preg_match('/(.*?(区|县|镇|乡|街道|市))/', $address, $matches);
        if (count($matches) > 1) {
            $area = $matches[count($matches) - 2];
            $address = str_replace($area, '', $address);
        }



        $city = (isset($city)&&$city!="") ? $city : $province."市";


        if(empty($province) || empty($area)) {
            return false;
        }

        return ['p' => $province, 'c' => $city, 'a' =>$area, 'd' =>  $address.$bra_address];
    }


}
