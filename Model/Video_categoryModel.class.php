<?php

namespace Model;
use Think\Model;
class Video_categoryModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证视频分类名称
        array("name","require","视频分类名称必须填写"),
        array('name','2,40','视频分类名称长度必须在2到40之间！',3,'length'),
        array('name', '/^[0-9a-zA-Z\x{4e00}-\x{9fa5}]+$/u' , '模块名称由中英文和数字组成' , 3, 'regex'),
        array('name','','视频分类名称已经存在！',0,'unique',1),

        //验证价格
        array("price","require","价格必须填写"),
        array('price', '/^([1-9]\d*|0)(\.\d{1,2})?$/' , '请输入正确的价格，小数后最多两位' , 3, 'regex'),

        //验证自定义排序
        array("custom_sort","require","自定义排序必须填写"),
        array('custom_sort','1,10','自定义排序长度必须在1到10之间！',3,'length'),
        array('custom_sort', '/^[1-9][0-9]*$/' , '自定义排序必须是正整数' , 3, 'regex'),

    );

}