<?php
/**
 * 模板相关配置
 *
 * 模板相关配置
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/27
 */
return [
    //素材相关的
    'material' => [
        //上传相关
        'upload' => [
            'dir'   => env('TP_UPLOAD_DIR'),
            'temp_view_pic' => 'temp',
            'works_view_pic' => 'works',
            'tp_url'  => env('TP_UPLOAD_URL'),
            'frame_hz' => '_mask',
            //素材相关配置
            'material_type'     =>[
                'background' => [  //背景
                    'name'  => '模板背景',
                    'crop' => [
                        'mid' => 1000,
                        'mmid' => 600,
                        'sml' => 200
                    ]
                ],
                'decorate'   => [  //装饰
                    'name'  => '模板装饰',
                    'crop' => [
                        'mid' => 500,
                        'mmid' => 600,
                        'sml' => 200
                    ]
                ],
                'frame'     => [    //画框
                    'name'  => '模板画框',
                    'crop' => [
                        'mid' => 500,
                        'sml' => 200
                    ]
                ],
                'view'      => [    //翻翻看
                    'name'  => '翻翻看',
                    'crop' => [
                        'mid' => 500,
                        'sml' => 200
                    ]
                ],
                'fontfile'  => [    //字体文件

                ],
                'font'      => [    //字体图片

                ],
                'special'   => [    //特殊元素
                    'name'  => '特殊元素',
                    'crop' => [
                        'mid' => 300,
                        'sml' => 200
                    ]
                ]
            ]
        ]
    ],
    'coml_url'            => env('COML_URL'),
    'coml_pc_url'         => env('COML_PC_URL'),
    'coml_mobile_url'     => env('COML_M_URL'),
];
