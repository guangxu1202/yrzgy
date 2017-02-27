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

    function contact_us(){
        //获取图片故事
        $picture_story = M("picture_story");
        $story = $picture_story->field("thumbnail,title,pk")-> where("is_show = 1") ->order("custom_sort desc")-> limit("1")->select() ;
        $this -> assign("story",$story);


        
        $this -> display();
    }

}