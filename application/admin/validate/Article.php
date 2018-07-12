<?php
namespace app\admin\validate;
use think\Validate;
class Article extends Validate{
    protected $rule=[
        'title'=>'require|max:25',
        'cated'=>'require',
    ];

    protected $message=[
        'title.require'=>'题目必须填写',
        'title.max'=>'标题长度不得超过15位',
        'cated.reqiure'=>'请选择文章所属栏目',
    ];

    protected $scene=[
        'add'=>['title'=>'require'],
        'edit'=>['title'=>'require']
    ];
}