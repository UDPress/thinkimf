<?php
/**
 *
 * ============================================================================
 * [UD IMF System] Copyright (c) 1995-2028https://www.thinkimf.com；
 * 版权所有 1995-2028 UD数据信息有限公司【中国】，并保留所有权利。
 * This is not  free soft ware, use is subject to license.txt
 * 网站地址: https://www.thinkimf.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: 陈建华 $
 * $Create Time: 2017-9-6 23:36:30 $
 * email:unnnnn@foxmail.com
 */

// 应用行为扩展定义文件
return [
    // 应用初始化
    'app_init'     => [
        'imf\\behavior\\InitHookBehavior',
    ],
    // 应用开始
    'app_begin'    => [
        'imf\\behavior\\LangBehavior',
    ],
    // 模块初始化
    'module_init'  => [],
    // 操作开始执行
    'action_begin' => [],
    // 视图内容过滤
    'view_filter'  => [],
    // 日志写入
    'log_write'    => [],
    // 应用结束
    'app_end'      => [],
    // 应用开始
    'admin_init'   => [
        'imf\\behavior\\AdminLangBehavior',
    ],
    'home_init'    => [
        'imf\\behavior\\HomeLangBehavior',
    ]
];
