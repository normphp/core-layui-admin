<?php
/**
 * Class BasicsLayuiAdminService
 * layuiadmin 处理服务
 */
namespace normphpCore\layuiAdmin\service\layuiadmin;
use normphpCore\layuiAdmin\service\config\BasicsConfigService;
use normphp\model\cache\Cache;
use normphp\model\db\Model;

/**
 * Class BasicsLayuiAdminService
 * @package normphpCore\layuiAdmin\service\layuiadmin
 */
class BasicsLayuiAdminService
{
    /**
     * 获取首页index
     * @param string $domain
     * @return string
     */
    public function getIndexHtml(string $domain):string
    {
        $data = Cache::get(['BasicsLayuiAdminService','getIndexHtml']);
        if ((!app()->__EXPLOIT__ && \Deploy::ENVIRONMENT !=='develop')){
            if ($data)return $data;
        }
        # 获取db配置
        $DbData = BasicsConfigService::getConfigAll(app()->__EXPLOIT__ ===1?false:true);
        $PRODUCT_INFO = $DbData['site']['PRODUCT_INFO']??[];

        $data = [
            #                                                                      生产环境每天变化一次版本号  开发模式每一秒钟变化一次
            'version'=>(!app()->__EXPLOIT__ && \Deploy::ENVIRONMENT !=='develop')?'1.0.1.'.date('md'):date('YmdHis'),
            'title'=>$PRODUCT_INFO['title']??\Config::PRODUCT_INFO['title'],
            'css'=>'https://www.layuicdn.com/layui-v2.5.6/css/layui.css',
            'js'=>'https://www.layuicdn.com/layui-v2.5.6/layui.all.js',
            'iconfont'=>\Deploy::VIEW_RESOURCE_PREFIX.((!app()->__EXPLOIT__ && \Deploy::ENVIRONMENT !=='develop')?'/dist/':'/src/').'style/font.css',//自定义图片库
            #'css'=>'./'.\Deploy::VIEW_RESOURCE_PREFIX.'/start/layui/css/layui.css',
            #'js'=>'./'.\Deploy::VIEW_RESOURCE_PREFIX.'/start/layui/layui.js',
        ];

        if (\Deploy::CDN_URL !==''){
                $data['index']  =   '../../'.\Deploy::MODULE_PREFIX.'/layui-admin/home/index';//index
                $data['base']   =   \Deploy::CDN_URL.'/'.\Deploy::VIEW_RESOURCE_PREFIX.((!app()->__EXPLOIT__ && \Deploy::ENVIRONMENT !=='develop')?'/dist/':'/src/');
        }else{
            $data['index']  =   '../../'.\Deploy::MODULE_PREFIX.'/layui-admin/home/index';//index
            $data['base']   =  './'.\Deploy::VIEW_RESOURCE_PREFIX.((!app()->__EXPLOIT__ && \Deploy::ENVIRONMENT !=='develop')?'/dist/':'/src/');
        }
        $file = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'index.html');
        $data = $this->str_replace($file,$data);
        Cache::set(['BasicsLayuiAdminService','getIndexHtml'],$data,120);
        return $data;
    }

    /**
     * 获取index配置
     * @param string $domain
     * @return string
     */
    public function getIndexJs(string $domain):string
    {
        $data = Cache::get(['BasicsLayuiAdminService','getIndexJs']);
        if ((!app()->__EXPLOIT__ && \Deploy::ENVIRONMENT !=='develop')){
            if ($data)return $data;
        }
        $data = [
            'config'=>'../../'.\Deploy::MODULE_PREFIX.'/layui-admin/home/config',
        ];
        $file = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'index.js');
        $data = $this->str_replace($file,$data);
        Cache::set(['BasicsLayuiAdminService','getIndexJs'],$data,120);
        return $data;
    }

    /**
     * 获取配置
     * @param string $domain
     * @return string
     * @throws \Exception
     */
    public function getConfig(string $domain):string
    {
        $data = Cache::get(['BasicsLayuiAdminService','getConfig']);
        if ((!app()->__EXPLOIT__ && \Deploy::ENVIRONMENT !=='develop')){
            if ($data)return $data;
        }

        # 路由 前缀
        foreach (\Deploy::SERVICE_MODULE as $vlaue) {$modulePrefix[$vlaue['path']] =  '/'.$vlaue['MODULE_PREFIX'].'/';}

        # 获取db中的配置（默认使用缓存）
        $DbData = BasicsConfigService::getConfigAll(app()->__EXPLOIT__ ===1?false:true);
        $PRODUCT_INFO = $DbData['site']['PRODUCT_INFO']??[];
        $data = [
            'debug' => (!app()->__EXPLOIT__ && \Deploy::ENVIRONMENT !== 'develop') ? 'false' : 'true',
            'console' => ($PRODUCT_INFO['console']??\Config::PRODUCT_INFO['name']),
            'tokenName' => \Config::ACCOUNT['GET_ACCESS_TOKEN_NAME'],
            'productInfo' => "'".Helper()->json_encode($PRODUCT_INFO)."'",
            'productInfo.name' => $PRODUCT_INFO['name'] ?? \Config::PRODUCT_INFO['name'],
            'productInfo.describe' => $PRODUCT_INFO['describe'] ?? \Config::PRODUCT_INFO['describe'],
            'productInfo.ICP' => $PRODUCT_INFO['ICP'] ?? \Config::PRODUCT_INFO['ICP'] ?? '粤ICP备xxxxxxx号',
            'productInfo.extend' => Helper()->json_encode($PRODUCT_INFO['extend'] ?? \Config::PRODUCT_INFO['extend']),
            'CDN_URL' => empty(\Deploy::CDN_URL) ? '' : \Deploy::CDN_URL,
            'prefix' => Helper()->json_encode($modulePrefix),
            'loginSwitch' => $DbData['GEETEST']['loginSwitch']??false,
            'forgetSwitch' => $DbData['GEETEST']['forgetSwitch']??false,
            'registerSwitch' => $DbData['GEETEST']['registerSwitch']??false,
            'uuid'=>Model::UUID_ZERO,
        ];
        # 获取模板文件
        $file = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'config.js');
        $data = $this->str_replace($file,$data);
        # 更新缓存
        Cache::set(['BasicsLayuiAdminService','getConfig'],$data,120);
        return $data;
    }
    /**
     * 替换
     * @param $template
     * @param $data
     * @return string
     */
    protected function str_replace($template, $data):string
    {
        foreach($data as $key=>$vuleu){
            if(!is_array($vuleu)){
                if ($vuleu === true){$vuleu = 'true';};
                if ($vuleu === false){$vuleu = 'false';};
                $template = str_replace("'{{{$key}}}'",$vuleu,$template);
                $template = str_replace("{{{$key}}}",$vuleu,$template);
            }
        }
        return $template;
    }

}