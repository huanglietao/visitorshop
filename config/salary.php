<?php
/**
 * 薪酬模块公共配置
 *
 * Created by PhpStorm.
 * Name: cjx
 * Date: 2020/06/28
 */

return [

    //职位配置
    'position_setting'   =>  [
        1 => [
            'name' => '机长',       //名称
            'rate' => 1.1 ,         //系数
            'per_money' => 70,      //日记件工资
        ],
        2=> [
            'name' => '助手',       //名称
            'rate' => 1.0 ,         //系数
            'per_money' => 71,      //日记件工资
        ],
        3=> [
            'name' => '普工',       //名称
            'rate' => 1.0 ,         //系数
            'per_money' => 72,      //日记件工资
        ],
    ],

    //薪酬计算取条数
    'salary_limit' => 50,


];