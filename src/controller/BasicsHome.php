<?php
/**
 * 首页控制器
 */
namespace normphpCore\layuiAdmin\controller;
use normphpCore\layuiAdmin\service\layuiadmin\BasicsLayuiAdminService;
use normphp\staging\Controller;

class BasicsHome extends Controller
{
    /**
     * 基础控制器信息
     */
    const CONTROLLER_INFO = [
        'User'=>'pizepei',
        'title'=>'后台首页控制台',//控制器标题
        'className'=>'layui_admin',//门面控制器名称
        'namespace'=>'bases',//门面控制器命名空间
        'basePath'=>'/layui-admin/home/',//基础路由
    ];
    /**
     * @return array [html]
     * @title  / 默认首页
     * @explain 注意所有 path 路由都使用 正则表达式为唯一凭证 所以 / 路由只能有一个
     * @baseAuth UserAuth:public
     * @router get /index.html
     */
    public function index()
    {
        return (new BasicsLayuiAdminService())->getIndexHtml($_SERVER['SERVER_NAME']);
    }

    /**
     * @return array [js]
     * @title  / 默认首页
     * @explain 注意所有 path 路由都使用 正则表达式为唯一凭证 所以 / 路由只能有一个
     * @baseAuth UserAuth:public
     * @router get config.js
     */
    public function homeConfig()
    {
        return (new BasicsLayuiAdminService())->getConfig($_SERVER['SERVER_NAME']);
    }
    /**
     * @return array [js]
     * @title  / 默认首页
     * @explain 注意所有 path 路由都使用 正则表达式为唯一凭证 所以 / 路由只能有一个
     * @baseAuth UserAuth:public
     * @router get index.js
     */
    public function homeIndex()
    {
        return (new BasicsLayuiAdminService())->getIndexJs($_SERVER['SERVER_NAME']);
    }
}