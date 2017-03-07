<?php

namespace Model;
use Think\Model;
class TeacherModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证地址
        array('address','0,100','地址长度必须在0到100之间！',3,'length'),

        //验证手机号
        array("phone","require","手机号必须填写"),
        array('phone','0,15','手机号长度必须在0到15之间！',3,'length'),

        //验证简介
        array('summary','0,300','简介长度必须在0到300之间！',3,'length'),

        //验证QQ
        array("qq","require","QQ必须填写"),
        array('qq','0,12','QQ长度必须在0到12之间！',3,'length'),

        //验证真实姓名
        array('real_name','0,50','真实姓名长度必须在0到50之间！',3,'length'),
        array("real_name","require","真实姓名必须填写"),

        //验证用户名
        array('username','0,50','用户名长度必须在0到50之间！',3,'length'),
        array('username','','用户名已经存在！',0,'unique',1),
        array("username","require","用户名必须填写"),

        //验证自定义排序
        array("custom_sort","require","自定义排序必须填写"),
        array('custom_sort','1,10','自定义排序长度必须在1到10之间！',3,'length'),
        array('custom_sort', '/^[1-9][0-9]*$/' , '自定义排序必须是正整数' , 3, 'regex'),

        //验证密码
        array("password","require","密码必须填写"),


    );

}