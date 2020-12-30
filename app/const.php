<?php
/**
 * 常量定义
 *
 * 所有的常量都在这里定义
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/7/30
 */

//===========公共常量定义 start =====================//

const PUBLIC_YES              =   1;  // 是
const PUBLIC_NO               =   0;  // 否

const PUBLIC_DISABLE          =   0;  //禁用
const PUBLIC_ENABLE           =   1;  //启用
const PUBLIC_LOCK             =   3;  //锁定
const ZERO                    =   0;  //公共0
const ONE                     =   1;  //公共1
const NOT_DOWNLOADED          =   0; //未下载完成
const DOWNLOADED              =   1; //已下载完成

const PUBLIC_CMS_MCH_ID       =   0;  //CMS用户的商户id

const OPERATE_TYPE_USER       =   1;  //前台客户操作
const OPERATE_TYPE_ADMIN      =   2;  //后台管理员操作

const AREA_LEVEL_PROVINCE     =   1;  //省份等级
const AREA_LEVEL_CITY         =   2;  //城市等级
const AREA_LEVEL_DISTRICT     =   3;  //区/县等级

const CAINIAO_DEFAULT_USER_ID = 18; //菜鸟接口默认agent_id

const AIMEIYIN_DEFAULT_USER_ID = 18; //爱美印淘宝图片接口默认id

const MASK_CATEGORY = 65; //

const DEFAULT_RULE_FLAG = 'platform'; //

//===================== 公共常量定义 end ============//

//===========订单相关常量 start =====================//

//确认状态
const ORDER_UNCONFIRMED     =   0;  //未确认
const ORDER_CONFIRMED       =   1;  //已确认
const ORDER_CANCELED        =   2;  //已取消
const ORDER_INVALID         =   3;  //无效

//支付状态
const ORDER_UNPAY           =   0;  //未付款
const ORDER_PAYING          =   2;  //付款中
const ORDER_PAYED           =   1;  //已付款

//生产状态
const ORDER_NO_PRODUCE      =   0;  //未生产
const ORDER_PRODUCING       =   2;  //生产中
const ORDER_PRODUCED        =   1;  //生产完成

//配送状态
const ORDER_UNSHIPPED       =   0;   //未发货
const ORDER_SHIPPED         =   1;   //已发货
const ORDER_RECEIVED        =   2;   //已收货
const ORDER_PREPARING       =   3;   //备货中


//综合状态
const ORDER_STATUS_WAIT_CONFIRM    =    1;   //待确认
const ORDER_STATUS_WAIT_PAY        =    2;   //待付款  已确认
const ORDER_STATUS_WAIT_PRODUCE    =    3;   //待生产  已付款
const ORDER_STATUS_WAIT_DELIVERY   =    4;   //待发货  已生产
const ORDER_STATUS_WAIT_RECEIVE    =    5;   //待收货  已发货
const ORDER_STATUS_CANCEL          =    6;   //交易取消
const ORDER_STATUS_AFTERSALE       =    7;    //售后
const ORDER_STATUS_FINISH          =   10;   // 交易完成  已收货

//评价状态,评价状态不影响订单主状态。评价在交易完成后
const ORDER_UNEVALUATE             =    0;   //待评价
const ORDER_EVALUATED              =    1;   //已评价

//售后处理方式
const ORDER_AFTER_PREFERENTIAL     =    1; //协商优惠
const ORDER_AFTER_REFUND_MONEY     =    2; //仅退款
const ORDER_AFTER_REFUND_GOODS     =    3; //退款退货
const ORDER_AFTER_EXCHANGE         =    4; //换货
const ORDER_AFTER_OTHERS           =    5; //其它

//售后状态
const ORDER_AFTER_STATUS_UNPROCESSED        =       1; //未处理
const ORDER_AFTER_STATUS_PROCESSED          =       2; //已处理
const ORDER_AFTER_STATUS_FILE               =       3; //审核归档
const ORDER_AFTER_STATUS_WITHDRAW           =       4; //已撤回

