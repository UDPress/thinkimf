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
return array (
	'account_sid' => array (// 在后台插件配置表单中的键名 ,会是config[text]
		'title' => 'ACCOUNT SID', // 表单的label标题
		'type' => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
		'value' => '',// 表单的默认值
		'tip' => '主帐号,对应开官网发者主账号下的ACCOUNT SID' //表单的帮助提示
	),
    'auth_token' => array (// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'AUTH TOKEN', // 表单的label标题
        'type' => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip' => '主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN' //表单的帮助提示
    ),
    'app_id' => array (// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'APP ID', // 表单的label标题
        'type' => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip' => '应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID' //表单的帮助提示
    ),
    'template_id' => array (// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => '模板ID', // 表单的label标题
        'type' => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip' => '模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID' //表单的帮助提示
    ),
    'expire_minute' => array (// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => '有效期', // 表单的label标题
        'type' => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '30',// 表单的默认值
        'tip' => '短信验证码过期时间，单位分钟' //表单的帮助提示
    ),
);
					