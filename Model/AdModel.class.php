<?php

namespace Model;
use Think\Model;
class AdModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证广告名称
        array("ad_name","require","广告名称必须填写"),
        array('ad_name','2,50','广告名称长度必须在2到50之间！',3,'length'),
        //array('title', '/^[0-9a-zA-Z\x{4e00}-\x{9fa5}]+$/u' , '标题由中英文和数字组成' , 3, 'regex'),

        //验证广告链接
        array('link','2,100','广告链接长度必须在2到100之间！',3,'length'),

        //验证描述内容
        array('ad_describe','0,200','广告描述内容长度必须在0到200之间！',3,'length'),

        //验证自定义排序
        array("custom_sort","require","自定义排序必须填写"),
        array('custom_sort','1,10','自定义排序长度必须在1到10之间！',3,'length'),
        array('custom_sort', '/^[1-9][0-9]*$/' , '自定义排序必须是正整数' , 3, 'regex'),

        //广告图片
        array("ad_path","require","请上传广告图片"),

    );

}