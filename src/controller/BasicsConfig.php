<?php
/**
 * 网站配置控制器
 */

namespace normphpCore\layuiAdmin\controller;


use normphpCore\layuiAdmin\service\config\BasicsConfigService;
use normphp\staging\Controller;
use normphp\staging\Request;

class BasicsConfig extends Controller
{
    /**
     * 基础控制器信息
     */
    const CONTROLLER_INFO = [
        'User'=>'pizepei',
        'title'=>'网站配置控制器',//控制器标题
        'namespace'=>'bases',//门面控制器命名空间
        'baseAuth'=>'UserAuth:test',//基础权限继承（加命名空间的类名称）
        'basePath'=>'/layui-admin/site/config/',//基础路由
        'baseParam'=>'[$Request:normphp\staging\Request]',//依赖注入对象
    ];

    /**
     * @Author 皮泽培
     * @Created 2020/3/16 11:47
     * @param Request $Request
     *      path [object]
     *          name [string] 配置名
     * @return array [json] 定义输出返回数据
     *      data [raw]
     * @title  网站配置列表
     * @explain 路由功能说明
     * @authGroup basics.menu.getMenu:权限分组1,basics.index.menu:权限分组2
     * @baseAuth Resource:public
     * @throws \Exception
     * @router get  info/:name[string]
     */
    public function info(Request $Request)
    {
        $this->succeed(BasicsConfigService::getConfigAll(false)[$Request->path('name')],'获取成功');
    }

    /**
     * @Author 皮泽培
     * @Created 2020/3/16 11:47
     * @param Request $Request
     *      path [object]
     *          name [string] 配置名
     *      raw [raw] 配置名
     * @return array [json] 定义输出返回数据
     *      data [raw]
     * @title  网站配置列表
     * @explain 路由功能说明
     * @authGroup basics.menu.getMenu:权限分组1,basics.index.menu:权限分组2
     * @baseAuth Resource:public
     * @throws \Exception
     * @router put  info/:name[string]
     */
    public function updateInfo(Request $Request)
    {
        $this->succeed(BasicsConfigService::setConfigAll($Request->path('name'),$Request->raw()),'设置成功');
    }


}