<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Admin;
class Login extends Controller{
    public function index(){
        if(request()->isPost()){
           $admin=new Admin();
           $data=input('post.');
           $num=$admin->login($data);
           if($num==3){
               $this->success('信息正确，登录中......','index/index');
           }elseif($num==4){
               $this->error('验证码错误');
           }
           else{
               $this->error('用户名或者密码错误');
           }
        }
        return $this->fetch('login');
    }

}