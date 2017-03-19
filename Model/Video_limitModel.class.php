<?php

namespace Model;
use Think\Model;
class Video_limitModel extends Model{

    //获取全部验证错误
    protected $patchValidate = true;

    //表单验证
    protected $_validate = array(

        //验证限制次数
        array('limit_count', '/^[1-9][0-9]*$/' , '限制次数必须是正整数' , 3, 'regex'),

        //验证分类id
        array('video_category_id', '/^[1-9][0-9]*$/' , '分类id必须是正整数' , 3, 'regex'),

        //验证视频id
        array('video_id', '/^[1-9][0-9]*$/' , '视频id必须是正整数' , 3, 'regex'),

        //验证会员id
        array('member_id', '/^[1-9][0-9]*$/' , '会员id必须是正整数' , 3, 'regex'),
        
    );

}