<?php
/**
 * 站点基础配置
 */
namespace normphpCore\layuiAdmin\model\config;


use normphp\model\db\Model;

class SiteConfigModel extends Model
{
    /**
     * 表结构
     * @var array
     */
    protected $structure = [
        'id'=>[
            'TYPE'=>'uuid','COMMENT'=>'主键uuid','DEFAULT'=>false,
        ],
        'name'=>[
            'TYPE'=>"varchar(100)", 'DEFAULT'=>false, 'COMMENT'=>'配置名称',
        ],
        'config'=>[
            'TYPE'=>"json", 'DEFAULT'=>false, 'COMMENT'=>'详细配置',
        ],
        'extend'=>[
            'TYPE'=>"json", 'DEFAULT'=>false, 'COMMENT'=>'扩展',
        ],
        /**
         * UNIQUE 唯一
         * SPATIAL 空间
         * NORMAL 普通 key
         * FULLTEXT 文本
         */
        'INDEX'=>[
            ['TYPE'=>'UNIQUE','FIELD'=>'name','NAME'=>'name','USING'=>'BTREE','COMMENT'=>'配置名'],
        ],//索引 KEY `ip` (`ip`) COMMENT 'sss 'user_name
        'PRIMARY'=>'id',//主键
    ];
    /**
     * @var string 表备注（不可包含@版本号关键字）
     */
    protected $table_comment = '站点基础配置';
    /**
     * @var int 表版本（用来记录表结构版本）在表备注后面@$table_version
     */
    protected $table_version = 0;
    /**
     * @var array 表结构变更日志 版本号=>['表结构修改内容sql','表结构修改内容sql']
     */
    protected $table_structure_log = [
    ];
    protected $initData = [

    ];
    protected function initData()
    {
        $data =[
            [# 用户相关配置: 默认注册账号登录限制
                'name'=>'user',
                'config'=>[
                    # 注册配置
                    'register'=>[
                        'role'=>'0EQD12A2-8824-9943-E8C9-C83E40F360D1',//注册账号默认角色
                        'status'=>'2',//状态1等待审核、2审核通过3、禁止使用4、保留
                    ],
                    # 登录配置
                    'login'=>[
                        'logon_online_count'=>['value'=>'3','diy'=>false],//同时在线数
                        'password_wrong_count'=>['value'=>'5','diy'=>false],//密码错误数
                        'password_wrong_lock'=>['value'=>'10','diy'=>false],//密码错误超过限制的锁定时间：分钟
                        'logon_token_period_pattern'=>['value'=>'3','diy'=>false],//登录token模式1、谨慎（分钟为单位）2、常规（小时为单位）3、方便（天为单位）4、游客（单位分钟没有操作注销）
                        'logon_token_period_time'=>['value'=>'2','diy'=>false],//登录token有效期对应logon_token_period_pattern
                        'password_wrong_lock'=>['value'=>'10','diy'=>false],//密码错误超过限制的锁定时间：分钟
                        'password_wrong_lock'=>['value'=>'10','diy'=>false],//密码错误超过限制的锁定时间：分钟
                    ],
                ],
            ],
            [# 用户相关配置: 用户基础配置
                'name'=>'userBaseConfig',
                'config'=>[
                    # 注册配置
                    'register'=>[
                        'role'=>'0EQD12A2-8824-9943-E8C9-C83E40F360D1',//注册账号默认角色
                        'status'=>'2',//状态1等待审核、2审核通过3、禁止使用4、保留
                    ],
                    # 账号配置
                    'account'=>[
                        # 注册
                        'register'=>[
                            'mobile'    =>   true,//是否需要填写并验证手机号
                            'email'     =>   false,//是否需要填写并验证邮箱
                            'verifyWeChat'    =>false,//是否需要填写并验微信（关注验证）
                            'mid'       =>false,//账号id

                        ],
                        # 登录
                        'logon'=>[
                            'mobile'    =>   false,//是否手机号登录
                            'email'     =>   false,//是否邮箱登录
                            'verifyWeChat'    =>false,//是否微信微信（微信内通过网页授权，但是对当前站点域名有要求）
                            'mid'       =>true,//账号id

                        ],
                        'forgetPassword'=>[
                            'mobile'    =>   true,//可使用手机号码验证找回密码
                            'email'     =>   false,//可使用邮箱验证找回密码
                            'mid'       =>false,//账号id

                        ],
                    ],
                ],
            ],
            # 网站信息配置
            [
                'name'=>'site',
                'config'=>[
                    'PRODUCT_INFO'=>[
                        'console'=> 'normphp控制台',
                        'meta' => 'normphp',
                        'name' => 'normphp',
                        'title' => 'normphp',
                        'ICP' => '粤ICP备xxxxxxxx号',
                        'copyright' => 'normphp',
                        'copyrightUrl' => 'https://www.normphp.org',
                        'extend' => [
                            'homeLoginLay' => '<h2>欢迎来使用normphp框架</h2><br><br><p>normphp致力于从开发到交付过程中规范团队协作提高开发交付效率</p><br><br>'
                        ],
                        'describe' => '一个非常适合团队协作的微型框架'
                    ]
                ],
            ],
            [# 用户密码安全配置：暂时不确定是否在站点数据库配置中还是直接在config配置文件中
                'name'=>'accountSafety',
                'config'=>[
                    'algo'                        => 1,
                    'options'                     => [
                        'cost' => 11
                    ],
                    'number_count'                => 20,
                    'logon_token_salt'            => Helper()->str()->str_rand(42),
                    'password_regular'            => [
                        '/^.*(?=.{6,})(?=.*\\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*+=?]).*$/',
                        '密码至少并且6位且包含大小写字母+特殊字符 !@#$%^&*?+='
                    ],
                    'GET_ACCESS_TOKEN_NAME'       => 'access-token',
                    'HEADERS_ACCESS_TOKEN_NAME'   => 'HTTP_ACCESS_TOKEN',
                    'user_logon_token_salt_count' => 22
                ],
            ],[
                'name'=>'GEETEST',
                'config'=>[
                    'frequency'=>4,//单次验证成功的测试  可重复使用的频率
                    'CAPTCHA_ID' => '',
                    'PRIVATE_KEY' => '',
                    'MD5' => Helper()->str()->str_rand(32),
                    'forgetSwitch'=>false,# 是否开启验证  找回密码时
                    'registerSwitch'=>false,# 是否开启验证 注册时
                    'loginSwitch'=>false, # 是否开启验证 登录时
                    'verifySwitch' => false,# 是否开启验证 发送短信或者邮箱时
                ],
            ]
        ];
        return $this->add($data);
    }
}