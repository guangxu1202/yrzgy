<?php

namespace Model;
use Think\Model;
class MiscellaneousModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证标题
        array("title","require","标题不能为空"),
        array('title','0,50','标题长度必须在2到50之间！',3,'length'),

    );

}