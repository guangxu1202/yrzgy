<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    function index(){
        //获取导航
        $model = M("model");
        $nav = $model->field("name,pk") -> where("is_show = 1 and custom_sort >0") ->order("custom_sort desc") -> select();
        $this -> assign("nav",$nav);

        //获取banner
        $flash_scroll = M("flash_scroll");
        $banner = $flash_scroll->field("description_heading as title,description_paragraph as str,link,url")-> where("is_show = 1") ->order("custom_sort desc") -> select();
        $this -> assign("banner",$banner);

        //获取图片故事
        $picture_story = M("picture_story");
        $story = $picture_story->field("thumbnail,title,pk")-> where("is_show = 1") ->order("custom_sort desc")-> limit("1")->select() ;
        $this -> assign("story",$story);


        $this -> display();
    }

}