<?php

namespace Model;
use Think\Model;
class IntroductionModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证标题
        array("title","require","标题必须填写"),
        array('title','2,40','标题长度必须在2到40之间！',3,'length'),
        //array('title', '/^[0-9a-zA-Z\x{4e00}-\x{9fa5}]+$/u' , '标题由中英文和数字组成' , 3, 'regex'),

        //验证摘要标题
        array("summary_title","require","摘要标题必须填写"),
        array('summary_title','2,20','摘要标题长度必须在2到20之间！',3,'length'),
        array('summary_title', '/^[0-9a-zA-Z\x{4e00}-\x{9fa5}]+$/u' , '摘要标题由中英文和数字组成' , 3, 'regex'),

        //验证摘要简介
        array("summary","require","摘要简介必须填写"),
        array('summary','2,200','摘要简介长度必须在2到200之间！',3,'length'),

    );

}