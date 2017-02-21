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
            $log["link"] = I("post.link");
            $log["url"] = $photo;
            $model->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Cms/personList");

        }else{
            $this -> display();
        }
    }

    //修改
    function bannerEdit(){
        if (!empty($_POST)){
            $model = new \Model\Person_introModel();
            //验证数据 Person_introModel
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
                if ($_FILES['photo']['name']==''){
                    //文件未上传
                }else{
                    //有修改文件上传

                    //上传照片处理
                    $upload = new \Think\Upload();// 实例化上传类
                    $upload->maxSize   =     3145728 ;// 设置附件上传大小
                    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                    $upload->rootPath  =      'CDN/uploaded/person/'; // 设置附件上传根目录
                    $upload->autoSub  =      true;
                    // 上传单个文件
                    $info   =   $upload->uploadOne($_FILES['photo']);
                    if(!$info) {// 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{// 上传成功 获取上传文件信息
                        $photo =  $info['savepath'].$info['savename'];
                        $thumb = $info['savepath'].'thumb_'.$info['savename'];

                        //缩略图处理
                        $image = new \Think\Image();
                        $image->open('CDN/uploaded/person/'.$photo);
                        // 生成缩略图
                        $image->thumb(110, 90)->save('CDN/uploaded/person/'.$thumb);
                        $data["photo"] = $photo;
                        $data["thumbnail"] = $thumb;
                    }
                }

                //数据修改

                $data["update_time"] = date("Y-m-d H:i:s");
                $data["regenerator"] = session("a_username");
                $data["allow_copy"] = checkBit(I('post.allow_copy'));
                $data["author"] = I("post.author");
                $data["content"] = I("post.content");
                $data["custom_sort"] = I("post.custom_sort");
                $data["keywords"] = I("post.keywords");
                $data["photo_describe"] = I("post.photo_describe");
                $data["summary"] = I("post.summary");
                $data["title"] = I("post.title");

                $model->  where("pk=".I('post.pk'))  ->setField($data);
                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cms/personList");
            }


        }else{
            $a = M("person_intro");
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
        $user = M("person_intro");

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