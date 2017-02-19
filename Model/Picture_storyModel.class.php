<?php

namespace Model;
use Think\Model;
class Picture_storyModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证标题
        array("title","require","标题必须填写"),
        array('title','2,90','标题长度必须在2到90之间！',3,'length'),

        //验证摘要简介
        array('summary','0,200','故事简述长度必须在0到200之间！',3,'length'),

        //验证自定义排序
        array("custom_sort","require","自定义排序必须填写"),
        array('custom_sort','1,10','自定义排序长度必须在1到10之间！',3,'length'),
        array('custom_sort', '/^[1-9][0-9]*$/' , '自定义排序必须是正整数' , 3, 'regex'),


        //封面图片
        array("photo","require","请上传封面图片"),


    );

}