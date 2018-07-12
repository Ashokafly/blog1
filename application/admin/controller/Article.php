<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\controller\Base;
use app\admin\model\Article as ArticleModel;
class Article extends Base{
    public function lst(){
        //分页显示数据，每页显示3条
        //$list=ArticleModel::paginate(3);
        //数组查询
//        $list=db('article')->alias('a')->join('cate c','c.id=a.cateid')->field('a.id,a.title,a.pic,a.author,a.state,c.catename')
//        ->paginate(3);
        //关联数组
        $list=ArticleModel::paginate(3);
        //分配到模板当中
        $this->assign('list',$list);
        return $this->fetch('list');
    }

    public function edit(){
        $id=input('id');
        $articles=db('article')->find($id);
        if(request()->isPost()){
            $data=[
                'id'=>input('id'),
                'title'=>input('title'),
                'author'=>input('author'),
                'desc'=>input('desc'),
                'keywords'=>str_replace('，',',',input('keywords')),
                'content'=>input('content'),
                'cateid'=>input('cateid'),
            ];
            if(input('state')=='on'){
                $data['state']=1;
            }else{
                $data['state']=0;
            }
            //判断上传缩略图
            if($_FILES['pic']['tmp_name']){
                @unlink(SITE_URL.'/public/static'.$articles['pic']);
                $file=request()->file('pic');
                $info=$file->move(ROOT_PATH.'public'.DS.'static/uploads');
                $data['pic']='/uploads/'.$info->getSaveName();
            }
//            //修改管理员密码，有新密码则使用新密码，否则没有则使用旧密码
//            if(input('password')){
//                $data['password']=md5(input('password'));
//            }else{
//                $data['password']=$articles['password'];
//            }
            $validate=\think\Loader::validate('Article');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());die;
            }
            //修改管理员信息
            if(db('article')->update($data)){
                $this->success('修改成功','lst');
            }else{
                $this->error('修改管理员信息失败');
            }
            dump($data);
            return;
        }
        $this->assign('articles',$articles);
        $cateres=db('cate')->select();
        $this->assign('cateres',$cateres);
        return $this->fetch('edit');
    }
    public function add(){
        if(request()->isPost()){
            $data=[
                'title'=>input('title'),
                'author'=>input('author'),
                'desc'=>input('desc'),
                'keywords'=>str_replace('，',',',input('keywords')),
                'content'=>input('content'),
                'cateid'=>input('cateid'),
                'time'=>time(),
            ];
            if(input("state")=='on'){
                $data['state']=1;
            }
            if($_FILES['pic']['tmp_name']){
                $file=request()->file('pic');
                $info=$file->move(ROOT_PATH.'public'.DS.'static/uploads');
                $data['pic']='/uploads/'.$info->getSaveName();
            }
            $validate=\think\Loader::validate('Article');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());die;
            }
            if(Db::name('article')->insert($data)){
                return $this->success("添加文章成功",'lst');
            }else{
                return $this->error("添加文章失败");
            }
            return;
        }
        $cateres=db('cate')->select();
        $this->assign('cateres',$cateres);
        return $this->fetch('add');
    }

    //管理员删除操作
    public function del(){
        $id=input('id');
            if(db('article')->delete(input('id'))){
                $this->success('删除文章成功','lst');
            }else{
                $this->error('删除文章失败');
            }
    }
}