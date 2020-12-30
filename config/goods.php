<?php
/**
 * 商品功能配置项
 *
 * 商品功能相关的购物流程配置项
 * @author:
 * @version: 1.0
 * @date: 2020
 */

return [
    'is_turn' =>[
        SINGLE_PAGE  => '单面',
        DOUBLE_PAGE  => '双面',
    ] ,
    'y_n' =>[
        PUBLIC_NO  => '否',
        PUBLIC_YES => '是',
    ] ,
    'is_cross' => [ //是否跨页
        SINGLE_CROSS_PAGE  => '不跨页',
        DOUBLE_CROSS_PAGE  => '跨页',
    ],
    'check_status' =>[ //模板审核状态
        TEMPLATE_STATUS_DOING => '制作中',
        TEMPLATE_STATUS_VERIFYING => '待审核',
        TEMPLATE_STATUS_VERIFYED => '已审核',
    ] ,
    'goods_type'          => [  //默认商品子页数量
        TEMPLATE_PAGES_ALBUM         => '22', //照片书
        TEMPLATE_PAGES_CALENDAR      => '26', //26p两年台挂历
        TEMPLATE_PAGES_SINGLE        => '1', //其他冲印
        TEMPLATE_PAGES_TWO_CALENDAR  => '51', //51p两年台挂历
    ],
    'page_type'     => [  //规格子页类型
        GOODS_SIZE_TYPE_COVER => '封面',
        GOODS_SIZE_TYPE_COVER_BACK => '封面/封底',
        GOODS_SIZE_TYPE_INNER => '内页',
        GOODS_SIZE_TYPE_BACK => '封底',
        GOODS_SIZE_TYPE_SPECIAL => '特殊页'
    ],
    'size_type'        => [  //规格标签
        GOODS_SIZE_SINGLE_TRANSVERSE  => '单页横款',
        GOODS_SIZE_SINGLE_VERTICAL    => '单页竖款',
        GOODS_SIZE_SINGLE_SQUARE      => '单页方款',
        GOODS_SIZE_DOUBLE_TRANSVERSE  => '对裱横款',
        GOODS_SIZE_DOUBLE_VERTICAL    => '对裱竖款',
        GOODS_SIZE_DOUBLE_SQUARE      => '对裱方款',
    ],
    'material_flag'        => [  //素材分类
        MATERIAL_TYPE_BACKGROUND      => '背景',
        MATERIAL_TYPE_DECORATE        => '装饰',
        MATERIAL_TYPE_FRAME           => '画框',
        MATERIAL_TYPE_FONT            => '文字',
        MATERIAL_TYPE_SPECIAL         => '特殊元素',
        MATERIAL_TYPE_MSTYLE          => '模板样板',
    ],




    'attr_type' =>[   //属性展示类型
        1 => '文字',
        //2 => '图片',
    ],
    'big_type' => [
        1 => '印品',
        2 => '实物',
        3 => '虚拟',
    ],

    'is_up' =>[
       0 => '下架',
       1 => '上架',
    ] ,
    // 为支付配置设置的勿删除
    'paytype' => [
        1 => '线上',
        2 => '线下',
    ],

    'flash_cate_id' => [   //对应商品的分类id
        1   =>  ['flag' =>'zps','name'=>'照片书'],
        2   =>  ['flag' =>'tgl','name'=>'台挂历'],
        3   =>  ['flag' =>'kp','name'=>'卡片'],
        4   =>  ['flag' =>'sjk','name'=>'手机壳'],
        5   =>  ['flag' =>'fbd','name'=>'帆布袋'],
        6   =>  ['flag' =>'tx','name'=>'T恤'],
    ],
    //默认供货商配置
    'default_suppliers' => [
        0 => '默认供货商',
    ],

    //所属终端默认分销、桌面端配置
    'default_subsystem' => [
        0 => ['id' => 0, 'name' => '桌面软件'],
        1 => ['id' => 1, 'name' => '分销'],
    ],
    'desktop' => 0, //桌面软件id，若此处修改，default_subsystem也得对应修改

    'distribute' => 1,//分销id,若此处修改，default_subsystem也得对应修改

    //规格标签
    'specifications_flag' => [
        1 => '8寸',
        2 => '10寸',
        3 => '12寸',
        4 => '18寸',
    ],

    //主题标签
    'theme_flag' => [
        1 => '幼儿园毕业册',
        2 => '中小学毕业册',
        3 => '大学毕业册',
        4 => '影楼册',
    ],

    //工艺标签
    'technology_flag' => [
        1 => '圈装',
        2 => '对裱',
        3 => '锁线精装',
        4 => '胶装',
    ],
    //退货标识数组
    'return_goods' => [
        '1'  => '维修',
        '2'  => '退货',
        '3'  => '换货',
        '4'  => '仅退款',
    ],
    //售后服务数组
    'after_sale' => [
        '1'  => '提供发票',
        '2'  => '保修服务',
        '3'  => '退换货承诺',
        '4'  => '七天无理由退款',
    ],
    //商品标签数组
    'goods_label' => [
        '1'  => '商品好看',
        '2'  => '优质材料',
        '3'  => '性价比高',
        '4'  => '格调高'
    ],
    //供货商配置
    'sup_region' => [
        '1'=>'华东',
        '2'=>'华西',
        '3'=>'华南',
        '4'=>'华北',
        '5'=>'华中'
    ],
    //商品进入模板市场时为冲印或者摆台&插画&框画
    'special_ids'=>[
        'single' => 52,//冲印id
        'stage'  => 54 //摆台&插画&框画id
    ],
    'add_one_cate' => [52]
];