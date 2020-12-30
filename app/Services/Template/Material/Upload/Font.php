<?php
/**
 * 字体示意图片
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2018/7/24
 */

namespace App\Services\Template\Material\Upload;


class Font extends Base
{
    public function run()
    {

        //上传基础处理
        $this->beforeRun();

        $sys_config = $this->sysConfig;


        //返回数据
        $info = $this->getFileInfo();

        if($this->stateInfo != 0){
            return ['success'=>false,'data'=>$this->stateInfo];
        }else{
            return ['success'=>true,'data'=>$info];
        }

    }
}