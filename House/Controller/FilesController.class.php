<?php
namespace House\Controller;
use Think\Controller;
class FilesController extends Controller {
    function ck_upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './'; // 设置附件上传根目录
        $upload->savePath  =     '/CDN/uploaded/'; // 设置附件上传（子）目录
        // 上传文件
        $fn = intval(I("get.CKEditorFuncNum"));

        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息   `
            echo '<script type="text/javascript">alert("上传格式错误，请关闭窗口重新上传!");</script>';
        }else{// 上传成功

            //获取具体的路径，用于返回给编辑器
            $savepath = $info["upload"]["savepath"].$info["upload"]["savename"];
            //图片名称
            $message=$info['upload']['name'];
            //下面的输出，会自动的将上传成功的文件路径，返回给编辑器。
            $str='<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$fn.', "'.$savepath.'","","'.$message.'");</script>';
            exit($str);//执行script，插入图片到编辑器
        }

    }
    function  storyCover(){

        $verifyToken = md5('unique_salt' . $_POST['timestamp']);

        if (!empty($_FILES) && $_POST['token'] == $verifyToken) {

            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','bmp');// 设置附件上传类型
            $upload->rootPath  =     'CDN/uploaded/story/'; // 设置附件上传根目录
            $upload->autoSub  =      true;

            // 上传文件
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                $back = array('status' =>0, 'msg'=> 'upload failed');
                $this->ajaxReturn($back,'JSON');
            }else{// 上传成功
                $image = new \Think\Image();
                foreach($info as $file) {

                    $photo =  $file['savepath'].$file['savename'];
                    $thumb = $file['savepath'].'thumb_'.$file['savename'];

                    $image->open('CDN/uploaded/story/'.$photo);
                    // 生成缩略图
                    $image->thumb(100, 75)->save('CDN/uploaded/story/'.$thumb);
                    $data["status"] = 1;
                    $data["savepath"] = $file['savepath'];
                    $data["savename"] = $file['savename'];
                    $data["pic_path"] = $file['savepath'] . $file['savename'];
                    $data["thumb_path"] = $thumb;
                }
                //返回值
                $this->ajaxReturn(json_encode($data) ,'JSON');
            }
        }
    }




    function  videoCover(){
        $verifyToken = md5('unique_salt' . $_POST['timestamp']);
        if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     31457280 ;// 设置附件上传大小
            $upload->exts      =     array('flv', 'mp4', 'm4v');// 设置附件上传类型
            $upload->rootPath  =     'CDN/uploaded/videos/'; // 设置附件上传根目录
            $upload->autoSub  =      true;
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['Filedata']);
            if(!$info) {// 上传错误提示错误信息
              $back = array('status' =>0, 'msg'=> $upload->getError());
              $this->ajaxReturn($back,'JSON');
            }else{// 上传成功 获取上传文件信息

              $data["status"] = 1;
              $data["pic_path"] = $info['savepath'].$info['savename'];
              $data["name"] = $info['name'];
              $this->ajaxReturn(json_encode($data) ,'JSON');
            }
        }
    }


    //文章视频附件
    function  articleCover(){
        $verifyToken = md5('unique_salt' . $_POST['timestamp']);
        if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     31457280 ;// 设置附件上传大小
            $upload->exts      =     array('flv', 'mp4', 'm4v');// 设置附件上传类型
            $upload->rootPath  =     'CDN/uploaded/article-videos/'; // 设置附件上传根目录
            $upload->autoSub  =      true;
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['Filedata']);
            if(!$info) {// 上传错误提示错误信息
                $back = array('status' =>0, 'msg'=> $upload->getError());
                $this->ajaxReturn($back,'JSON');
            }else{// 上传成功 获取上传文件信息
                $data["status"] = 1;
                $data["pic_path"] = $info['savepath'].$info['savename'];
                $data["name"] = $info['name'];
                $this->ajaxReturn(json_encode($data) ,'JSON');
            }
        }
    }

    //文章图片附件
    function  imgCover(){
        $verifyToken = md5('unique_salt' . $_POST['timestamp']);
        if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','bmp');// 设置附件上传类型
            $upload->rootPath  =     'CDN/uploaded/article/'; // 设置附件上传根目录
            $upload->autoSub  =      true;
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['Filedata']);
            if(!$info) {// 上传错误提示错误信息
                $back = array('status' =>0, 'msg'=> $upload->getError());
                $this->ajaxReturn($back,'JSON');
            }else{// 上传成功 获取上传文件信息
                $data["status"] = 1;
                $data["pic_path"] = $info['savepath'].$info['savename'];
                $data["name"] = $info['name'];
                $this->ajaxReturn(json_encode($data) ,'JSON');
            }
        }
    }

}