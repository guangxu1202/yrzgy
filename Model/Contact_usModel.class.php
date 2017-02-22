<?php

namespace Model;
use Think\Model;
class Contact_usModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(

        array('address','0,100','地址长度必须在0到100之间！',3,'length'),
        array('bus','0,100','公交线路长度必须在0到100之间！',3,'length'),
        array('email','0,80','邮箱长度必须在0到80之间！',3,'length'),
        array('fax','0,12','传真长度必须在0到12之间！',3,'length'),
        array('mobile','0,12','手机号长度必须在0到12之间！',3,'length'),
        array('organization','0,50','机构名称长度必须在0到50之间！',3,'length'),
        array('qq_group','0,12','QQ群长度必须在0到12之间！',3,'length'),
        array('telephone','0,12','电话长度必须在0到12之间！',3,'length'),
        array('zip','0,6','邮编长度必须在0到6之间！',3,'length'),


    );

}