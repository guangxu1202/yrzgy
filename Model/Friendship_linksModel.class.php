<?php

namespace Model;
use Think\Model;
class Friendship_linksModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证链接名称
        array("name","require","链接名称不能为空"),
        array('name','0,50','链接名称长度必须在2到50之间！',3,'length'),


        //验证链接地址
        array("url","require","链接地址不能为空"),
        array('url','0,200','链接名称长度必须在2到200之间！',3,'length'),


        //验证链接描述
        array('description','0,50','链接描述长度必须在0到50之间！',3,'length'),

        //验证自定义排序
        array("custom_sort","require","自定义排序必须填写"),
        array('custom_sort','1,10','自定义排序长度必须在1到10之间！',3,'length'),
        array('custom_sort', '/^[1-9][0-9]*$/' , '自定义排序必须是正整数' , 3, 'regex'),



    );

}