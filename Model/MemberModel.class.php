<?php

namespace Model;
use Think\Model;
class MemberModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证地址
        array('address','0,100','地址长度必须在0到100之间！',3,'length'),

        //验证email
        array('email','0,80','email长度必须在0到80之间！',3,'length'),
        array('email','','email已经存在！',0,'unique',1),
        array("email","require","email必须填写"),

        //验证gender
        array('gender','number','性别应为纯数字！'),

        //验证手机号
        array('mobile','0,15','手机号长度必须在0到15之间！',3,'length'),

        //验证msn
        array('msn','0,50','msn长度必须在0到50之间！',3,'length'),

        //验证昵称
        array('nickname','0,50','昵称长度必须在0到50之间！',3,'length'),
        array("nickname","require","昵称必须填写"),

        //验证单位
        array('organization','0,100','单位长度必须在0到100之间！',3,'length'),

        //验证简介
        array('summary','0,1000','简介长度必须在0到1000之间！',3,'length'),


        //验证QQ
        array('qq','0,12','QQ长度必须在0到12之间！',3,'length'),

        //验证真实姓名
        array('real_name','0,50','真实姓名长度必须在0到50之间！',3,'length'),
        array("real_name","require","真实姓名必须填写"),

        //验证固定电话
        array('telephone','0,15','固定电话长度必须在0到15之间！',3,'length'),

        //验证用户名
        array('username','0,20','用户名长度必须在0到20之间！',3,'length'),
        array('username','','用户名已经存在！',0,'unique',1),
        array("username","require","用户名必须填写"),

        //验证密码
        array("password","require","密码必须填写"),


    );

}