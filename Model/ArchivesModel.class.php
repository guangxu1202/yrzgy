<?php

namespace Model;
use Think\Model;
class ArchivesModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证标题
        array("title","require","研修生姓名必须填写"),
        array('title','0,90','研修生姓名长度必须在0到90之间！',3,'length'),

        //验证摘要简介
        array('summary','0,200','摘要长度必须在0到200之间！',3,'length'),

        //验证自定义排序
        array("custom_sort","require","自定义排序必须填写"),
        array('custom_sort','1,10','自定义排序长度必须在1到10之间！',3,'length'),
        array('custom_sort', '/^[1-9][0-9]*$/' , '自定义排序必须是正整数' , 3, 'regex'),


        //所属分类
        array("cate_id","require","所属分类至少选择一个"),

        //关键词
        array('keywords','0,200','关键词长度必须在0到200之间！',3,'length'),
        
    );

}