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
namespace app\admin\controller;

use imf\controller\AdminBaseController;
use app\admin\model\PluginModel;
use app\admin\model\HookPluginModel;
use think\Db;
use think\Validate;

/**
 * Class PluginController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'插件管理',
 *     'action' =>'default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 20,
 *     'icon'   =>'cloud',
 *     'remark' =>'插件管理'
 * )
 */
class PluginController extends AdminBaseController
{

    protected $pluginModel;

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 插件列表
     * @adminMenu(
     *     'name'   => '插件列表',
     *     'parent' => 'admin/Plugin/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '插件列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $pluginModel = new PluginModel();
        $plugins     = $pluginModel->getList();
        $this->assign("plugins", $plugins);
        return $this->fetch();
    }

    /**
     * 插件启用/禁用
     * @adminMenu(
     *     'name'   => '插件启用禁用',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '插件启用禁用',
     *     'param'  => ''
     * )
     */
    public function toggle()
    {
        $id = $this->request->param('id', 0, 'intval');

        $pluginModel = PluginModel::get($id);

        if (empty($pluginModel)) {
            $this->error('插件不存在！');
        }

        $status         = 1;
        $successMessage = "启用成功！";

        if ($this->request->param('disable')) {
            $status         = 0;
            $successMessage = "禁用成功！";
        }

        $pluginModel->startTrans();

        try {
            $pluginModel->save(['status' => $status], ['id' => $id]);

            $hookPluginModel = new HookPluginModel();

            $hookPluginModel->save(['status' => $status], ['plugin' => $pluginModel->name]);

            $pluginModel->commit();

        } catch (\Exception $e) {

            $pluginModel->rollback();

            $this->error('操作失败！');

        }

        $this->success($successMessage);
    }

    /**
     * 插件设置
     * @adminMenu(
     *     'name'   => '插件设置',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '插件设置',
     *     'param'  => ''
     * )
     */
    public function setting()
    {
        $id = $this->request->param('id', 0, 'intval');

        $pluginModel = new PluginModel();
        $plugin      = $pluginModel->find($id);

        if (empty($plugin)) {
            $this->error('插件未安装!');
        }

        $plugin = $plugin->toArray();

        $pluginClass = imf_get_plugin_class($plugin['name']);
        if (!class_exists($pluginClass)) {
            $this->error('插件不存在!');
        }

        $pluginObj = new $pluginClass;
        //$plugin['plugin_path']   = $pluginObj->plugin_path;
        //$plugin['custom_config'] = $pluginObj->custom_config;
        $pluginConfigInDb = $plugin['config'];
        $plugin['config'] = include $pluginObj->getConfigFilePath();

        if ($pluginConfigInDb) {
            $pluginConfigInDb = json_decode($pluginConfigInDb, true);
            foreach ($plugin['config'] as $key => $value) {
                if ($value['type'] != 'group') {
                    if (isset($pluginConfigInDb[$key])) {
                        $plugin['config'][$key]['value'] = $pluginConfigInDb[$key];
                    }
                } else {
                    foreach ($value['options'] as $group => $options) {
                        foreach ($options['options'] as $gkey => $value) {
                            if (isset($pluginConfigInDb[$gkey])) {
                                $plugin['config'][$key]['options'][$group]['options'][$gkey]['value'] = $pluginConfigInDb[$gkey];
                            }
                        }
                    }
                }
            }
        }

        $this->assign('data', $plugin);
//        if ($plugin['custom_config']) {
//            $this->assign('custom_config', $this->fetch($plugin['plugin_path'] . $plugin['custom_config']));
//        }

        $this->assign('id', $id);
        return $this->fetch();

    }

    /**
     * 插件设置提交
     * @adminMenu(
     *     'name'   => '插件设置提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '插件设置提交',
     *     'param'  => ''
     * )
     */
    public function settingPost()
    {
        if ($this->request->isPost()) {
            $id = $this->request->param('id', 0, 'intval');

            $pluginModel = new PluginModel();
            $plugin      = $pluginModel->find($id)->toArray();

            if (!$plugin) {
                $this->error('插件未安装!');
            }

            $pluginClass = imf_get_plugin_class($plugin['name']);
            if (!class_exists($pluginClass)) {
                $this->error('插件不存在!');
            }

            $pluginObj = new $pluginClass;
            //$plugin['plugin_path']   = $pluginObj->plugin_path;
            //$plugin['custom_config'] = $pluginObj->custom_config;
            $pluginConfigInDb = $plugin['config'];
            $plugin['config'] = include $pluginObj->getConfigFilePath();

            $rules    = [];
            $messages = [];

            foreach ($plugin['config'] as $key => $value) {
                if ($value['type'] != 'group') {
                    if (isset($value['rule'])) {
                        $rules[$key] = $this->_parseRules($value['rule']);
                    }

                    if (isset($value['message'])) {
                        foreach ($value['message'] as $rule => $msg) {
                            $messages[$key . '.' . $rule] = $msg;
                        }
                    }

                } else {
                    foreach ($value['options'] as $group => $options) {
                        foreach ($options['options'] as $gkey => $value) {
                            if (isset($value['rule'])) {
                                $rules[$gkey] = $this->_parseRules($value['rule']);
                            }

                            if (isset($value['message'])) {
                                foreach ($value['message'] as $rule => $msg) {
                                    $messages[$gkey . '.' . $rule] = $msg;
                                }
                            }
                        }
                    }
                }
            }

            $config = $this->request->param('config/a');

            $validate = new Validate($rules, $messages);
            $result   = $validate->check($config);
            if ($result !== true) {
                $this->error($validate->getError());
            }

            $pluginModel = new PluginModel();
            $pluginModel->save(['config' => json_encode($config)], ['id' => $id]);
            $this->success('保存成功', '');
        }
    }

