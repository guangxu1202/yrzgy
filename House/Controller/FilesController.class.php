<?php
namespace House\Controller;
use Think\Controller;
class FilesController extends CommonController {
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

}