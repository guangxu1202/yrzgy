<?php

namespace Model;
use Think\Model;
class CommentModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证标题
        array('title','0,100','文章标题长度必须在0到100之间！',3,'length'),

        //验证内容
        array('content','0,500','内容长度必须在0到500之间！',3,'length'),

    );

}