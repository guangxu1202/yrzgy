<?php

namespace Model;
use Think\Model;
class ModelModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证模块名称
        array("name","require","模块名称必须填写"),
        array('name','2,20','模块名称长度必须在2到20之间！',3,'length'),
        //array('name', '/^[0-9a-zA-Z\x{4e00}-\x{9fa5}]+$/u' , '模块名称由中英文和数字组成' , 3, 'regex'),
        array('name','','模块名称已经存在！',0,'unique',1),
    );

}