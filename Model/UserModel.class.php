<?php

namespace Model;
use Think\Model;
class UserModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证用户名
        array("username","require","用户名必须填写"),
        array('username','4,20','登录名长度必须在4到20之间！',3,'length'),
        array('username', '/^[a-zA-Z0-9_\.]+$/' , '用户名由数字字母下划线和.组成' , 3, 'regex'),
        array('username','','用户名已经存在！',0,'unique',1),

        //验证真实姓名
        array("real_name","require","真实姓名必须填写"),
        array('real_name','1,40','登录名长度必须在1到40之间！',3,'length'),
       

        //验证密码
        array("password","require","密码必须填写"),
        array('password','6,30','用户名长度必须在6到30之间！',3,'length'),
        array('password', '/^[a-zA-Z0-9_\.]+$/' , '用户名由数字字母下划线和.组成' , 3, 'regex'),


    );

}