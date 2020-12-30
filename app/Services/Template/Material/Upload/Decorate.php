<?php
namespace App\Services\Template\Material\Upload;

/**
 * 装饰上传类
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2018/7/24
 */
class Decorate extends Base
{
    public function run()
    {
        //上传基础处理
        $this->beforeRun();

        $sys_config = $this->sysConfig;

        //需要切的参数
        $params[$this->mid] = $sys_config['crop']['mid'];
        $params[$this->sml] = $sys_config['crop']['sml'];

        $this->createCrop($params);

        //返回数据
        $info = $this->getFileInfo();

        if($this->stateInfo != 0){
            return ['success'=>false,'data'=>$this->stateInfo];
        }else{
            return ['success'=>true,'data'=>$info];
        }

    }
}