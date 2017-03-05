<?php
namespace Home\Controller;
use Think\Controller;
class SystemController extends CommonController {

    //条款显示
    function show(){
        $miscellaneous = M("miscellaneous");
        $info = $miscellaneous->where("code='".I("get.u")."'") ->find();

        if ($info == null){
            //错误ID
            $this->error("页面无法访问！");
        }else {
            //获取所属模块
            $info1[0] = $info;
            $this->assign("info1", $info1);

            $this->display();
        }
    }
    //知名媒体专题报道
    function  report(){
        //列表获取
        $friendship_links = M("friendship_links");
        $count      = $friendship_links->field("name,url") ->where("position = 1") ->order("custom_sort desc")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = $friendship_links->field("name,url") ->where("position = 1")->order('custom_sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this->display();
    }
    //技术团队
    function  team(){
        //列表获取
        $person_intro = M("person_intro");
        $count      = $person_intro->field("title,pk,update_time") ->where("is_show = 1") ->order("custom_sort desc")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = $person_intro->field("title,pk,update_time") ->where("is_show = 1") ->order("custom_sort desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this->display();
    }

    //技术团队详情
    function team_info(){
        $person_intro = M("person_intro");
        if ($person_intro->find(I("get.u")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else {

            $info = $person_intro-> field("title,keywords,summary,author,update_time,content,photo,photo_describe") ->where("pk=".I("get.u")) ->select();
            $this->assign("info", $info);
            $this->display();
        }
    }

    //资料下载
    function download(){
        $file_download = M("file_download");
        $count      = $file_download->field("pk") ->where("expired <> 1") ->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = $file_download->field("file_name,pk,update_time,file_path") ->where("expired <> 1") ->order("custom_sort desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this->display();
    }

    //投票列表
    function vote(){
        $vote = M("vote");
        $count      = $vote->field("pk") ->where("is_show = 1") ->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = $vote->field("title,pk,update_time") ->where("is_show = 1") ->order("custom_sort desc,update_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this->display();
    }

    //投票详情页
    function vote_info(){
        if (!empty($_POST)) {
            $vote = M("vote");
            if ($vote->find(I("post.id")) == null) {
                //错误ID
                $this->error("页面无法访问！");
            } else {
                //获取投票内容
                if (I("post.myradio") == '' && I("post.mycheckbox") =='' ){
                    $this->error("没有赋值！");
                }else{
                    //判断重复投票
                    if (session("?vote.".I("post.id"))){
                        $this->error("您已经投过票了，请不要重复投票！");
                    }else{
                        //记录投票
                        $vitem = M("vote_item");
                        if (I("post.myradio") == ''){
                            //多选
                            foreach (I("post.mycheckbox") as $checkbox){
                                $vitem->where('vote_id='.I("post.id").' and pk='.$checkbox)->setInc('vote_number',1);
                            }
                        }else{
                            //单选
                            $vitem->where('vote_id='.I("post.id").' and pk='.I("post.myradio"))->setInc('vote_number',1);
                        }
                        //设置重复投票限制
                        session("vote.".I("post.id"),I("post.id"));

                        //录入成功
                        $this->success("恭喜您，投票成功！",__MODULE__."/System/vote_result/u/".I("post.id"));
                    }

                }
            }
        }else{
            $vote = M("vote");
            if ($vote->find(I("get.u")) == null) {
                //错误ID
                $this->error("页面无法访问！");
            } else {
                //投票内容
                $info = $vote->where("pk=" . I("get.u"))->select();
                $this->assign("info", $info);

                //获取投票选项
                $vote_item = M("vote_item");
                $item = $vote_item->where("vote_id=".I("get.u")) -> select();
                $this->assign("item", $item);

                $this->display();
            }
        }
    }

    //查看投票结果
    function vote_result(){

        $a = M("vote");
        if ($a->find(I("get.u")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else{
            //界面展示
            $b = M("vote_item");
            $info = $a -> where("pk=".I("get.u")) -> select();
            $info1 = $b -> where("vote_id=".I("get.u")) -> select();
            $time = $b-> where("vote_id=".I("get.u")) ->sum("vote_number");

            $info2 = array();
            $c = 0;
            foreach ($info1 as $v){
                $info2[$c]["content"] = $v["content"];
                $info2[$c]["num"] = $v["vote_number"];
                $info2[$c]["per"] = round($v["vote_number"]/$time*100,2);
                $c++;
            }

            $this -> assign("info",$info);
            $this -> assign("info1",$info2);
            $this -> assign("times",$time);
            $this -> display();
        }
    }

}