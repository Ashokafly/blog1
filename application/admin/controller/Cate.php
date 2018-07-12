<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\controller\Base;
use app\admin\model\Cate as CateModel;
class Cate extends Base{
    public function lst(){
        //分页显示数据，每页显示3条
        $list=CateModel::paginate(3);
        //分配到模板当中
        $this->assign('list',$list);
        return $this->fetch('list');
    }

    public function edit(){
        $id=input('id');
        $cates=db('cate')->find($id);
        if(request()->isPost()){
            $data=[
                'id'=>input('id'),
                'catename'=>input('catename'),
            ];

            //修改管理员密码，有新密码则使用新密码，否则没有则使用旧密码
//            if(input('password')){
//                $data['password']=md5(input('password'));
//            }else{
//                $data['password']=$cates['password'];
//            }
            $validate=\think\Loader::validate('Cate');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());die;
            }
            //修改管理员信息
            if(db('cate')->update($data)){
                $this->success('修改成功','lst');
            }else{
                $this->error('修改栏目信息失败');
            }
            dump($data);
            return;
        }
        $this->assign('cates',$cates);
        return $this->fetch('edit');
    }
    public function add(){
        if(request()->isPost()){
            $data=[
                'catename'=>input('catename'),
            ];
            $validate=\think\Loader::validate('Cate');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());die;
            }
            if(Db::name('cate')->insert($data)){
                return $this->success("添加栏目表成功",'lst');
            }else{
                return $this->error("添加栏目表失败");
            }
            return;
        }
        return $this->fetch('add');
    }

    //管理员删除操作
    public function del(){
        $id=input('id');
        if($id!=2){
            if(db('cate')->delete(input('id'))){
                $this->success('删除栏目列表成功','lst');
            }else{
                $this->error('删除栏目列表失败');
            }
        }else{
            $this->error('初始化栏目列表不能删除!');
        }

    }
}