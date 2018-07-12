<?php
namespace app\index\controller;
use app\index\controller\Base;
class Article extends Base
{
    public function index()
    {
        $arid=input('arid');
        $articles=db('article')->find($arid);
        $relateres=$this->relat($articles['keywords'],$articles['id']);
        //页面中每访问一次增加一次热度值
        Db('article')->where('id','=',$arid)->setInc('click');
        $cates=db('cate')->find($articles['cateid']);
        $recres=db('article')->where(array('cateid'=>$cates['id'],'state'=>1))->limit(4)->select();
        $this->assign(array(
            'articles'=>$articles,
            'cates'=>$cates,
            'recres'=>$recres,
            'relateres'=>$relateres,
        ));
        return $this->fetch('article');
    }

    public function relat($keywords,$id){
        $arr=explode(',',$keywords);
        static $relateres=array();
        foreach($arr as $k=>$v){
            $map['keywords']=['like','%'.$v.'%'];
            $map['id']=['neq',$id];
            $artres=db('article')->where($map)->order('id desc')->limit(8)->select();
            $relateres=array_merge($relateres,$artres);
        }
        //推荐阅读中去重
        $ralateres=arr_unique($relateres);
        return $ralateres;
    }
}
