<?php
/**
 * User : YuGang Yang
 * Date : 7/27/15
 * Time : 15:26
 * Email: smartydroid@gmail.com
 */

return [
    'disable_routes'              => true, //禁用自带routes，默认启用
    'auth'                        => [
        'administrator_table'           => 'admins',
        'administrator_auth_controller' => 'Auth\AuthController'
    ],
    'site_config'                 => [
        'site_name'   => 'DEMO后台',
        'title'       => 'DEMO后台',
        'description' => 'DEMO后台',
        'logo'        => 'vendor/forone/images/logo.png'
    ],
    'RedirectAfterLoginPath'      => 'home', // 登录后跳转页面
    'RedirectIfAuthenticatedPath' => 'admin/roles', // 如果授权后直接跳转到指定页面

    'menus'               => [
        'DEMO' => [
            'icon'       => 'mdi-action-track-changes',
            'permission' => 'admin',//PERMISSION_TRACK,
            'children'   => [
                '表单组件' => [
                    'uri' => 'form',
                ],
                '数据列表' => [
                    'uri' => 'newdata',
                ],
                '图片管理(七牛)' => [
                    'uri' => 'imgs',
                ],
                '文件管理(七牛)' => [
                    'uri' => 'files',
                ],
                '操作日志' => [
                    'uri' => 'operations',
                ]
            ],

        ],
        '用户管理' => [
            'icon'       => 'mdi-image-timer-auto',
            'permission' => 'admin',
            'uri' => 'user',
        ],
        '权限设置' => [
            'icon'       => 'mdi-toggle-radio-button-on',
            'permission' => 'admin',
            'children'   => [
                '角色管理'    => [
                    'uri' => 'roles',
                ],
                '权限管理'    => [
                    'uri' => 'permissions',
                ],
                '管理员管理'   => [
                    'uri' => 'admins',
                ]

            ],
        ],
    ],
    'qiniu'               => [
        'host'       => env('QINIU_HOST', 'http://7xlntj.com2.z0.glb.qiniucdn.com/'), //your qiniu host url
        'access_key' => env('QINIU_AK', '7uuXy55ekyLfIw9gwI2Jr4Oin_9qHIQQfXi4ijL1'), //for test
        'secret_key' => env('QINIU_SK', 'FX8P2NE_iE2TR0pwMkK1f3ZErGqGlsmjffCOIZUq'), //for test
        'bucket'     => env('QINIU_BT', 'poly')
    ],
];