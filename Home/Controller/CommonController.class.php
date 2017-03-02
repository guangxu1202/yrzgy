<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function _initialize(){
        // 自动运行方法
        //获取导航
        $model = M("model");
        $nav = $model->field("name,pk") -> where("is_show = 1 and custom_sort >0") ->order("custom_sort desc") -> select();
        $this -> assign("nav",$nav);

        //获取底部友情链接
        $friendship_links = M("friendship_links");
        $friend = $friendship_links->where("position = 0")->field("name,url") ->order("custom_sort desc") ->select();
        $this -> assign("friend",$friend);

        //登录状态判断
        if(!session('?c_id')){
            //未登录
        }else{
            $id = session('c_id');
            $this -> assign("id",$id);
        }

        //客服状态判定
        $customer_service = M("customer_service");
        if ($customer_service -> find() == null){
        }else{
            $service = "xixi";
            $slist = $customer_service ->select();
            $this -> assign("service",$slist);
        }


        //文章类左侧公共显示获取
        //获取图片故事
        $picture_story = M("picture_story");
        $story = $picture_story->field("thumbnail,title,pk")-> where("is_show = 1") ->order("custom_sort desc")-> limit("1")->select() ;
        $this -> assign("story",$story);

        //获取研修生园地
        $article = M("article");
        $researcher  = $article ->join("as a LEFT JOIN article_model AS b ON a.pk = b.article_id LEFT JOIN model AS c ON b.model_id = c.pk ")->field("a.pk,a.title") ->order("a.custom_sort desc,a.update_time desc") ->where("c.pk = 12") -> limit("5") -> select();
        $this -> assign("researcher",$researcher);

        //获取专业委员会
        $Committee  = $article ->join("as a LEFT JOIN article_model AS b ON a.pk = b.article_id LEFT JOIN model AS c ON b.model_id = c.pk ")->field("a.pk,a.title") ->order("a.custom_sort desc,a.update_time desc") ->where("c.pk = 14") -> limit("5") -> select();
        $this -> assign("Committee",$Committee);

        //获取投票
        $vote = M("vote");
        $vote_arr = $vote ->field("title,pk")->order("custom_sort desc,update_time desc")->where("is_show = 1")->limit("5")->select();
        $this -> assign("vote_arr",$vote_arr);

    }
}