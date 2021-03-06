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
namespace app\admin\api;

use app\admin\model\NavModel;

class NavApi
{
    // 分类列表 用于模板设计
    public function index($param = [])
    {
        $navModel = new NavModel();

        $where = [];

        if (!empty($param['keyword'])) {
            $where['name'] = ['like', "%{$param['keyword']}%"];
        }

        return $navModel->where($where)->select();
    }

}