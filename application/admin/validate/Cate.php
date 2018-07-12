<?php

//待改动，功能唯一性验证未能实现
namespace app\admin\validate;
use think\Validate;
class Cate extends Validate{
    protected $rule=[
      'catename'=>'require|max:25',
    ];

    protected $message=[
      'catename.require'=>'栏目必须填写',
      'catename.max'=>'栏目长度不得大于25位',
      'catename.unique'=>'栏目名称不能重复',
    ];

    //添加场景验证
    protected $scene=[
        'add'=>['catename'=>'require'],
        'edit'=>['catename'=>'require'],
    ];

}