<?php

namespace Model;
use Think\Model;
class Customer_serviceModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        array('email','0,80','邮箱长度必须在0到80之间！',3,'length'),
        array('msn','0,80','MSN长度必须在0到80之间！',3,'length'),
        array('name','0,50','姓名长度必须在0到50之间！',3,'length'),
        array('telephone','0,12','电话长度必须在0到12之间！',3,'length'),
        array('qq','0,12','QQ长度必须在0到12之间！',3,'length'),

    );

}