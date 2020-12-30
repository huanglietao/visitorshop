<?php
/**
 * 业务异常抛出处理
 *
 * 对App\Exceptions\CommonException进行再次封装，把语言包和多层错误定义组合进来
 * 与throwControllerException功能一致
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/17
 */

namespace App\Services;


use App\Exceptions\CommonException;

class Exception
{
    /**
     * 通用抛出异常
     * @param int $code 错误代码，映射配置文件config/exception.php中的数组索引
     * @param string $pos  错误位置
     * @param array $lang_key_val  替换异常语言包里的参数,数组，与语言包里key对应.
     * @param bool $is_notice  是否发送通知
     * @param string $notice_who  通知的对象
     * @param null $ext_info    附带的额外日志信息以及
     * @throws CommonException 抛出公用异常
     */
    public function throwException($code, $pos, $lang_key_val= null, $is_notice = false, $notice_who = "all", $ext_info = null)
    {
        //将错误行数存到配置中(单生命周期有效)
        \Config::set("exception_pos", $pos);
        //获取错误码对应的错误信息
        $exceptionMessage = $this->getExceptionMessageByCode($code, $lang_key_val);

        //抛出异常
        throw new CommonException($exceptionMessage, $code, $is_notice, $notice_who, $ext_info);
    }

    /**
     * 通过错误代码获取错误信息,
     * @param $code 错误代码
     * @param $lang_key_val 替换异常语言包里的参数,数组，与语言包里key对应.
     * @return string  $strComposeException  信息1||信息2
     */
    private function getExceptionMessageByCode($code, $lang_key_val)
    {
        //通过code获取获取错误信息
        $exceptionConfig = config("exception")[$code];
        //将错识误信息以'||'连接传到exception
        $allException =__($exceptionConfig);
        $strComposeException = ''; //把异常信息用||连接起来

        if(is_array($allException)) {
            //先把异常信息循环看是不是替换(循环替换)
            if(!empty($lang_key_val)) {

                $tempExceptionArr = [];
                $allException = array_values($allException);

                foreach ($allException as $k=>$v) {
                    if(count($lang_key_val) == 1) {
                        $tempExceptionArr[$k] = __($v,['key' => $lang_key_val[0]]);
                    } else {
                        $tempExceptionArr[$k] = __($v,['key' => $lang_key_val[$k]]);
                    }

                }
                $strComposeException = implode('||', $tempExceptionArr);

            } else {
                $strComposeException = implode('||', $allException);
            }

        } else {
            $strComposeException = $allException;
        }
        return $strComposeException;
    }

}