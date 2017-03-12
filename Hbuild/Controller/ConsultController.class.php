<?php
namespace Hbuild\Controller;
use Think\Controller;
class ConsultController extends CommonController {
//*****************我的咨询管理********************
    //我的咨询列表
    function itemList(){
        $item = M("consult_item");
        $info = $item->field("a.time_number,a.count_times,a.times,b.lx_name,b.lx_phone,b.lx_qq,b.title,a.start_time,a.end_time,a.status,a.teacher_eval,a.pk")->join("as a LEFT JOIN consult as b ON a.order_id = b.pk") ->where("a.teacher_id = ".session("b_id"))->order("a.status asc,a.start_time ASC") ->select();


        $this -> assign("info",$info);
        $this -> display();
    }

    //我的咨询处理
    function itemEdit(){
        $consult_item = M("consult_item");
        if ($consult_item->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else{
            if (!empty($_POST)){
                //实例化
                $user = new \Model\Consult_itemModel();
                //验证数据 Consult_itemModel
                $z = $user -> create();
                if (!$z){
                    //show_bug($user -> getError());
                    $this->error("您录入的数据格式错误！");
                    exit();
                }

                $log["status"] = checkBit(I("post.status"));
                if (checkBit(I('post.status'))){
                    $log["teacher_eval"] = I("post.teacher_eval");
                    $log["teacher_eval_time"] = date("Y-m-d H:i:s");
                }else{
                    $log["teacher_eval"] = null;
                    $log["teacher_eval_time"] = null;
                }
                $user->  where("pk=".I('post.pk'))  ->setField($log);

                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Consult/itemList");

            }else{
                $info = $consult_item->field("a.time_number,b.summary,b.zx_name,b.zx_relation,b.zx_age,b.create_time,a.count_times,a.times,b.lx_name,b.lx_phone,b.lx_qq,b.title,a.start_time,a.end_time,a.status,a.teacher_eval,a.pk")->join("as a LEFT JOIN consult as b ON a.order_id = b.pk") ->where("a.pk = ".I("get.pa"))->select();
                $this -> assign("info",$info);
                $this -> display();
            }

        }
    }
    //咨询编号
    function itemHandle(){
        $consult_item = M("consult_item");
        if ($consult_item->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else{

            $info = $consult_item->field("a.time_number,b.summary,b.zx_name,b.zx_relation,b.zx_age,b.create_time,a.count_times,a.times,b.lx_name,b.lx_phone,b.lx_qq,b.title,a.start_time,a.end_time,a.status,a.teacher_eval,a.pk,b.examine,b.is_pay,b.release_statu")->join("as a LEFT JOIN consult as b ON a.order_id = b.pk") ->where("a.pk = ".I("get.pa"))->select();
            $this -> assign("info",$info);

            $id = $consult_item->field("order_id")->where("pk=".I("get.pa")) ->find();


            $info2 = M("consult_item")->order("times asc")->where("order_id=".$id['order_id'])->select();
            $this -> assign("info2",$info2);

            $this -> display();


        }
    }
}