//售后类型
const ORDER_AFTER_TYPE_REFUND            =   1; //仅退款
const ORDER_AFTER_TYPE_GOOD_REFUND       =   2; //退货退款

//售后物品状态
const ORDER_AFTER_GOOD_STATUS_NOT_RECEIVER   =   1; //未收到货
const ORDER_AFTER_GOOD_STATUS_RECEIVER       =   2; //已收到货

//发票信息
const INVOICE_TYPE_ELECTRONICS              =   1; //电子发票
const INVOICE_TYPE_PAPER                    =   2; //纸质发票
const INVOICE_INFO_DETAIL                   =   1; //明细
const INVOICE_INFO_CLASSIFICATION           =   2; //分类
const INVOICE_RISE_PERSON                   =   1; //个人
const INVOICE_RISE_COMPANY                  =   2; //企业

//订单日志相关
const ORDER_LOG_CREATE             =    1; //创建
const ORDER_LOG_PAY                =    2; //支付
const ORDER_LOG_EDIT               =    3; //修改

//生产相关
const ORDER_PRODUCE_TYPE_AUTO      =    1; //自动
const ORDER_PRODUCE_TYPE_HAND      =    2; //手动

//订单异常类型
const ORDER_EXCEPTION_TYPE_WORK         = 1; //作品异常
const ORDER_EXCEPTION_TYPE_SYNTHESIS    = 2; //合成异常
const ORDER_EXCEPTION_TYPE_PRODUCE      = 3; //生产异常

//集货状态
const NOT_BE_STOCKED                    = 0;
const BE_STOCKED                        = 1;

//退款单状态
const REFUND_STATUS_APPLY       = 0; //退款申请
const REFUND_STATUS_SUCCESS     = 1; //退款成功
const REFUND_STATUS_FAIL        = 2; //退款失败

//供货商订单状态
const SP_ORDER_STATUS_PRODUCE   = 1; //待生产
const SP_ORDER_STATUS_PRODUCING = 2; //生产中
const SP_ORDER_STATUS_PRINT     = 3; //已打码
const SP_ORDER_STATUS_DELIVERY  = 4; //已送货
const SP_ORDER_STATUS_SEND      = 5; //已发货

//同步队列的商品类型
const SYNC_QUEUE_DIY        = 1; //印品
const SYNC_QUEUE_ENTITY     = 2; //实物
const SYNC_QUEUE_MIX        = 3; //混合


//===========订单相关常量 end =====================//

//===========商品相关常量 start =====================//

//商品规格相关
const GOODS_SIZE_SINGLE_TRANSVERSE =    1;  //单页横款
const GOODS_SIZE_SINGLE_VERTICAL   =    2;  //单页竖款
const GOODS_SIZE_SINGLE_SQUARE     =    3;  //单页方款
const GOODS_SIZE_DOUBLE_TRANSVERSE =    4;  //跨页横款
const GOODS_SIZE_DOUBLE_VERTICAL   =    5;  //跨页竖款
const GOODS_SIZE_DOUBLE_SQUARE     =     6;  //跨页方款

//印品规格类型
const GOODS_SIZE_TYPE_COVER        =    1;  //封面
const GOODS_SIZE_TYPE_COVER_BACK   =    2;  //封面-封底
const GOODS_SIZE_TYPE_INNER        =    3;  //内页
const GOODS_SIZE_TYPE_BACK         =    4;  //封底
const GOODS_SIZE_TYPE_SPECIAL      =    5;  //特殊页

//规格单双页
const SINGLE_PAGE             =    0;  //单页
const DOUBLE_PAGE             =    1;  //双页

//规格是否跨页
const SINGLE_CROSS_PAGE             =    0;  //不跨页
const DOUBLE_CROSS_PAGE             =    1;  //跨页