    /**
     * 解析插件配置验证规则
     * @param $rules
     * @return array
     */
    private function _parseRules($rules)
    {
        $newRules = [];

        $simpleRules = [
            'require', 'number',
            'integer', 'float', 'boolean', 'email',
            'array', 'accepted', 'date', 'alpha',
            'alphaNum', 'alphaDash', 'activeUrl',
            'url', 'ip'];
        foreach ($rules as $key => $rule) {
            if (in_array($key, $simpleRules) && $rule) {
                array_push($newRules, $key);
            }
        }

        return $newRules;
    }

    /**
     * 插件安装
     * @adminMenu(
     *     'name'   => '插件安装',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '插件安装',
     *     'param'  => ''
     * )
     */
    public function install()
    {
        $pluginName = $this->request->param('name', '', 'trim');
        $class      = imf_get_plugin_class($pluginName);
        if (!class_exists($class)) {
            $this->error('插件不存在!');
        }

        $pluginModel = new PluginModel();
        $pluginCount = $pluginModel->where('name', $pluginName)->count();

        if ($pluginCount > 0) {
            $this->error('插件已安装!');
        }

        $plugin = new $class;
        $info   = $plugin->info;
        if (!$info || !$plugin->checkInfo()) {//检测信息的正确性
            $this->error('插件信息缺失!');
        }

        $installSuccess = $plugin->install();
        if (!$installSuccess) {
            $this->error('插件预安装失败!');
        }

        $methods = get_class_methods($plugin);

        foreach ($methods as $methodKey => $method) {
            $methods[$methodKey] = imf_parse_name($method);
        }

        $systemHooks = $pluginModel->getHooks(true);

        $pluginHooks = array_intersect($systemHooks, $methods);

        //$info['hooks'] = implode(",", $pluginHooks);

        if (!empty($plugin->hasAdmin)) {
            $info['has_admin'] = 1;
        } else {
            $info['has_admin'] = 0;
        }

        $info['config'] = json_encode($plugin->getConfig());

        $pluginModel->data($info)->allowField(true)->save();

        $hookPluginModel = new HookPluginModel();
        foreach ($pluginHooks as $pluginHook) {
            $hookPluginModel->data(['hook' => $pluginHook, 'plugin' => $pluginName, 'status' => 1])->isUpdate(false)->save();
        }

        $this->success('安装成功!');
    }

    /**
     * 插件更新
     * @adminMenu(
     *     'name'   => '插件更新',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '插件更新',
     *     'param'  => ''
     * )
     */
    public function update()
    {
        $pluginName = $this->request->param('name', '', 'trim');
        $class      = imf_get_plugin_class($pluginName);
        if (!class_exists($class)) {
            $this->error('插件不存在!');
        }

        $plugin = new $class;
        $info   = $plugin->info;
        if (!$info || !$plugin->checkInfo()) {//检测信息的正确性
            $this->error('插件信息缺失!');
        }

        $methods = get_class_methods($plugin);

        foreach ($methods as $methodKey => $method) {
            $methods[$methodKey] = imf_parse_name($method);
        }

        $pluginModel = new PluginModel();
        $systemHooks = $pluginModel->getHooks(true);

        $pluginHooks = array_intersect($systemHooks, $methods);

        if (!empty($plugin->hasAdmin)) {
            $info['has_admin'] = 1;
        } else {
            $info['has_admin'] = 0;
        }

        $config = $plugin->getConfig();

        $defaultConfig = $plugin->getDefaultConfig();

        $pluginModel = new PluginModel();

        $config = array_merge($defaultConfig, $config);

        $info['config'] = json_encode($config);

        $pluginModel->allowField(true)->save($info, ['name' => $pluginName]);

        $hookPluginModel = new HookPluginModel();

        $pluginHooksInDb = $hookPluginModel->where('plugin', $pluginName)->column('hook');

        $samePluginHooks = array_intersect($pluginHooks, $pluginHooksInDb);

        $shouldDeleteHooks = array_diff($samePluginHooks, $pluginHooksInDb);

        $newHooks = array_diff($pluginHooks, $samePluginHooks);

        if (count($shouldDeleteHooks) > 0) {
            $hookPluginModel->where('hook', 'in', $shouldDeleteHooks)->delete();
        }

        foreach ($newHooks as $pluginHook) {
            $hookPluginModel->data(['hook' => $pluginHook, 'plugin' => $pluginName])->isUpdate(false)->save();
        }

        $this->success('更新成功!');
    }

    /**
     * 卸载插件
     * @adminMenu(
     *     'name'   => '卸载插件',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '卸载插件',
     *     'param'  => ''
     * )
     */
    public function uninstall()
    {
        $pluginModel = new PluginModel();
        $id          = $this->request->param('id', 0, 'intval');

        $result = $pluginModel->uninstall($id);

        if ($result !== true) {
            $this->error('卸载失败!');
        }

        $this->success('卸载成功!');
    }


}