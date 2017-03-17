<?php

namespace Model;
use Think\Model;
class Video_orderModel extends Model{

    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(
        //验证留言
        array('comment','0,200','标题长度必须在0到200之间！',3,'length'),
        
        //验证价格
        array("price","require","价格必须填写"),
        array('price', '/^([1-9]\d*|0)(\.\d{1,2})?$/' , '请输入正确的价格，小数后最多两位' , 3, 'regex'),
        
        //所属分类
        array("video_category_id","require","所属分类不能为空"),
        array('video_category_id', '/^[1-9][0-9]*$/' , '所属分类必须是正整数' , 3, 'regex'),

       //所属会员
        array("member_id","require","会员不能为空"),
        array('member_id', '/^[1-9][0-9]*$/' , '会员必须是正整数' , 3, 'regex'),



    );

}