<?php
/**
 * 配置类型
 */
namespace normphpCore\layuiAdmin\service\config;


use normphpCore\layuiAdmin\model\config\SiteConfigModel;
use normphp\model\cache\Cache;

class BasicsConfigService
{
    /**
     * 获取全部配置
     * @param bool $cache 是否使用缓存
     * @return array
     */
    public static function getConfigAll(bool $cache = true):array
    {
        if ($cache){
            $data = Cache::get(['BasicsConfigService','SiteConfigAll']);
            if ($data){return $data;}
        }
        $DbData = SiteConfigModel::table()->fetchAll();
        # 写入缓存
        if ($DbData){
            foreach ($DbData as $value){
                $data[$value['name']] = $value['config'];
            }
            Cache::set(['BasicsConfigService','SiteConfigAll'],$data,120);
            return $data;
        }
        return [];
    }
    /**
     * 设置配置
     * @param bool $name 配置内容
     * @param array $setData 数据
     * @return array
     */
    public static function setConfigAll(string $name = '',array $setData)
    {
        $function = 'set'.ucfirst($name);
        $data = self::getConfigAll(false)[$name]??[];
        static::$function($setData,$data);
        $res = SiteConfigModel::table()->where(['name'=>$name])->update(['config'=>$data]);
        static::getConfigAll(false);
        return $res;
    }

    /**
     * 设置基本配置
     *  @param array  $setData
     * @param array  $data
     */
    public static function setSite(array $setData,array &$data)
    {
        Helper()->arrayList()->verifyMergeData($setData, $data);
    }

    /**
     * 设置基本配置
     *  @param array  $setData
     * @param array  $data
     */
     public static function setUserBaseConfig(array $setData,array &$data)
     {
         $setData['account']['logon']['email'] = isset($setData['account']['logon']['email'])?true:false;
         $setData['account']['logon']['mobile'] = isset($setData['account']['logon']['mobile'])?true:false;
         $setData['account']['logon']['verifyWeChat'] = isset($setData['account']['logon']['verifyWeChat'])?true:false;
         $setData['account']['logon']['mid'] = isset($setData['account']['logon']['mid'])?true:false;

         $setData['account']['register']['email'] = isset($setData['account']['register']['email'])?true:false;
         $setData['account']['register']['mobile'] = isset($setData['account']['register']['mobile'])?true:false;
         $setData['account']['register']['verifyWeChat'] = isset($setData['account']['register']['verifyWeChat'])?true:false;
         $setData['account']['register']['mid'] = isset($setData['account']['register']['mid'])?true:false;

         $setData['account']['forgetPassword']['email'] = isset($setData['account']['forgetPassword']['email'])?true:false;
         $setData['account']['forgetPassword']['mobile'] = isset($setData['account']['forgetPassword']['mobile'])?true:false;
         $setData['account']['forgetPassword']['verifyWeChat'] = isset($setData['account']['forgetPassword']['verifyWeChat'])?true:false;
         $setData['account']['forgetPassword']['mid'] = isset($setData['account']['forgetPassword']['mid'])?true:false;


         Helper()->arrayList()->verifyMergeData($setData, $data);
     }

    /**
     * 设置用户配置
     *  @param array  $setData
     * @param array  $data
     */
    public static function setUser(array $setData,array &$data)
    {
        $setData['login']['logon_online_count']['diy'] = isset($setData['login']['logon_online_count']['diy'])?true:false;
        $setData['login']['password_wrong_count']['diy'] = isset($setData['login']['password_wrong_count']['diy'])?true:false;
        $setData['login']['password_wrong_lock']['diy'] = isset($setData['login']['password_wrong_lock']['diy'])?true:false;
        $setData['login']['logon_token_period_pattern']['diy'] = isset($setData['login']['logon_token_period_pattern']['diy'])?true:false;
        $setData['login']['logon_token_period_time']['diy'] = isset($setData['login']['logon_token_period_time']['diy'])?true:false;
        Helper()->arrayList()->verifyMergeData($setData,$data);


    }
    /**
     * 设置基本配置 GEETEST
     *  @param array  $setData
     * @param array  $data
     */
    public static function setGEETEST(array $setData,array &$data)
    {
        $setData['loginSwitch'] = isset($setData['loginSwitch'])?true:false;
        $setData['forgetSwitch'] = isset($setData['forgetSwitch'])?true:false;
        $setData['verifySwitch'] = isset($setData['verifySwitch'])?true:false;
        $setData['registerSwitch'] = isset($setData['registerSwitch'])?true:false;
        $setData['frequency'] = (int)$setData['frequency'];
        Helper()->arrayList()->verifyMergeData($setData,$data);
    }
    /**
     * 快速设置数据
     * @param $setData
     * @param $data
     * @return bool
     */
    public static function setData($setData,&$data)
    {
        foreach ($data as $key=>&$value)
        {
            if (isset($setData[$key])){
                # 判断是否相同的数据类型
                if (!is_array($setData[$key])  && gettype($setData[$key]) === gettype($value))
                {
                    $value = $setData[$key];
                }elseif (is_array($setData[$key])){
                    static::setData($setData[$key],$value);
                }
            }
        }
    }
}