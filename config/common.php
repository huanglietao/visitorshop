<?php
/**
 * 系统公共配置
 *
 * 公共配置文件
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/7/30
 */

return [
    'js_version' => env("JS_VERSION"),
    'static_url' => env("STATIC_URL"),
    'page_limit' => 10,  //分页数
    'goods_default_thickness' => 10, //默认书脊厚度
    'goods_cate_no_spine'   => [     //无书脊的商品分类
        GOODS_DIY_CATEGORY_CALENDAR,
        GOODS_DIY_CATEGORY_SINGLE,
        GOODS_DIY_CATEGORY_CUP,
        GOODS_DIY_CATEGORY_STAGE
    ],
    'sys_name' => [
        'backend'   => '数据配置平台',
        'merchant'  => '商户管理平台',
        'agent'     => '分销平台',
        'api'       => '接口平台',
        'mobile'    => '移动端平台',
        'erp'       => '上账平台'
    ],
    'mch_super_username' => 'super_cr',
    'mch_super_password' => 'rTby7~!Tyls',
    'mongo'     => array(
        /*'host' => 'mongodb://'.env('MONGO_USER').':'.env('MONGO_PWD').'@'.env('MONGO_HOST').':'.env('MONGO_PORT').'/'.env('MONGO_DATABASE'),*/
        'host' => 'mongodb://'.env('MONGO_HOST').':'.env('MONGO_PORT').'/'.env('MONGO_DATABASE'),
        'db'   => env('MONGO_DATABASE')
    ),
    'image_path' => env("STATIC_PATH"),   //静态资资源文件
    //模块负责人信息
    'manager' => [
        'sys' => [     //系统配置及公共模块 异常code 以1开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'auth' => [     //权限、管理员账号   以2开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'member' => [  //会员相关   以3开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'goods' => [  //商品相关  以4开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '商品专员',
                'mobile'  => '15915774779',
                'email'   => '724434526@qq.com'
            ]
        ],
        'template' => [  //模板相关   以5开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'works' => [  //作品相关   以6开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'orders' => [  //订单相关   以7开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'logistics' => [  //物流相关    以8开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'payment' => [  //支付相关    以9开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'agent' => [  //分销商相关    以11开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'media' => [  //文章/广告是关    以12开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'supplier' => [  //供货商/工厂   以13开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'market' => [  //营销/优惠券   以14开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
        'statistics' => [  //统计     以15开头
            'dev' => [  //开发
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ],
            'bus' => [  //业务/运营
                'name'    => '严小山',
                'mobile'  => '15915774779',
                'email'   => '541139655@qq.com'
            ]
        ],
    ],
    //极验验证码插件
    'geettest' => [
        'captcha_id' => "48a6ebac4ebc6642d68c217fca33eb4d",
        'private_key' => "4f1c085290bec5afdc54df73535fc361"
    ],

    //上账支付宝配配置
    'alipay_agt'    => array(
        'partner'           => env('ALIPAY_PARTNER', ''),        //appid
        'seller_id'         => env('ALIPAY_SELLER_ID', ''),                      //appid
        /*'key'             => 'wx5xeu4wc1et446al316jmuorkel4lhr',      //支付宝安全校验码
        'seller_email'      => 'yiliyuan@art2print.cn',                  //卖家支付宝帐号*/
        'key'               => env('ALIPAY_KEY', ''),      //支付宝安全校验码
        'seller_email'      => env('ALIPAY_SELLER_EMAIL', ''),                 //卖家支付宝帐号
        'fin_notify_url'    => env('ALIPAY_FIN_NOTIFY_URL', ''),
        'fin_return_url'    => env('ALIPAY_FIN_RETURN_URL', ''),
        'sign_type'         => env('ALIPAY_SIGN_TYPE', ''),       //加密类型
        'input_charset'     => env('ALIPAY_INPUT_CHARSET', ''),      //编码类型
        'transport'         => env('ALIPAY_TRANSPORT', ''),     //访问模式
        'payment_type'      => env('ALIPAY_PAYMENT_TYPE', ''),
        'service'           => env('ALIPAY_SERVICE', ''),
        'cacert'            => getcwd().'\\cacert.pem',
        'exter_invoke_ip'   => '',          //客户端的IP地址      --非必须项
        'anti_phishing_key' => '',          //防钓鱼时间戳        --非必须项
        //   'request_type'      => 'post',      //请求方式
    ),

    //招行聚合支付相关配置
    'cmb'          => [
        'mch_name'     => env('CMB_MCH_NAME', ''),
        'mch_id'       => env('CMB_MCH_ID', ''),
        'user_id'      => env('CMB_USER_ID', ''),  //收银员
        'app_id'       => env('CMB_APP_ID', ''),
        'app_secret'   => env('CMB_APP_SECRET', ''),
        //商户私钥
        //'mch_pri_key'  => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCc540quYC9xzCMZeFOe8UmE3W5LWrqFd/2DDSHQASxq8vmOiwFRSG2hsVXtjfmNLQNhtpTR0SGDUjkCsx+SJH0JDnOfQ2xXHasO65Rnv2wrHs64P6U0aUrMWjgapjkmLwzRV12AKNAX77MGIocpcB0KZhk+0AVc6oQCBybV65JTGu+pAyFLMJRtIP5kH3VMuXmig6VeiZAsjEewD/emxgK3cXejMQvqlNYFnCLsZ7ovAhr+bhz6SHkOws3p80O6zfQbKfLzdSVaZK8FnwNPznUxAK77bRZN0zF3V9mL9+zrarvFPD5VkcVHNLj0DRzLmr2c5TbiCigs4+I+NMfhpoLAgMBAAECggEAbM8GzoImDXV87WAZhtu+NFF6ahhc9EiHL5H3O3PhzXRdyiK9NEpkvrdnUxRCX5pc4qSJ8waRNoUv7zSt60VYMf6NN+zw+fYtNfONR30CYOq76nDtGzbnW7TADiDeNmjU2plX3uVCUPoUzmSWIpevht7xl9XE8xtq7AM0E2YSrzEADcxtqQslM0uVOf+ki1eu0/OwCz13FzPlPtnDwt2Lw9xxCxWqTgpN4oD5m6EWTqbognUIJ0EFD0dHXjrYnHXc+/Za5e+CDXYApHuhR9bifa1e4HMN084oLY+rkSXUV3+Te0APPCfbeEeqvubziDmKxxKaWUq1wPbYi4c06ZQdgQKBgQDhF7zDWgiJFTgrLGmExJRKiR/3QZN4sugYE1itdRDJmiPV4xhWPXSsND3WtqR5+0otb/hbzRa3cyl/RXV/1ZmBbE46fX2DKnmLQ1gP74iOuqWpfxjh/qpk+3kEY9aP57le/O0QEEPsJmqCsGM7XnzfNsxGAFYaDHooRbcGtv++AwKBgQCycuvRUQjV4dxTuRJuwFbmdq4odSBMu7yCS4i5I9I73d3TGZBWfiXQWFmuiPh+pf3HdvMbgyA243Uv/NGapSmNvARXm0/eEyfTxV7+GVdwLf3sSe8DQMCR1eJA9VzuS+jhCrHkFgyW3V/3ki66W8YITENlgC+VebOatfFE8i/ZWQKBgQCZ2VmhxFX1LFW53J86qgoZb+QzYdTkOJQ+cGq6FDunL/2yYYfu2g527TYfHbMJ1OH8cH22cVVHiiUg4l7PQzWqqlZF0CQLlOqCb0MvkS8rLxOv6DkfrqrUXrV2dK7gqSegbwuxYQyryg4eyWTp3UlIX/H7Hpu7LjAIeq4Anu/p9QKBgFMtpiYHM6segGi2F5VwKhF6uGs7TTb3O0MwmiZSQCiPnlpLzC/E1TNsO0FTryC5lrVnCKKGWHm9RF595eXDnr7mKM/9IRlOrH3VvhWLEmrDxVxiifpmMFzJ6ZCFzi91SrO7HHhIns2jmpv3k7hiFsi/Y5roSUXPWJyAull82jjhAoGAaKujjF4HL91UXZFetkkKiBIpIrH5+XbiX9z7H9/Tv8NSy/zTvXp3hFl3dr9gO722i/96dTq4th23Gqtih4cA9x8Wd7RChR9yAK/ffSj1lW6RhBWj1j2JCPFCm1TJD5iO3bIeuHm2sAuafKKoWT/VCUkKRwt9Wwh9yF20vMQ3kFw=',
        'mch_pri_key'  => env('CMB_MCH_PRI_KEY', ''),
        //商户公钥
        //'mch_pub_key'  => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnOeNKrmAvccwjGXhTnvFJhN1uS1q6hXf9gw0h0AEsavL5josBUUhtobFV7Y35jS0DYbaU0dEhg1I5ArMfkiR9CQ5zn0NsVx2rDuuUZ79sKx7OuD+lNGlKzFo4GqY5Ji8M0VddgCjQF++zBiKHKXAdCmYZPtAFXOqEAgcm1euSUxrvqQMhSzCUbSD+ZB91TLl5ooOlXomQLIxHsA/3psYCt3F3ozEL6pTWBZwi7Ge6LwIa/m4c+kh5DsLN6fNDus30Gyny83UlWmSvBZ8DT851MQCu+20WTdMxd1fZi/fs62q7xTw+VZHFRzS49A0cy5q9nOU24gooLOPiPjTH4aaCwIDAQAB',
        'mch_pub_key'  => env('CMB_MCH_PUB_KEY', ''),
        //招行公钥
        //'cmb_pub_key'  => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjutZyxP2L9eYM6DhZ11jk5lZieyyA6Wsr4baAU7PT+E0fv3KlERoh0edHLsLVff2I4AzuEqSoKDywKIBw1aSkIXGAaESj/FzA/V1jtmorq1RpPFmaqAOGDocMiaqukBBemwFnsYrTegsZUf88fU7KujwEMffLhhpwnM/Vf0NJ2s3ZwEZCgPWDa5cm1YpMLgopzc5HozENI5K9VFL92ThjHiTiutE28Bpi2xgSt6Cx+S8Nxqhy6/r/YVxvfgP66YCccnWOObN3fWo5TXepP6uBReTwjqNajlcSC5JqINqUUEAqief87y3NAFKRbE7Bu312y6zqcJgC/TIrWLXXB1/XQIDAQAB',
        'cmb_pub_key'  => env('CMB_CMB_PUB_KEY', ''),
        //回调地址
        /*'cmb_notify'   => 'http://oms.mkyunyin.com/cmb/notify/',*/
        'cmb_notify'   => env('CMB_NOTIFY', ''),
    ],
    //分类数组
    'backend_category' => [
        'goods'    =>  "商品分类",
        'template' =>  "模板分类",
        'material' =>  "素材分类",
        'article'  =>  "文章分类",
        'background'  =>  "背景分类"
    ],
    'oss_key'  => '91MW47zAypL4QVUvX8oisfU2kQk0RQ',
    //同步订单快递模板id
    'syn_delivery_temp_id'=>4,
    //同步订单快递id
    'syn_delivery_id'=>3,
    //同步订单支付id（余额支付）
    'order_pay_id'=>3,

    //平台简称
    'sys_abbreviation'  =>  [
        'backend'       =>  'CMS', //商户管理平台简称
        'merchant'      =>  'OMS', //商户管理平台简称
        'agent'         =>  'DMS', //分销平台简称
        'factory'       =>  'SCM', //供货商平台简称
    ],

    'agent_code' =>[
        101=>[
            'flag'=>'kissBaby',
            'key'=>'UtUwrpu7Bbkm'
        ],
        301=>[
            'flag'=>'babyBus',
            'key'=>'UtUwrpu7Bbkm'
        ],
        208=>[
            'flag'=>'zt',
            'key'=>'87tuhrv8'
        ],
        20=>[
            'flag'=>'tmall',
            'key'=>''
        ],
        509=>[
            'flag'=>'tmall',
            'key'=>''
        ]
    ],

    //队列条数设置
    'queue_limit'   => [
        'print'                       =>  40, //冲印图片队列条数
        'message_sync'                =>  50,  //消息队列同步条数
        'ready_sync_queue'            =>  10,  //跑同步队列生成订单条数
        'error_sync_queue'            =>  50, //跑创建订单错误的队列重新生成订单条数
        'ready_push_erp_order_queue'  =>  50,  //推送erp订单队列条数
        'sync_order_images'           =>  10, //获取淘宝订单图片接口队列条数
        'delivery_queue'              =>  50,  //跑物流信息回写队列条数
        'create_works_pic_queue'      =>  2,  //跑生成作品的图片队列
        'outer_create_order_queue'    =>  2,  //跑外协创建订单队列
        'order_file_queue'            =>  10,  //跑订单发货表队列将订单归档
        'special_order_queue'         =>  100, //同步diy_assistant特殊订单归档队列条数
        'logistics_cost_queue'        =>  2000, //更新物流成本队列条数
    ],

    //商户后台联系电话配置
    'technology_mobile' => [
        'tech_mobile'   => '138-0138-0000',
        'prod_mobile'   => '138-0138-0000',
    ],
    //商户后台联系电话配置
    'default_sender' => [
    'mch_sender_person'   => '长荣健康—李先生',
    'mch_sender_phone'    => '13610500434',
],
     'syncStatus' =>[ //队列处理状态
         //'prepare' => '准备中',
         'ready' => '待处理',
         'progress' => '处理中',
         'finish' => '已处理',
         'error' => '已失败',
    ] ,

    //默认供货商id 长荣
    'default_sup_id'=>5,

    //微信支付所需的终端ip地址
    'spbill_create_ip'=>'47.92.37.227',

    //淘宝信息备注回写开关
    'is_update_tb_memo'=>env('IS_UPDATE_TB_MEMO'),

    //商户平台菜单配置
    'oms_rule_flag' => [
        'platform'  => "平台",
        'agent'     => "分销",
        'finance'   => "财务",
        'shift'     => '移动',
        'resources' => '资源',
    ],
    'oms_platform_menu' => false,
];