//类目常量(一级类目)
const GOODS_MAIN_CATEGORY_PRINTER     =    "printer";  //印品
const GOODS_MAIN_CATEGORY_ENTITY      =    "entity";  //实物
const GOODS_MAIN_CATEGORY_VIRTUAL     =    "virtual";  //虚拟
const GOODS_MAIN_CATEGORY_TEMPLATE    =    "template";  //模板
const GOODS_MAIN_CATEGORY_FRAME       =    "frame";  //画框
const GOODS_MAIN_CATEGORY_DECORATE    =    "decorate";  //装饰
const GOODS_MAIN_CATEGORY_BACKGROUND  =    "background";  //背景
const GOODS_MAIN_CATEGORY_ANNOUNCE    =    "announce";  //公告
const GOODS_MAIN_CATEGORY_NEWS        =    "news";  //新闻
const GOODS_MAIN_CATEGORY_HELP        =    "help";  //帮助中心
const GOODS_MAIN_CATEGORY_NOTICE      =    "notice";  //通知

//类目常量(二级类目)
const GOODS_PRINTER_CATEGORY_DIY   =   "personal"; //个性印刷
const GOODS_PRINTER_CATEGORY_COM   =   "commercial"; //商务印刷


//类目常量(个印三级类目)
const GOODS_DIY_CATEGORY_ALBUM     =     "album";         //照片书/纪念册/
const GOODS_DIY_CATEGORY_CALENDAR  =     "calendar"; //台历/挂历....
const GOODS_DIY_CATEGORY_SINGLE    =     "single"  ;  //单张类,冲印类
const GOODS_DIY_CATEGORY_CUP       =     "cup"  ;     //杯子/马克杯
const GOODS_DIY_CATEGORY_STAGE     =     "stage"  ;   //摆台/插画/框画

const GOODS_COM_CATEGORY_CARD      =     "card";  //名片
const GOODS_COM_CATEGORY_FOLD      =     "fold";  //折页
const GOODS_COM_CATEGORY_POSTER    =    "poster";//海报


//商品媒体相关
const GOODS_MEDIA_PHOTOS           =     1;//图片
const GOODS_MEDIA_VIDEO            =     2;//视频

const GOODS_ATTR_PAGE_FLAG         =     'page';

//能否增减p
const ADD_PAGE                      =   '1';
const NO_ADD_PAGE                   =   '0';

//加减p标识
const PAGE_FLAG                     =   'page';

//p数属性id
const  PAGE_ID                      =   '6';

//是否启用库存
const PROD_STOCK_STATUS             =   '1';

//物流方式
const  FIXED_FEE                    =   '1';//固定收取物流费用
const  EXPRESS_TPL                  =   '2';//按快递模板

//商品类型
const SKU                           =    2;
const SPU                           =    1;

//套餐货号标识
const PACKAGE_SN                    = 'T';
//冲印商品货号标识
const SINGLE_SN                    = '-';
//多商品合并的特殊商品标识
const DOUBLE_SN                     ='_';

//商品上架状态
const PRODUCT_OFF    	=   0; //下架
const PRODUCT_ON    	=   1; //上架

//===========商品相关常量 end =====================//

//===========作品相关常量 start =====================//
const WORKS_FILE_TYPE_EMPTY        =     0; //无任何作品，如 实物   这种有特殊的推送流程
const WORKS_FILE_TYPE_DIY          =     1; //通过编辑器制作的作品  这种需要合成
const WORKS_FILE_TYPE_UPLOAD       =     2; //通过稿件上传或者提供下载url  这种需要下载

//作品处理状态
const WORKS_HANDEL_TYPE_UNPROCESSED     =       0;  //未处理,针对稿件(未合成,针对DIY)
const WORKS_HANDEL_TYPE_PROCESSING      =       1;  //处理中,针对稿件(合成中,针对DIY)
const WORKS_HANDEL_TYPE_PROCESSED       =       2;  //已处理,针对稿件(合成完成,针对DIY)

//作品稿件类型
const WORKS_MANUSCRIPT_TYPE_PDF               =       1;  //pdf
const WORKS_MANUSCRIPT_TYPE_ZIP               =       2;  //zip

