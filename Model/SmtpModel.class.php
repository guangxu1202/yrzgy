<?php

namespace Model;
use Think\Model;
class SmtpModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证发送人
        array("sender","require","发送人必须填写"),
        array('sender','0,50','发送人长度必须在0到50之间！',3,'length'),

        //验证主机名称
        array("host_name","require","主机名称必须填写"),
        array('host_name','0,80','主机名称长度必须在0到80之间！',3,'length'),

        //验证email
        array("email","require","email必须填写"),
        array('email','0,80','email长度必须在0到80之间！',3,'length'),

        //验证邮箱密码
        array("password","require","邮箱密码必须填写"),
        array('password','0,30','邮箱密码长度必须在0到30之间！',3,'length'),

        //验证自定义排序
        array("custom_sort","require","自定义排序必须填写"),
        array('custom_sort','1,10','自定义排序长度必须在1到10之间！',3,'length'),
        array('custom_sort', '/^[1-9][0-9]*$/' , '自定义排序必须是正整数' , 3, 'regex'),
        
    );

}