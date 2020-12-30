<?php

return [

//    'wechat' => [
//        // 公众号 APPID
//        'app_id' => '',
//        // 小程序 APPID
//        'miniapp_id' => '',
//        // APP 引用的 appid
//        'appid' => '',
//        // 微信支付分配的微信商户号
//        'mch_id' => '',
//        // 微信支付异步通知地址
//        'notify_url' => '',
//        // 微信支付签名秘钥
//        'key' => '',
//        // 客户端证书路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
//        'cert_client' => '',
//        // 客户端秘钥路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
//        'cert_key' => '',
//        // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
//        'log' => [
//            'file' => storage_path('logs/wechat.log'),
//            //     'level' => 'debug'
//        ],
//
//        // optional
//        // 'dev' 时为沙箱模式
//        // 'hk' 时为东南亚节点
//        // 'mode' => 'dev',
//    ],

    'wechat' => [
        'appid'       => env('WECAHT_APPID','wx67980372ab244c8f'), // APP APPID
        'app_id'      => env('WECHAT_APP_ID','wx67980372ab244c8f'), // 公众号 APPID
        'miniapp_id'  => env('WECHAT_MINIAPP_ID','wx67980372ab244c8f'), // 小程序 APPID
        'mch_id'      => env('WECHAT_MCH_ID','1492035112'), //支付商户ID
        'key'         => env('WECHAT_KEY','8934e7d55453e97507ef7d4cf7b05194'),
        'notify_url'  => env('WECHAT_NOTIFY_URL','http://dms.haoin.com.cn/finance/recharge/wxpaynotify'), //请勿修改此配置
//        'cert_client' => ADDON_PATH . '/epay/certs/apiclient_cert.pem', // optional, 退款，红包等情况时需要用到
//        'cert_key'    => ADDON_PATH . '/epay/certs/apiclient_key.pem',// optional, 退款，红包等情况时需要用到
//        'log'         => [
//            // optional
//            'file'  => LOG_PATH . '/epaylogs/wechat' . date("Y-m-d") . '.log',
//            'level' => 'debug'
//        ],
    ]
];