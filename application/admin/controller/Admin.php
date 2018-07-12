<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\controller\Base;
use app\admin\model\Admin as AdminModel;
class Admin extends Base{
    public function lst(){
        //分页显示数据，每页显示3条
        $list=AdminModel::paginate(3);
        //分配到模板当中
        $this->assign('list',$list);
        return $this->fetch('list');
    }

    public function edit(){
        $id=input('id');
        $admins=db('admin')->find($id);
        if(request()->isPost()){
            $data=[
                'id'=>input('id'),
                'username'=>input('username'),
            ];

            //修改管理员密码，有新密码则使用新密码，否则没有则使用旧密码
            if(input('password')){
                $data['password']=md5(input('password'));
                }else{
                $data['password']=$admins['password'];
                }
            $validate=\think\Loader::validate('Admin');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());die;
            }
            //修改管理员信息
            if(db('admin')->update($data)){
                $this->success('修改成功','lst');
            }else{
                $this->error('修改管理员信息失败');
            }
            return;
        }
        $this->assign('admins',$admins);
        return $this->fetch('edit');
    }
    public function add(){
        if(request()->isPost()){
            $data=[
                'username'=>input('username'),
                'password'=>input('password'),
            ];
            $validate=\think\Loader::validate('Admin');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());die;
            }
            if(Db::name('admin')->insert($data)){
                return $this->success("添加管理员成功",'lst');
            }else{
                return $this->error("添加管理员失败");
            }
            return;
        }
        return $this->fetch('add');
    }

    //管理员删除操作
    public function del(){
        $id=input('id');
        if($id!=2){
            if(db('admin')->delete(input('id'))){
                $this->success('删除管理员成功','lst');
            }else{
                $this->error('删除管理员失败');
            }
        }else{
            $this->error('初始化管理员不能删除!');
        }

    }

    //退出登录删除操作
    public function loginout(){
        session(null);
        $this->success('退出成功','login/index');
    }
}