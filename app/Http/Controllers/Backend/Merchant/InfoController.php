<?php
namespace App\Http\Controllers\Backend\Merchant;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Merchant\MerchantRequest;
use App\Repositories\OmsMerchantAccountRepository;
use App\Repositories\OmsMerchantInfoRepository;
use Illuminate\Http\Request;

/**
 * 商户管理->商户列表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020-03-05
 */
class InfoController extends BaseController
{
    protected $viewPath = 'backend.merchant.info';  //当前控制器所的view所在的目录
    protected $modules = 'sys';                     //当前控制器所属模块
    protected $noNeedRight = [];                    //无需检查权限


    public function __construct(OmsMerchantInfoRepository $merchantInfoRepository,OmsMerchantAccountRepository $merchantAccountRepository)
    {
        parent::__construct();
        $this->repositories = $merchantInfoRepository;
        $this->groupList = $merchantAccountRepository->getGroupList();
    }

    public function index()
    {
        return view('backend.merchant.info.index');
    }

    //添加、编辑操作
    public function save(MerchantRequest $request)
    {
        $param = $request->all();
        unset($param['_token']);

        $ret = $this->repositories->save($param);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

    //创建账号
    public function create(Request $request)
    {
        try {
            if($request->ajax())
            {
                $mch_id = $request->id;
                $htmlContents = $this->renderHtml('backend.merchant.info.create', ['groupList' => $this->groupList,'mch_id'=>$mch_id]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

}