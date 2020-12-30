<?php
/**
 * 打单模块公共配置
 *
 * Created by PhpStorm.
 * Name: hlt
 * Date: 2020/06/10
 */

return [

    'sheetTemplates' => [
        'YTO' => 'http://cloudprint.cainiao.com/template/standard/290659/31',
        'SF' => 'http://cloudprint.cainiao.com/template/standard/1501/54',
        'YUNDA' => 'http://cloudprint.cainiao.com/template/standard/401/165',
        'STO'=>'http://cloudprint.cainiao.com/template/standard/288948/33',
        'EMS' => 'http://cloudprint.cainiao.com/template/standard/701/127',
        'EYB' => 'http://cloudprint.cainiao.com/template/standard/801/147',
        'HTKY' => 'http://cloudprint.cainiao.com/template/standard/501/147',
        'POSTB' => 'http://cloudprint.cainiao.com/template/standard/801/147',
    ],
    'customerTemplates' => 'http://cloudprint.cainiao.com/print/resource/getResource.json?resourceId=1502530&status=0',
    'default_sender' => [
        'mch_sender_person'   => '李先生',
        'mch_sender_phone'    => '02787101355',
        'mch_sender_address'  => '天津 天津市 北辰区 永兴道102号'
    ],
    //自定义打单类型
    'custom_print' => [
        'import_print'   =>  '导入订单打印',
        /*'custom_print'   =>  '自定义打印',*/
    ]

];