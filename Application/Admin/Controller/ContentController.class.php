<?php
/** 后台文章相关 */
namespace Admin\Controller;
use Think\Controller;
class ContentController extends CommonController{

    public function index(){
        $conds = array();
        $title = $_GET['title'];
        if($title){
            $conds['title'] = $title;
        }
        if($_GET['catid']){
            $conds['catid'] = intval($_GET['catid']);
        }

        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = 2;
        $conds['status'] = array('neq',-1);
        $news = D("News")->getNews($conds,$page,$pageSize);
        $count = D("News")->getNewsCount($conds);

        $res = new \Think\Page($count,$pageSize);
        $pageres = $res->show();

        $data = array();
        if(isset($_REQUEST['type'])){
            $data['type'] = intval($_REQUEST['type']);
            $this->assign('type',$data['type']);
        }else{
            $this->assign('type',-1);
        }


        $this->assign('pageres',$pageres);
        $this->assign('news',$news);

        $this->assign('webSiteMenu',D("Menu")->getBarMenus());
        $this->display();
    }
    public function add(){
        if($_POST){
            if(!isset($_POST['title']) || !$_POST['title']) {
                return show(0,'标题不存在');
            }
            if(!isset($_POST['small_title']) || !$_POST['small_title']) {
                return show(0,'短标题不存在');
            }
            if(!isset($_POST['catid']) || !$_POST['catid']) {
                return show(0,'文章栏目不存在');
            }
            if(!isset($_POST['keywords']) || !$_POST['keywords']) {
                return show(0,'关键字不存在');
            }
            if(!isset($_POST['content']) || !$_POST['content']) {
                return show(0,'content不存在');
            }
            if($_POST['news_id']){
                return $this->save($_POST);
            }

            $newsId = D("News")->insert($_POST);
            if($newsId) {
                $newsContentData['content'] = $_POST['content'];
                $newsContentData['news_id'] = $newsId;
                $cId = D("NewsContent")->insert($newsContentData);
                
                if($cId){
                    return show(1,'新增成功');
                }else{
                    return show(1,'主标插入成功，副表插入失败');
                }
                
            }else{
                return show(0,'新增失败');
            }
        }else{

        
            $websiteMenu = D("Menu")->getBarMenus();
            $titleFontColor = C("TITLE_FONT_COLOR");
            $copyFrom = C("COPY_FROM");
            $this->assign('webSiteMenu',$webSiteMenu);
            $this->assign('titleFontColor',$titleFontColor);
            $this->assign('copyfrom',$copyFrom);
            $this->display();
        }
        
    }
    public function save($data){
            $newsId = $data['news_id'];
            unset($data['news_id']);
            try{
                $id = D("News")->updateById($newsId,$data);
                $newsContentData['content'] = $data['content'];
                $condId = D("NewsContent")->updateNewsById($newsId,$newsContentData);    
                if($id === false || $condId === false){
                    return show(0,'更新失败');
                }else{
                    return show(1,'更新成功');
                }
            }catch(Exception $e){
                return show(0,$e->getMessage());
            }
            
        }
    public function setStatus(){
        try{
            if($_POST){
                $id = $_POST['id'];
                $status = $_POST['status'];
                if(!$id){
                    return show(0,'ID不存在');
                }
                $res = D("News")->updateStatusById($id,$status);
                if($res){
                    return show(1,'操作成功');
                }else{
                    return show(0,'操作失败');
                }
            }
            return show(0,'没有提交的内容');
        }
        catch(Exception $e){
            return show(0,$e->getMessage());
        }
        
    }
    public function listorder(){
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        try{
            if($listorder){
                foreach($listorder as $newsId => $v){
                    //执行更新
                    $id = D("News")->updateNewsListorderById($newsId,$v);
                    if($id === false){
                        $errors[] = $newsId;
                    }
                }
                if($errors){
                    return show(0,'排序失败－'.implode(',',$errors),array('jump_url' => $jumpUrl));
                }
                return show(1,'排序成功',array('jump_url' => $jumpUrl));
            }
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
        return show(0,'排序数据失败',array('jump_url' => $jumpUrl));
        
    }

}