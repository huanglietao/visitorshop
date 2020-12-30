<?php
namespace App\Http\Controllers\Agent;

use App\Exceptions\CommonException;
use App\Http\Requests\Agent\DemoRequest;
use App\Jobs\Notice;
use App\Jobs\Taobao\SyncMessage;
use App\Repositories\AgentRepository;
use App\Services\Factory;
use App\Services\Orders\OrdersEntity;

use App\Services\SyncData;
use App\Services\Works\TbOuter;
use Event;
use Illuminate\Http\Request;

/**
 * 开发demo
 *
 * 以分销账号表为例的demo,实现列表、添加、修改、删除及各组件
 * 给合使用
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/5
 */
class DemoController extends BaseController
{
    protected $viewPath = 'agent.demo';  //当前控制器所的view所在的目录
    protected $modules = 'goods';        //当前控制器所属模块
    protected $servicesOrder;
    public function __construct(AgentRepository $Repository,OrdersEntity $serOrder)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->servicesOrder = $serOrder;
    }

    public function index()
    {

        $imageList = [
            [
                'image_id'   => 1,
                'origwidth'  => 928,
                'origheight' => 2000,
                'big_img'   => 'http://img2.meiin.com/m02/y20/m0615/925366/5ee6ca035dfa8.jpg?x-oss-process=image/auto-orient,1/rotate,270',
                'print_nums' => 1,
            ],
            [
                'image_id'   => 2,
                'origwidth'  => 928,
                'origheight' => 2000,
                'big_img'   => 'http://img2.meiin.com/m02/y20/m0615/925366/5ee6ca01f31a2.jpg?x-oss-process=image/auto-orient,1/rotate,270',
                'print_nums' => 1,
            ],
            [
                'image_id'   => 3,
                'origwidth'  => 928,
                'origheight' => 2000,
                'big_img'   => 'http://img2.meiin.com/m02/y20/m0615/925366/5ee6ca0044b38.jpg?x-oss-process=image/auto-orient,1/rotate,270',
                'print_nums' => 2,
            ],
            [
                'image_id'   => 4,
                'origwidth'  => 928,
                'origheight' => 2000,
                'big_img'   => 'http://img2.meiin.com/m02/y20/m0615/925366/5ee6c9ff19761.jpg?x-oss-process=image/auto-orient,1',
                'print_nums' => 1,
            ],

        ];
        $orderNo = '123123123';
        $skuId  = '482';
        $agentId  = 18;
        app(TbOuter::class)->createWorks($imageList,$orderNo,$skuId, $agentId);

        exit;

        $info = [
            'erp_name' => '广州电商',
            'order_no' => '1232331223',
            'factory_code' => 'A5HSP内157G20封相250G',
            'project_sn'   => '1232331223-3-1',
            'goods_num'    => 3,
            'quantity'     =>1,
            'page_count'   => 100
        ];
        $res = app(Factory::class)->generateFileName(223,$info,1);
        var_dump($res);exit;
        exit;

        $ret = Event::fire(new SyncMessage([]));

        $post_data = [
            'items'  => [
                [
                    'goods_id'   => 43,  //商品id  必须
                    'product_id' => 49,  //skuid  必须
                    'works_id'   => 1,  // 有作品可为0 ，0表示无作品(实物或需下载的) 作品包括diy和稿件形式 必须
                    'file_type'  => 1,  // 1,diy文件 2,稿件url 0,无  可为0 ，0表示无文件  必须
                    'file_info'  => [   //文件信息 works_id为0并且无文件表示实物，works_id为0但有文件信息表示文件信息需要下载或转移
                        'file_url' => 'http://xxxxx/xxx.pdf||http://xxxxx/xxx.pdf',  //封面||内页||封底这样排
                        'pages_num'  => 20  //冲印张数或照片书内页数

                    ],
                    'price_mod'  => 1,   //1正常按本/个计价 2按张数计价
                    'buy_num'    => 1,  //购买数量 必须
                    //'real_fee'  => 15.3, // 价格  商品单价*数量
                    'part_mjz_discount' =>  3,// 平摊的优惠金额,如果单独使用则表示使用优惠券的金额 非必须
                    'price'      => 12.3, //最终商品价格 非必须，如果有，则需要验证正确性 非必须
                    'coupon_id'  =>  111,  //使用优惠券的id 非必须
                ],
                [
                    'goods_id'   => 43,
                    'product_id' => 49,
                    'works_id'   => 0,  //无diy作品 (可能是0或2)
                    'file_info'  => [
                        'file_url' => 'http://cover/xxx.pdf||http://inner/xxx.pdf',
                        'pages_num'  => 20  //冲印张数或照片书内页数
                    ],
                    'file_type'  => 2,  //1,diy文件 2,稿件url 0,无
                    'price_mod'  => 1,   //1正常按本/个计价 单价*buy_num 2按张数计价 单价*buy_num*photo_pages
                    'buy_num'    => 2,  //购买数量
                    //'real_fee'  => 16.3, // 价格  商品单价*数量
                    'part_mjz_discount' =>  4, // 平摊的优惠金额
                    'price'      => 12.3,
                ],
            ],
            //收货人信息
            'receiver_info' => [      //必须
                'consignee'         => '张三',        //必须
                'ship_mobile'       => '15915774478',//必须
                'province_name'     => '北京',  //地区编码和名称二选一,不要混用，如果可以，最好提供地区编码
                'province_code'     => '420000',
                'city_name'         => '舟山群岛新区',
                'city_code'         => '',
                'district_name'     => '唐县',
                'district_code'     => '',
                'ship_addr'         => '天盈创意园3306房',//必须
                'ship_tel'         => '020845565454',//电话，选填
                'ship_zip'         => '51000',//邮政编码，选填
            ],
            'outer_order_no' => 'x23423432',     //关联的第三方单号 选填
            'shipping_temp_id'    =>  1,        //快递模板id 必须
            'shipping_id'    =>  1,        //快递id 必须
            'partner_code'   =>  '200',    //合作代码，以些代码开头生成订单号
            'total_amount'   =>  73.3,      //订单总价 商品价格+快递价格+税费  非必填
            'discount_fee'   =>  21,      //总优惠                     非必填

            'payment'        =>  35,          //订单总金额，实际价格 含运费 ,如果提交了，则会验算 非必填
            //'post_fee'       =>  8,            //运费  选填
            'mch_id'         =>  5,          //商家id,必须
            'chanel_id'      =>  2,           //渠道id,必须
            'buyer_type'     =>  2,         // 终端用户类型 1代表分销 2代表会员，其他无效 必须
            'user_id'        =>  1,          //用户id,必须
            'note'           => '麻烦加急一下',  //用户备注  选填
            //支付信息
            'pay_info'     =>[  //支付信息 必填
                'pay_id'   => 1,  //余额、支付宝、微信等支付对应的id 必须
                'need_pay' => 23.45,  //应付金额 选填
                'payed'    => 23.45,  //已付金额 选填
                'status'   => 1,  //付款状态  选填
            ],
            'coupon_info'         => [   //优惠信息 选填
                'coupon_id'  => 11, //'优惠券id',
                'coupon_discount' => 5, //优惠金额
            ],
            'point_info'          => [  //积分信息 选填
                'used'    => 1000,   //使用积分 选填
                'point_discount'  =>  10,  //抵扣金额  选填
            ],
            'invoice_info'         => [  //发票信息 选填
                'fee'       =>  0,  //税费
                'type'      =>  1,  // 1电子 2纸质
                'info'      => 'xxxx明细',  //发票信息
                'user_type' => 1,   //抬头类型 1个人  2企业
                'title'     =>  'xxxxx公司',  //抬头
                'taxer_no'  => 'xxxxxxxxxxxx',  //纳税人识别号
            ]


        ];
        //$objOrder = new OrdersEntity();
        $this->servicesOrder->create($post_data);

        //redis缓存操作


        exit;

       // var_dump(json_encode(\Config::get('app')));exit;
        try {

//            $messageData = [
//                'type' => 'sms',
//                'options' => [
//                    'query' => [
//                        'RegionId' => "cn-hangzhou",
//                        'PhoneNumbers' => "15915774779",
//                        'SignName' => "爱美印",
//                        'TemplateCode' => "SMS_638505261",
//                        'TemplateParam' => "{'code':'345678'}",
//                    ]
//                ]
//            ];
//
//            //放入消息通知队列
//            $ret = Notice::dispatch($messageData)->onQueue('q1');exit;
            //模拟出错的情况
            if(true) {
                //throw new CommonException(__('exception.goods_list_failed.dev',['goods_id' =>122]),20002,false,'dev');
                $this->throwControllerException(40001,__FILE__.':'.__LINE__,["对裱纪念册","122"], true, 'dev');
            }

            return parent::index(); // TODO: Change the autogenerated stub
        } catch (CommonException $e) {
            //统一收集错误再做处理
            var_dump($e->getMessage());
        }

    }

    //添加/编辑操作
    public function save(DemoRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }


    //同步newmy模板数据
    public function syncTemp(Request $request)
    {
        $id = $request->input('tid');
        app(SyncData::class)->syncNewmyTemp($id);
    }

    //同步newmy素材数据
    public function syncMaterial(Request $request)
    {
        //1 背景 2 装饰 3 画框
        $type = $request->input('type');
        app(SyncData::class)->syncNewmyMaterial($type);
    }

    public function syncLayout(Request $request)
    {
        app(SyncData::class)->syncTbTcmMsg();
        exit;

        $sizeId = $request->input('size_id');
        app(SyncData::class)->syncNewmyLayout($sizeId);
    }

    public function syncSkuProNo()

    {
         //app(SyncData::class)->syncSkuProNo();
        app(SyncData::class)->syncTbTcmMsgRe();
    }

}