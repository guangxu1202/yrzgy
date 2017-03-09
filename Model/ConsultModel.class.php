<?php

namespace Model;
use Think\Model;
class ConsultModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(

        //验证联系人姓名
        array('lx_name','0,50','联系人姓名长度必须在0到50之间！',3,'length'),
        array("lx_name","require","联系人姓名必须填写"),

        //验证咨询人姓名
        array('zx_name','0,50','咨询人姓名长度必须在0到50之间！',3,'length'),
        array("zx_name","require","咨询人姓名必须填写"),

        //验证联系人手机
        array('mobile','0,15','联系人手机长度必须在0到15之间！',3,'length'),
        array("mobile","require","联系人手机必须填写"),

        //验证QQ
        array('qq','0,15','联系人QQ长度必须在0到15之间！',3,'length'),
        array("mobile","require","联系人QQ必须填写"),

        //验证地址
        array('address','0,100','地址长度必须在0到100之间！',3,'length'),

        //咨询人关系
        array('zx_relation','0,20','咨询人关系长度必须在0到20之间！',3,'length'),

        //验证gender
        array('zx_gender','number','性别应为纯数字！'),

        //咨询人年龄
        array('zx_age','0,20','咨询人年龄长度必须在0到20之间！',3,'length'),
        array("zx_age","require","咨询人年龄必须填写"),

        //验证描述
        array('summary','0,1000','描述长度必须在0到1000之间！',3,'length'),

        //验证标题
        array('title','0,100','标题长度必须在0到100之间！',3,'length'),
        array("title","require","标题必须填写"),

    );

}