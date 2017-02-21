<?php
namespace House\Controller;
use Think\Controller;
class SettingController extends CommonController{

//**********Banner图片管理************
    //列表
    function bannerList(){
        $user = M("flash_scroll");
        $info = $user ->order("is_show desc,custom_sort desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //过时与恢复
    function bannerLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，过时成功！";
            $sa = false;
        }else{
            $str = "恭喜您，恢复成功！";
            $sa = true;
        }
        $model = M("flash_scroll");
        $data = array('is_show'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);
        //操作成功
        $this->success($str,__MODULE__."/Setting/bannerList");
    }

    //新增
    function bannerAdd(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\Flash_scrollModel();
            //验证数据 Flash_scrollModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }


            //上传照片处理
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      'CDN/uploaded/flash-images/'; // 设置附件上传根目录
            $upload->autoSub  =      true;
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['url']);
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功 获取上传文件信息
                $photo =  $info['savepath'].$info['savename'];
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["update_time"] = date("Y-m-d H:i:s");
            $log["regenerator"] = session("a_username");
            $log["is_show"] = checkBit(I('post.is_show'));
            $log["description_heading"] = I("post.description_heading");
            $log["description_paragraph"] = I("post.description_paragraph");
            $log["custom_sort"] = I("post.custom_sort");
            $log["link"] = I("post.link");
            $log["url"] = $photo;
            $model->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Setting/bannerList");

        }else{
            $this -> display();
        }
    }

    //修改
    function bannerEdit(){
        if (!empty($_POST)){
            $model = new \Model\Flash_scrollModel();
            //验证数据 Flash_scrollModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            if ($model->find(I("post.pk")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //处理信息
                if ($_FILES['url']['name']==''){
                    //文件未上传
                }else{
                    //有修改文件上传

                    //上传照片处理
                    $upload = new \Think\Upload();// 实例化上传类
                    $upload->maxSize   =     3145728 ;// 设置附件上传大小
                    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                    $upload->rootPath  =      'CDN/uploaded/flash-images/'; // 设置附件上传根目录
                    $upload->autoSub  =      true;
                    // 上传单个文件
                    $info   =   $upload->uploadOne($_FILES['url']);
                    if(!$info) {// 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{// 上传成功 获取上传文件信息
                        $photo =  $info['savepath'].$info['savename'];
                        $data["photo"] = $photo;
                    }
                }

                //数据修改
                $data["update_time"] = date("Y-m-d H:i:s");
                $data["regenerator"] = session("a_username");
                $data["is_show"] = checkBit(I('post.is_show'));
                $data["description_heading"] = I("post.description_heading");
                $data["description_paragraph"] = I("post.description_paragraph");
                $data["custom_sort"] = I("post.custom_sort");
                $data["link"] = I("post.link");


                $model->  where("pk=".I('post.pk'))  ->setField($data);
                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Setting/bannerList");
            }


        }else{
            $a = M("flash_scroll");
            if ($a->find(I("get.pa")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //修改界面展示
                $info = $a -> where("pk=".I("get.pa")) -> select();
                $this -> assign("info",$info);
                $this -> display();
            }
        }
    }

    //查看
    function bannerShow(){
        $user = M("flash_scroll");

        if ($user->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else{
            //界面展示
            $info = $user -> where("pk=".I("get.pa")) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }

    }


//**********首页广告图片管理************
    //列表
    function adList(){
        $user = M("ad");
        $info = $user ->order("expired asc,custom_sort desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //过时与恢复
    function adLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，过时成功！";
            $sa = true;
        }else{
            $str = "恭喜您，恢复成功！";
            $sa = false;
        }
        $model = M("ad");
        $data = array('expired'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);
        //操作成功
        $this->success($str,__MODULE__."/Setting/adList");
    }

    //新增
    function adAdd(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\AdModel();
            //验证数据 AdModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //上传照片处理
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      'CDN/uploaded/ads/'; // 设置附件上传根目录
            $upload->autoSub  =      true;
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['ad_path']);
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功 获取上传文件信息
                $photo =  $info['savepath'].$info['savename'];
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["update_time"] = date("Y-m-d H:i:s");
            $log["regenerator"] = session("a_username");
            $log["expired"] = checkBit(I('post.expired'));
            $log["custom_sort"] = I("post.custom_sort");
            $log["link"] = I("post.link");
            $log["click_count"] = 0;
            $log["ad_describe"] = I("post.ad_describe");
            $log["ad_name"] = I("post.ad_name");
            $log["position"] = I("post.position");
            $log["ad_path"] = $photo;
            $model->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Setting/adList");

        }else{
            $this -> display();
        }
    }

    //修改
    function adEdit(){
        if (!empty($_POST)){
            $model = new \Model\AdModel();
            //验证数据 AdModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }


            if ($model->find(I("post.pk")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //处理信息
                if ($_FILES['ad_path']['name']==''){
                    //文件未上传
                }else{
                    //有修改文件上传

                    //上传照片处理
                    $upload = new \Think\Upload();// 实例化上传类
                    $upload->maxSize   =     3145728 ;// 设置附件上传大小
                    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                    $upload->rootPath  =      'CDN/uploaded/ads/'; // 设置附件上传根目录
                    $upload->autoSub  =      true;
                    // 上传单个文件
                    $info   =   $upload->uploadOne($_FILES['ad_path']);
                    if(!$info) {// 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{// 上传成功 获取上传文件信息
                        $photo =  $info['savepath'].$info['savename'];
                        $data["ad_path"] = $photo;
                    }
                }

                //数据修改
                $data["update_time"] = date("Y-m-d H:i:s");
                $data["regenerator"] = session("a_username");
                $data["expired"] = checkBit(I('post.expired'));
                $data["custom_sort"] = I("post.custom_sort");
                $data["link"] = I("post.link");
                $data["ad_describe"] = I("post.ad_describe");
                $data["ad_name"] = I("post.ad_name");
                $data["position"] = I("post.position");


                $model->  where("pk=".I('post.pk'))  ->setField($data);
                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Setting/adList");
            }


        }else{
            $a = M("ad");
            if ($a->find(I("get.pa")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //修改界面展示
                $info = $a -> where("pk=".I("get.pa")) -> select();
                $this -> assign("info",$info);
                $this -> display();
            }
        }
    }

    //查看
    function adShow(){
        $user = M("ad");

        if ($user->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else{
            //界面展示
            $info = $user -> where("pk=".I("get.pa")) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }

    }

}