<?php

namespace Model;
use Think\Model;
class Consult_itemModel extends Model{
    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(

        //咨询师评价
        array('teacher_eval','0,900','咨询师评价长度必须在0到900之间！',3,'length'),

        //用户评价
        array('member_eval','0,900','用户评价长度必须在0到900之间！',3,'length'),

    );

}