const WORKS_DIY_STATUS_MAKING                 =       1; //制作中
const WORKS_DIY_STATUS_WAIT_CONFIRM           =       2; //待确认
const WORKS_DIY_STATUS_ORDER                  =       3; //已下单
const WORKS_DIY_STATUS_DELETE                 =      4; //回收站

//===========作品相关常量 end =====================//


//===========供货商相关常量 start =====================//
const SUPPLIER_TYPE_MAIN                =          1;//主力供货商
const SUPPLIER_TYPE_BACKUP              =          2;//备选供货商

//供货商队列下载状态
const SUPPLIER_QUEUE_STATUS_NOT_DOWNLOAD      =       0; //未下载
const SUPPLIER_QUEUE_STATUS_DOWNLOADED        =       1; //已下载

//供货商订单审核状态
const SUPPLIER_ORDER_EXAMINE_NOT_REVIEWED   =   0; //未审核
const SUPPLIER_ORDER_EXAMINE_REVIEWED       =   1; //已审核

//默认工厂id
const SUPPLIER_DEFAULT_ID                   =   5;//长荣

//===========供货商相关常量 end =====================//


//===========渠道/会员相关常量 start =====================//
//客户等级类型
const CHANEL_TERMINAL_AGENT        =    1; //面对分销(B客户，主要是分销系统)
const CHANEL_TERMINAL_USER         =    2; //面对一般会员(C客户,PC商城，手机商城、小程序....)

//渠道缩写
const AGENT_CHANNEL      = 'agent';    //分销
const OFFICAL_CHANNEL    = 'offical';  //官网
const DESKTOP_CHANNEL    = 'desktop';  //桌面软件
const WX_CHANNEL         = 'wx';       //微商城
const MINAPP_CHANNEL     = 'minapp';   //小程序

//分销店铺类型
const AGENT_SHOP_TYPE_AGT       = 1;    //分销
const AGENT_SHOP_TYPE_TM        = 2;    //天猫
const AGENT_SHOP_TYPE_TB        = 3;    //淘宝
const AGENT_SHOP_TYPE_JD        = 4;    //京东
const AGENT_SHOP_TYPE_ENTITY    = 5;    //实体店
const AGENT_SHOP_TYPE_WORK      = 6;    //合作商户
const AGENT_SHOP_TYPE_PRIVATE   = 7;    //自有商城

//===========渠道/会员相关常量 end =====================//


//===========分类管理相关常量 start =====================//

//分类状态
const CATEGORY_ENABLED             =    1; //启用
const CATEGORY_DISABLED            =    0; //禁用
//类目等级
const CATEGORY_NO_ONE              =    1; //第一级
const CATEGORY_NO_TWO              =    2; //第二级
const CATEGORY_NO_THREE            =    3; //第三级

//个性印刷所属id
const PERSONAL_PRINTING_ID         =    49;
const COMMERCIAL_PRINTING_ID         =    92;
//===========分类管理相关常量 end =====================//



//===========物流相关常量 start =====================//
const LOGISTICS_PRICE_BY_FIXED    =      1; //按商品固定收取物流费
const LOGISTICS_PRICE_BY_TEMP     =      2; //按物流模板收取物流费

//===========物流相关常量 end =====================//

//===========支付相关常量 start =====================//
const PAYMENT_FLAG_BALANCE        =      'balance'; //余额
const PAYMENT_FLAG_ALI            =      'alipay' ; //支付宝
const PAYMENT_FLAG_WX             =       'wxpay' ; //微信支付
const PAYMENT_FLAG_CMB_COM        =       'cmbjh'; //招行聚合支付
//===========支付相关常量 end =====================//

//===========财务/账务相关常量 start =====================//
const FINANCE_INCOME              =        1;  //收入
const FINANCE_EXPEND              =        2;  //支出

