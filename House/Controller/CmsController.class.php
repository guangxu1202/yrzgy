<?php
namespace House\Controller;
use Think\Controller;
class CmsController extends CommonController
{

//**********网站简介管理************
    //查看
    function introShow(){
        $user = M("introduction");
        $info = $user->order("pk desc")->select();
        $this->assign("info", $info);
        $this->display();
    }

    //修改
    function introEdit(){
        if (!empty($_POST)){
            $model = new \Model\IntroductionModel();
            //验证数据 IntroductionModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据修改
            $data = array(
                'title'=> I('post.title'),
                'summary_title'=>I('post.summary_title'),
                'summary'=>I('post.summary'),
                'content'=>I('post.content'),
                'regenerator'=>session("a_username"),
                'update_time'=>date("Y-m-d H:i:s")
            );
            $model->  where("pk=".I('post.pk'))  ->setField($data);

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Cms/introShow");

        }else{
            $model = M("introduction");
            $info = $model-> order("pk desc") -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }
//**********文章管理************
    //列表
    function articleList(){
        $user = M("article");
        $info = $user -> join("left join category on category.pk = article.category_id") ->field("article.update_time,article.title,category.name,article.author,article.custom_sort,article.is_enable_comment,article.allow_copy,article.creator,article.create_time,article.regenerator,article.is_show,article.pk") ->order("article.is_show desc,custom_sort desc,article.update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //新增
    function articleAdd(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\ArticleModel();
            //验证数据 ArticleModel
            $z = $model -> create();
            if (!$z){
//                show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["update_time"] = date("Y-m-d H:i:s");
            $log["regenerator"] = session("a_username");
            $log["allow_copy"] = checkBit(I('post.allow_copy'));
            $log["article_from"] = I("post.article_from");
            $log["author"] = I("post.author");
            $log["content"] = I("post.content");
            $log["custom_sort"] = I("post.custom_sort");
            $log["is_enable_comment"] = checkBit(I('post.is_enable_comment'));
            $log["keywords"] = I("post.keywords");
            $log["is_show"] = checkBit(I('post.is_show'));
            $log["summary"] = I("post.summary");
            $log["title"] = I("post.title");
            $log["is_title_bold"] = checkBit(I('post.is_title_bold'));
            $log["title_color"] = I("post.title_color");
            $log["category_id"] = I("post.category_id");
            $log["like_count"] = 0;
            $log["browse_count"] = 0;
            $log["unlike_count"] = 0;
            $result = $model->data($log)->add();

            if ($model){
                $id = $result; // 获取数据库写入数据的主键
                //插入关联表数据
                $cars = explode(',',I("post.smodule"));
                $arrlength=count($cars);
                for($x=0;$x<$arrlength;$x++) {
                    $article_model = M("article_model"); // 实例化article_model对象
                    $data['article_id'] = $id;
                    $data['model_id'] = $cars[$x];
                    $article_model->add($data);
                }

                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cms/articleList");
            }else{
                $this->error("数据录入失败！");
                exit();
            }

        }else{
            $m = M("model");
            $n = M("category");
            $info = $m-> order("pk asc") ->where("is_show = 1") -> select();
            $info2 = $n ->order("pk asc") ->where("is_show = 1") -> select();
            $this -> assign("info",$info);
            $this -> assign("info2",$info2);
            $this -> display();
        }
    }

    //修改
    function articleEdit(){
        if (!empty($_POST)){
            $model = new \Model\ArticleModel();
            //验证数据 ArticleModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据修改
            $data = array(
                'allow_copy'=>checkBit(I('post.allow_copy')),
                'article_from'=>I('post.article_from'),
                'author'=>I('post.author'),
                'content'=>I('post.content'),
                'custom_sort'=>I('post.custom_sort'),
                'is_enable_comment'=>checkBit(I('post.is_enable_comment')),
                'keywords'=>I('post.keywords'),
                'is_show'=>checkBit(I('post.is_show')),
                'summary'=>I('post.summary'),
                'category_id'=>I('post.category_id'),
                'is_title_bold'=>checkBit(I('post.is_title_bold')),
                'title'=>I('post.title'),
                'title_color'=>I('post.title_color'),
                'regenerator'=>session("a_username"),
                'update_time'=>date("Y-m-d H:i:s")
            );
            $model->  where("pk=".I('post.pk'))  ->setField($data);

            //删除原有文章模型关联
            $article_model = M("article_model"); // 实例化article_model对象
            $article_model->where('article_id = '.I('post.pk'))->delete();

            //创建新文章模型关联
            $cars = explode(',',I("post.smodule"));
            $arrlength=count($cars);
            for($x=0;$x<$arrlength;$x++) {
                $data1['article_id'] = I('post.pk');
                $data1['model_id'] = $cars[$x];
                $article_model->add($data1);
            }

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Cms/articleList");

        }else{
            $a = M("model");
            $arrid = $a -> join("left join article_model ON model.pk = article_model.model_id") -> field("model.pk") -> where("article_model.article_id =".I('get.pa'))->select();
            $info1 = $a -> where("is_show = 1")->field("pk,name") -> select();

            //关联数组数据整理
            $arr = array();
            for ($c=0;$c<count($arrid);$c++){
                array_push($arr,$arrid[$c]['pk']);
            }

            //数组转换为字符串
            $nb = implode(",", $arr);

            //追加关联数组数据
            for($x=0;$x<count($info1);$x++) {
                if (in_array($info1[$x]['pk'],$arr)){
                    $info1[$x]['check'] = "1";
                }else{
                    $info1[$x]['check'] = "0";
                }
            }

            $m = M("category");
            $info2 = $m-> order("pk asc") ->where("is_show = 1") -> field("pk,name") -> select();
            $model = M("article");
            $info = $model-> where("pk=".I('get.pa')) -> select();
            $cate = $model ->where("pk=".I('get.pa')) -> field("category_id") ->select();

            //追加类别id显示
            for($r=0;$r<count($info2);$r++) {
                if ($info2[$r]['pk'] == $cate[0]['category_id']){
                    $info2[$r]['check'] = "1";
                }else{
                    $info2[$r]['check'] = "0";
                }
            }

            $this -> assign("info",$info);
            $this -> assign("info1",$info1);
            $this -> assign("info2",$info2);
            $this->assign('arr',$nb);
            $this -> display();
        }
    }

    //文章过时与恢复
    function articleLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，模块过时成功！";
            $sa = false;
        }else{
            $str = "恭喜您，模块恢复成功！";
            $sa = true;
        }
        $model = M("article");
        $data = array('is_show'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Cms/articleList");
    }

    //查看
    function articleShow(){
        $user = M("article");
        $info = $user-> where('pk='.I('get.pa'))->select();

        $m = M("category");
        $info1 = $m ->where("pk = ".$info[0]["category_id"]) -> field("name") -> select();

        $n = M("model");
        $info2 = $n -> join("left join article_model on model.pk = article_model.model_id") -> where("article_id=".I('get.pa'))->field("model.name") -> select();

        $this->assign("info", $info);
        $this->assign("category_name",$info1[0]["name"]);
        $this->assign("info2",$info2);
        $this->display();
    }


//**********技术团队管理************
    //列表
    function personList(){
        $user = M("person_intro");
        $info = $user ->order("is_show desc,custom_sort desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //过时与恢复
    function personLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，记录过时成功！";
            $sa = false;
        }else{
            $str = "恭喜您，记录恢复成功！";
            $sa = true;
        }
        $model = M("person_intro");
        $data = array('is_show'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Cms/personList");
    }

    //新增
    function personAdd(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\Person_introModel();
            //验证数据 Person_introModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }


            //上传照片处理
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      'CDN/uploaded/person/'; // 设置附件上传根目录
            $upload->autoSub  =      true; 
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['photo']);
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功 获取上传文件信息
                $photo =  $info['savepath'].$info['savename'];
                $thumb = $info['savepath'].'thumb_'.$info['savename'];

                //缩略图处理
                $image = new \Think\Image();
                $image->open('CDN/uploaded/person/'.$photo);
                // 生成缩略图
                $image->thumb(110, 90)->save('CDN/uploaded/person/'.$thumb);
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["update_time"] = date("Y-m-d H:i:s");
            $log["regenerator"] = session("a_username");
            $log["allow_copy"] = checkBit(I('post.allow_copy'));
            $log["author"] = I("post.author");
            $log["content"] = I("post.content");
            $log["custom_sort"] = I("post.custom_sort");
            $log["keywords"] = I("post.keywords");
            $log["photo_describe"] = I("post.photo_describe");
            $log["is_show"] = true;
            $log["summary"] = I("post.summary");
            $log["title"] = I("post.title");
            $log["browse_count"] = 0;
            $log["photo"] = $photo;
            $log["thumbnail"] = $thumb;
            $model->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Cms/personList");

        }else{
            $this -> display();
        }
    }

    //修改
    function personEdit(){
        if (!empty($_POST)){
            $model = new \Model\Person_introModel();
            //验证数据 Person_introModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            if ($model->find(I("post.pk")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //处理信息
                if ($_FILES['photo']['name']==''){
                    //文件未上传
                }else{
                    //有修改文件上传

                    //上传照片处理
                    $upload = new \Think\Upload();// 实例化上传类
                    $upload->maxSize   =     3145728 ;// 设置附件上传大小
                    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                    $upload->rootPath  =      'CDN/uploaded/person/'; // 设置附件上传根目录
                    $upload->autoSub  =      true;
                    // 上传单个文件
                    $info   =   $upload->uploadOne($_FILES['photo']);
                    if(!$info) {// 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{// 上传成功 获取上传文件信息
                        $photo =  $info['savepath'].$info['savename'];
                        $thumb = $info['savepath'].'thumb_'.$info['savename'];

                        //缩略图处理
                        $image = new \Think\Image();
                        $image->open('CDN/uploaded/person/'.$photo);
                        // 生成缩略图
                        $image->thumb(110, 90)->save('CDN/uploaded/person/'.$thumb);
                        $data["photo"] = $photo;
                        $data["thumbnail"] = $thumb;
                    }
                }

                //数据修改

                $data["update_time"] = date("Y-m-d H:i:s");
                $data["regenerator"] = session("a_username");
                $data["allow_copy"] = checkBit(I('post.allow_copy'));
                $data["author"] = I("post.author");
                $data["content"] = I("post.content");
                $data["custom_sort"] = I("post.custom_sort");
                $data["keywords"] = I("post.keywords");
                $data["photo_describe"] = I("post.photo_describe");
                $data["summary"] = I("post.summary");
                $data["title"] = I("post.title");

                $model->  where("pk=".I('post.pk'))  ->setField($data);
                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cms/personList");
            }


        }else{
            $a = M("person_intro");
            if ($a->find(I("get.pa")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //修改界面展示
                $info = $a -> where("pk=".I("get.pa")) -> select();
                $this -> assign("info",$info);
                $this -> display();
            }
        }
    }

    //查看
    function personShow(){
        $user = M("person_intro");

        if ($user->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else{
            //界面展示
            $info = $user -> where("pk=".I("get.pa")) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }

    }

//**********图片故事管理************
    //列表
    function storyList(){
        $user = M("picture_story");
        $info = $user ->order("is_show desc,custom_sort desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //过时与恢复
    function storyLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，记录过时成功！";
            $sa = false;
        }else{
            $str = "恭喜您，记录恢复成功！";
            $sa = true;
        }
        $model = M("picture_story");
        $data = array('is_show'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Cms/storyList");
    }

    //新增
    function storyAdd(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\Picture_storyModel();
            //验证数据 Picture_storyModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }



            //上传照片处理
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','bmp');// 设置附件上传类型
            $upload->rootPath  =      'CDN/uploaded/story/'; // 设置附件上传根目录
            $upload->autoSub  =      true;
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['cover']);
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功 获取上传文件信息
                $photo =  $info['savepath'].$info['savename'];
                $thumb = $info['savepath'].'thumb_'.$info['savename'];

                //缩略图处理
                $image = new \Think\Image();
                $image->open('CDN/uploaded/story/'.$photo);
                // 生成缩略图
                $image->thumb(217, 127,\Think\Image::IMAGE_THUMB_CENTER)->save('CDN/uploaded/story/'.$thumb);
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["update_time"] = date("Y-m-d H:i:s");
            $log["regenerator"] = session("a_username");
            $log["allow_copy"] = checkBit(I('post.allow_copy'));
            $log["author"] = I("post.author");
            $log["custom_sort"] = I("post.custom_sort");
            $log["keywords"] = I("post.keywords");
            $log["is_show"] = true;
            $log["story_descirbe"] = I("post.story_descirbe");
            $log["title"] = I("post.title");
            $log["browse_count"] = 0;
            $log["cover"] = $photo;
            $log["thumbnail"] = $thumb;
            $result = $model->data($log)->add();

            if ($model){
                $id = $result; // 获取数据库写入数据的主键
                //插入关联表数据


                $arr = array_keys(I('picture'));
                for($x=0;$x<count($arr);$x++) {
                    $index = $arr[$x]; //下标ID
                    $table_model = M("picture_story_item"); // 实例化picture_story_item对象
                    $data['custom_sort'] = I("customSorts")[$index];
                    $data['description'] = I("description")[$index];
                    $data['picture'] = I("picture")[$index];
                    $data['is_show'] = true;
                    $data['thumbnail'] = I("thumbnail")[$index];
                    $data['picture_story_id'] = $id;
                    $table_model->add($data);

                }


                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cms/storyList");
            }else{
                $this->error("数据录入失败！");
                exit();
            }

        }else{
            $this -> display();
        }
    }

    //修改
    function storyEdit(){
        if (!empty($_POST)){
            $model = new \Model\Picture_storyModel();
            //验证数据 Picture_storyModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            if ($model->find(I("post.pk")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //处理信息
                if ($_FILES['cover']['name']==''){
                    //文件未上传
                }else{
                    //有修改文件上传

                    //上传照片处理
                    $upload = new \Think\Upload();// 实例化上传类
                    $upload->maxSize   =     3145728 ;// 设置附件上传大小
                    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','bmp');// 设置附件上传类型
                    $upload->rootPath  =      'CDN/uploaded/story/'; // 设置附件上传根目录
                    $upload->autoSub  =      true;
                    // 上传单个文件
                    $info   =   $upload->uploadOne($_FILES['cover']);
                    if(!$info) {// 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{// 上传成功 获取上传文件信息
                        $photo =  $info['savepath'].$info['savename'];
                        $thumb = $info['savepath'].'thumb_'.$info['savename'];

                        //缩略图处理
                        $image = new \Think\Image();
                        $image->open('CDN/uploaded/story/'.$photo);
                        // 生成缩略图
                        $image->thumb(217, 127,\Think\Image::IMAGE_THUMB_CENTER)->save('CDN/uploaded/story/'.$thumb);

                        $data["cover"] = $photo;
                        $data["thumbnail"] = $thumb;
                    }
                }

                //数据修改

                $data["update_time"] = date("Y-m-d H:i:s");
                $data["regenerator"] = session("a_username");
                $data["allow_copy"] = checkBit(I('post.allow_copy'));
                $data["author"] = I("post.author");
                $data["custom_sort"] = I("post.custom_sort");
                $data["keywords"] = I("post.keywords");
                $data["story_descirbe"] = I("post.story_descirbe");
                $data["title"] = I("post.title");

                $model->  where("pk=".I('post.pk'))  ->setField($data);

                //删除原有关联ID记录
                $table_model = M("picture_story_item"); // 实例化picture_story_item对象
                $table_model->where('picture_story_id = '.I('post.pk'))->delete();

                //添加新关联ID记录
                $arr = array_keys(I('picture'));
                for($x=0;$x<count($arr);$x++) {
                    $index = $arr[$x]; //下标ID
                    $log['custom_sort'] = I("customSorts")[$index];
                    $log['description'] = I("description")[$index];
                    $log['picture'] = I("picture")[$index];
                    $log['is_show'] = true;
                    $log['thumbnail'] = I("thumbnail")[$index];
                    $log['picture_story_id'] = I('post.pk');
                    $table_model->add($log);
                }

                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cms/storyList");
            }

        }else{
            $a = M("picture_story");
            $b = M("picture_story_item");
            if ($a->find(I("get.pa")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //修改界面展示
                $info = $a -> where("pk=".I("get.pa")) -> select();
                $info1 = $b -> where("picture_story_id=".I("get.pa")) -> select();
                $this -> assign("info",$info);
                $this -> assign("info1",$info1);
                $this -> display();
            }
        }
    }

    //查看
    function storyShow(){

        $a = M("picture_story");
        if ($a->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else{
            //界面展示
            $b = M("picture_story_item");
            $info = $a -> where("pk=".I("get.pa")) -> select();
            $info1 = $b -> where("picture_story_id=".I("get.pa")) -> select();
            $this -> assign("info",$info);
            $this -> assign("info1",$info1);
            $this -> display();
        }

    }

//**********资料管理************
    //列表
    function filesList(){
        $user = M("file_download");
        $info = $user ->order("expired desc,custom_sort desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //新增
    function filesAdd(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\File_downloadModel();
            //验证数据 File_downloadModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }


            //上传照片处理
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     21457280 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','ppt','pptx','doc','docx','pdf','rar','zip','xls','xlsx');// 设置附件上传类型
            $upload->rootPath  =      'CDN/uploaded/files/'; // 设置附件上传根目录
            $upload->autoSub  =      true;
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['file_path']);
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功 获取上传文件信息
                $photo =  $info['savepath'].$info['savename'];
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["update_time"] = date("Y-m-d H:i:s");
            $log["regenerator"] = session("a_username");
            $log["custom_sort"] = I("post.custom_sort");
            $log["expired"] = checkBit(I('post.expired'));
            $log["file_name"] = I("post.file_name");
            $log["download_count"] = 0;
            $log["file_path"] = $photo;
            $model->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Cms/filesList");

        }else{
            $this -> display();
        }
    }

    //修改
    function filesEdit(){
        if (!empty($_POST)){
            $model = new \Model\File_downloadModel();
            //验证数据 File_downloadModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            if ($model->find(I("post.pk")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //处理信息
                if ($_FILES['file_path']['name']==''){
                    //文件未上传
                }else{
                    //有修改文件上传

                    //上传照片处理
                    $upload = new \Think\Upload();// 实例化上传类
                    $upload->maxSize   =     21457280 ;// 设置附件上传大小
                    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','ppt','pptx','doc','docx','pdf','rar','zip','xls','xlsx');// 设置附件上传类型
                    $upload->rootPath  =      'CDN/uploaded/files/'; // 设置附件上传根目录
                    $upload->autoSub  =      true;
                    // 上传单个文件
                    $info   =   $upload->uploadOne($_FILES['file_path']);
                    if(!$info) {// 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{// 上传成功 获取上传文件信息
                        $photo =  $info['savepath'].$info['savename'];
                        $data["file_path"] = $photo;
                    }
                }

                //数据修改

                $data["update_time"] = date("Y-m-d H:i:s");
                $data["regenerator"] = session("a_username");
                $data["custom_sort"] = I("post.custom_sort");
                $data["expired"] = checkBit(I('post.expired'));
                $data["file_name"] = I("post.file_name");
                $model->  where("pk=".I('post.pk'))  ->setField($data);
                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cms/filesList");
            }


        }else{
            $a = M("file_download");
            if ($a->find(I("get.pa")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //修改界面展示
                $info = $a -> where("pk=".I("get.pa")) -> select();
                $this -> assign("info",$info);
                $this -> display();
            }
        }
    }

    //过时与恢复
    function filesLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，记录过时成功！";
            $sa = false;
        }else{
            $str = "恭喜您，记录恢复成功！";
            $sa = true;
        }
        $model = M("file_download");
        $data = array('expired'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Cms/filesList");
    }

    //删除
    function filesDel(){
        //删除记录
        $model = M("file_download");


        if ($model->find(I("get.pa")) == null){
            $this -> error("操作失败");
            exit();
        }else{
            $model->where('pk = '.I('get.pa'))->delete();
            //操作成功
            $this->success("记录删除成功！",__MODULE__."/Cms/filesList");
        }


    }

//**********投票管理************
    //列表
    function voteList(){
        $user = M("vote");
        $info = $user -> order("is_show desc,custom_sort desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //新增
    function voteAdd(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\VoteModel();
            //验证数据 VoteModel
            $z = $model -> create();
            if (!$z){
//                show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["update_time"] = date("Y-m-d H:i:s");
            $log["regenerator"] = session("a_username");
            $log["is_radio"] = checkBit(I('post.is_radio'));
            if (checkBit(I('post.is_radio')) == true){
                $log["maximum"] = 1;
            }else{
                $log["maximum"] =I("post.maximum");
            }
            $log["title"] = I("post.title");
            $log["custom_sort"] = I("post.custom_sort");
            //$log["deadline"] = I("post.deadline");
            $log["description"] = I("post.description");
            $log["is_show"] = true;
            $result = $model->data($log)->add();

            if ($model){
                $id = $result; // 获取数据库写入数据的主键

                //插入关联表数据
                $table_model = M("vote_item"); // 实例化picture_story_item对象
                $arr = array_keys(I('content'));
                for($x=0;$x<count($arr);$x++) {
                    $data['content'] = I("content")[$arr[$x]];
                    $data['vote_number'] = 0;
                    $data['vote_id'] = $id;
                    $table_model->add($data);
                }

                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cms/voteList");
            }else{
                $this->error("数据录入失败！");
                exit();
            }

        }else{
            $this -> display();
        }
    }

    //修改
    function voteEdit(){
        if (!empty($_POST)){
            $model = new \Model\VoteModel();
            //验证数据 VoteModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            if ($model->find(I("post.pk")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{

                //数据修改


                $data["update_time"] = date("Y-m-d H:i:s");
                $data["regenerator"] = session("a_username");
                $data["is_radio"] = checkBit(I('post.is_radio'));
                if (checkBit(I('post.is_radio')) == true){
                    $data["maximum"] = 1;
                }else{
                    $data["maximum"] =I("post.maximum");
                }
                $data["title"] = I("post.title");
                $data["custom_sort"] = I("post.custom_sort");
                $data["description"] = I("post.description");

                $model->  where("pk=".I('post.pk'))  ->setField($data);

                //删除原有关联ID记录
                $table_model = M("vote_item"); // 实例化vote_item对象
                $table_model->where('vote_id = '.I('post.pk'))->delete();


                //添加新关联ID记录
                $arr = array_keys(I('content'));
                for($x=0;$x<count($arr);$x++) {
                    $log['content'] = I("content")[$arr[$x]];
                    $log['vote_number'] = I('post.vote_number')[$arr[$x]];
                    $log['vote_id'] = I('post.pk');
                    $table_model->add($log);
                }

                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cms/voteList");
            }

        }else{
            $a = M("vote");
            $b = M("vote_item");
            if ($a->find(I("get.pa")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //修改界面展示
                $info = $a -> where("pk=".I("get.pa")) -> select();
                $info1 = $b -> where("vote_id=".I("get.pa")) -> select();
                $this -> assign("info",$info);
                $this -> assign("info1",$info1);
                $this -> display();
            }
        }
    }

    //过时与恢复
    function voteLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，过时成功！";
            $sa = false;
        }else{
            $str = "恭喜您，恢复成功！";
            $sa = true;
        }
        $model = M("vote");
        $data = array('is_show'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);
        //操作成功
        $this->success($str,__MODULE__."/Cms/voteList");
    }

    //查看
    function voteShow(){

        $a = M("vote");
        if ($a->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else{
            //界面展示
            $b = M("vote_item");
            $info = $a -> where("pk=".I("get.pa")) -> select();
            $info1 = $b -> where("vote_id=".I("get.pa")) -> select();
            $time = $b-> where("vote_id=".I("get.pa")) ->sum("vote_number");

            $info2 = array();
            $c = 0;
            foreach ($info1 as $v){
                $info2[$c]["content"] = $v["content"];
                $info2[$c]["num"] = $v["vote_number"];
                $info2[$c]["per"] = round($v["vote_number"]/$time*100,2);
                $c++;
            }

            //show_bug($info2);

            $this -> assign("info",$info);
            $this -> assign("info1",$info2);
            $this -> assign("times",$time);
            $this -> display();
        }

    }

}