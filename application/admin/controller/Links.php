<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;
use app\admin\model\Links as LinksModel;
class Links extends Base{
    public function lst(){
        //分页显示数据，每页显示3条
        $list=LinksModel::paginate(3);
        //分配到模板当中
        $this->assign('list',$list);
        return $this->fetch('list');
    }

    public function edit(){
        $id=input('id');
        $Links=db('links')->find($id);
        if(request()->isPost()){
            $data=[
                'id'=>input('id'),
                'title'=>input('title'),
                'url'=>input('url'),
                'desc'=>input('desc'),
            ];
            $validate=\think\Loader::validate('links');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());die;
            }
            //修改管理员信息
            if(db('links')->update($data)){
                $this->success('修改成功','lst');
            }else{
                $this->error('修改管理员信息失败');
            }
            dump($data);
            return;
        }
        $this->assign('Links',$Links);
        return $this->fetch('edit');
    }
    public function add(){
        if(request()->isPost()){
            $data=[
                'title'=>input('title'),
                'url'=>input('url'),
                'desc'=>input('desc'),
            ];
            $validate=\think\Loader::validate('Links');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());die;
            }
            if(Db::name('Links')->insert($data)){
                return $this->success("添加链接成功",'lst');
            }else{
                return $this->error("添加链接失败");
            }
            return;
        }
        return $this->fetch('add');
    }

    //管理员删除操作
    public function del(){
        $id=input('id');
        if(db('Links')->delete(input('id'))){
            $this->success('删除链接成功','lst');
        }else{
            $this->error('删除链接失败');
        }
        }

}