//资金变动类型
const FINANCE_CHANGE_TYPE_TRADE       =        1;  //交易
const FINANCE_CHANGE_TYPE_RECHARGE    =        2;  //充值
const FINANCE_CHANGE_TYPE_REFUND      =        3;  //退款
const FINANCE_CHANGE_TYPE_SERVICES    =        4;  //手续费
const FINANCE_CHANGE_TYPE_GIVE        =        5; //赠送
const FINANCE_CHANGE_TYPE_CASH        =        6; //提现
const FINANCE_CHANGE_TYPE_CHECK       =        7; //冲正
const FINANCE_CHANGE_TYPE_FROZEN      =        8; //冻结
//===========财务/账务相关常量 start =====================//


//===========模板相关常量 start =====================//
//模板页面类型
const TEMPLATE_PAGE_PAGE        = 1; //封面
const TEMPLATE_PAGE_INNER       = 2; //内页
const TEMPLATE_PAGE_MAIN        = 3; //主模板

//素材页面类型
const MATERIAL_TYPE_BACKGROUND      = 'background'; //背景
const MATERIAL_TYPE_DECORATE        = 'decorate'; //装饰
const MATERIAL_TYPE_FRAME           = 'frame'; //画框
const MATERIAL_TYPE_FONT            = 'font'; //文字
const MATERIAL_TYPE_SPECIAL         = 'special'; //特殊元素
const MATERIAL_TYPE_MSTYLE         = 'templet'; //模板样板

//审核状态
const TEMPLATE_STATUS_DOING            = 1; //制作中
const TEMPLATE_STATUS_VERIFYING        = 2; //待审核
const TEMPLATE_STATUS_VERIFYED         = 3; //已审核

//模板子页商品类型数量
const TEMPLATE_PAGES_ALBUM             = 1; //22p照片书
const TEMPLATE_PAGES_CALENDAR          = 2; //26p两年台挂历
const TEMPLATE_PAGES_SINGLE            = 3; //1p其他冲印
const TEMPLATE_PAGES_TWO_CALENDAR      = 4; //51p两年台挂历


//===========模板相关常量 end =====================//

//===========广告相关常量 start =====================//
//广告类型
const AD_TYPE_SINGEPIC          = 1; //单图
const AD_TYPE_CAROUSEL          = 2; //多图

//广告图片风格
const AD_IMAGE_STYLE_FOLLOW        = 1; //跟随容器
const AD_IMAGE_STYLE_FIXED         = 2; //固定宽度

//广告标识
const AD_FLAG_AGENT_BJ         = 'bj'; //特殊布局
const AD_FLAG_AGENT_ZS         = 'zs'; //分销专属服务
const AD_FLAG_AGENT_HZ         = 'hz'; //分销合作伙伴
const AD_FLAG_AGENT_SY         = 'sy'; //分销平台首页轮播
const AD_FLAG_AGENT_YS         = 'ys'; //分销我们的优势
const AD_FLAG_AGENT_WT         = 'wt'; //分销常见问题
const AD_FLAG_AGENT_DL         = 'dl'; //分销平台登录
const AD_FLAG_AGENT_GG         = 'gg'; //分销平台公告
const AD_FLAG_AGENT_GA         = 'ga'; //分销平台商品

//===========广告相关常量 end =====================//

//===========淘宝/天猫相关常量 start =====================//

const PIC_QUEUE_STATUS_WAITE        = 0; //待处理
const PIC_QUEUE_STATUS_DOWNLOAD     = 1; //图片已下载
const PIC_QUEUE_STATUS_WORK         = 2; //作品已生成
const PIC_QUEUE_STATUS_ORDER        = 3; //订单已生成

//===========淘宝/天猫相关常量 end =====================//

//===========Erp创建订单接口 start =====================//

const API_SIGN_KEY                      = 'odoo'; //erp接口签名key
const API_ERROR                         = '接口报错'; //erp接口签名key

//===========Erp创建订单接口 end =====================//

//===========异常信息发送 start =====================//

const ERROR_PHONE                       = '15915774779'; //发送异常信息的手机号
const ERROR_SIGN_NAME                   = '爱美印'; //发送异常信息用的签名
const ERROR_TEMPLATE_CODE               = 'SMS_198932754'; //发送异常信息用的模板

//===========异常信息发送 end =====================//