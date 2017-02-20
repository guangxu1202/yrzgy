<?php

namespace Model;
use Think\Model;
class File_downloadModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证文件名称
        array("file_name","require","文件名称必须填写"),
        array('file_name','2,50','文件名称长度必须在2到50之间！',3,'length'),

        //验证自定义排序
        array("custom_sort","require","自定义排序必须填写"),
        array('custom_sort','1,10','自定义排序长度必须在1到10之间！',3,'length'),
        array('custom_sort', '/^[1-9][0-9]*$/' , '自定义排序必须是正整数' , 3, 'regex'),

        //成员照片
        array("file_path","require","选择文件不能为空"),

    );

}