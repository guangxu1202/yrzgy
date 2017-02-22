<?php

namespace Model;
use Think\Model;
class Model_imageModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(

        //验证链接
        array('link','0,100','链接长度必须在0到100之间！',3,'length'),

        //验证描述内容
        array('img_describe','0,200','描述内容长度必须在0到200之间！',3,'length'),

    );

}