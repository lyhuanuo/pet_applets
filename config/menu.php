<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/8
 * Time: 10:56
 */

return [
    "homeInfo" => [
        "title" => "首页",
        "href" => ""
    ],
    "logoInfo" => [
        "title" => "后台",
        "image" => "admin/images/logo.png",
        "href" => ""
    ],
    "menuInfo" => [
        [
            "title" => "管理员管理",
            "icon" => "fa fa-address-book",
            "href" => "",
            "target" => "_self",
            "child" => [
                ["title" => "管理员列表",
                    "href" => "user",
                    "icon" => "fa fa-home",
                    "target" => "_self",
                ], [
                    "title" => "管理员日志",
                    "href" => "user/log",
                    "icon" => "fa fa-home",
                    "target" => "_self",
                ],


            ],
            [
                "title" => "菜单管理",
                "href" => "page/menu.html",
                "icon" => "fa fa-window-maximize",
                "target" => "_self"
            ],
            ["title" => "系统设置",
                "href" => "setting",
                "icon" => "fa fa-gears",
                "target" => "_self"
            ],

        ],
        [
            "title" => "组件管理",
            "icon" => "fa fa-lemon-o",
            "href" => "",
            "target" => "_self",
            "child" => [
                [
                    "title" => "图标列表",
                    "href" => "page/icon.html",
                    "icon" => "fa fa-dot-circle-o",
                    "target" => "_self"
                ], [
                    "title" => "富文本编辑器",
                    "href" => "page/editor.html",
                    "icon" => "fa fa-edit",
                    "target" => "_self"
                ]

            ]
        ],
        [
            "title" => "其它管理",
            "icon" => "fa fa-slideshare",
            "href" => "",
            "target" => "_self",
            "child" => [
                ["title" => "多级菜单",
                    "href" => "",
                    "icon" => "fa fa-meetup",
                    "target" => "",
                    "child" => [
                        "title" => "按钮1",
                        "href" => "page/button.html?v=1",
                        "icon" => "fa fa-calendar",
                        "target" => "_self",
                        "child" => [

                        ]
                    ]
                ], [
                    "title" => "失效菜单",
                    "href" => "page/error.html",
                    "icon" => "fa fa-superpowers",
                    "target" => "_self"
                ]
            ],
        ],
    ],
];