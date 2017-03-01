<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    function index(){

        //获取banner
        $flash_scroll = M("flash_scroll");
        $banner = $flash_scroll->field("description_heading as title,description_paragraph as str,link,url")-> where("is_show = 1") ->order("custom_sort desc") -> select();
        $this -> assign("banner",$banner);

        //获取图片故事
        $picture_story = M("picture_story");
        $story = $picture_story->field("thumbnail,title,pk")-> where("is_show = 1") ->order("custom_sort desc")-> limit("1")->select() ;
        $this -> assign("story",$story);

        //获取知名媒体专题
        $friendship_links = M("friendship_links");
        $media = $friendship_links->where("position = 1")->field("name,url") ->limit("5") ->order("custom_sort desc") ->select();
        $this -> assign("media",$media);


//        //获取article
//        $article = M("article");
//        $article_arr = $article->field("title,pk") ->order("custom_sort desc") ->select();
//        $this -> assign("media",$article_arr);


        $this -> display();
    }

    function regsiter(){
        $this -> display();
    }

    function login(){
        $this -> display();
    }

    //查找username是否重名
    function checkuser(){
        $user = M("member"); // 实例化User对象
        $info = $user->where('username="'.I("post.username").'"')->find();
        if (!empty($info)){
            $data['valid'] = false;
        }else{
            $data['valid'] = true;
        }
        $this->ajaxReturn($data);
    }

    //查找username是否重名
    function checkemail(){
        $user = M("member"); // 实例化User对象
        $info = $user->where('email="'.I("post.email").'"')->find();
        if (!empty($info)){
            $data['valid'] = false;
        }else{
            $data['valid'] = true;
        }
        $this->ajaxReturn($data);
    }

    //联系我们
    function contact_us(){
        //获取联系我们
        $contact_us = M("contact_us");
        $info = $contact_us -> select();
        $this -> assign("info",$info);

        //文章类左侧公共显示获取
        $article = M("article");
        //获取图片故事
        $picture_story = M("picture_story");
        $story = $picture_story->field("thumbnail,title,pk")-> where("is_show = 1") ->order("custom_sort desc")-> limit("1")->select() ;
        $this -> assign("story",$story);

        //获取研修生园地
        $researcher  = $article ->join("as a LEFT JOIN article_model AS b ON a.pk = b.article_id LEFT JOIN model AS c ON b.model_id = c.pk ")->field("a.pk,a.title") ->order("a.custom_sort desc,a.update_time desc") ->where("c.pk = 12") -> limit("5") -> select();
        $this -> assign("researcher",$researcher);

        //获取专业委员会
        $Committee  = $article ->join("as a LEFT JOIN article_model AS b ON a.pk = b.article_id LEFT JOIN model AS c ON b.model_id = c.pk ")->field("a.pk,a.title") ->order("a.custom_sort desc,a.update_time desc") ->where("c.pk = 14") -> limit("5") -> select();
        $this -> assign("Committee",$Committee);

        //获取投票
        $vote = M("vote");
        $vote_arr = $vote ->field("title,pk")->order("custom_sort desc,update_time desc")->where("is_show = 1")->limit("5")->select();
        $this -> assign("vote_arr",$vote_arr);

        
        $this -> display();
    }

}