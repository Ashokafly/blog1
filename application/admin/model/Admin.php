<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Admin extends Model{
    public function login($data){
        //验证码检验
        $captcha=new \think\captcha\Captcha();
        if(!$captcha->check($data['code'])){
            return 4;
        }
        $user=Db::name('admin')->where('username','=',$data['username'])->find();
//        $user=db('admin')->where(['username'=>$data['username']])->find();
        if($user){
            if($user['password']==$data['password']){
                session('username',$user['username']);
                session('uid',$user['id']);
                return 3;   //信息正确
            }else{
                return 2; //密码错误
            }
        }else{
            return 1;       //1表示用户不存在
        }
    }
}