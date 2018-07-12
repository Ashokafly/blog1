<?php
namespace app\admin\validate;
use think\Validate;
class Admin extends Validate{
    protected $rule=[
      'username'=>'require|max:15',
      'password'=>'require',
    ];

    protected $message=[
      'username.require'=>'管理员必须填写',
      'username.max'=>'管理员长度不得大于25位',
        'password.require'=>'管理员密码必须填写',
    ];

    //添加场景验证
    protected $scene=[
        'add'=>['username'=>'require','password'],
        'edit'=>['username'=>'require'],
    ];

}