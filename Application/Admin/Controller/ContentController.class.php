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
        $positions = D("Position")->getNormalPositions();
        $data = array();
        if(isset($_REQUEST['type'])){
            $data['type'] = intval($_REQUEST['type']);
            $this->assign('type',$data['type']);
        }else{
            $this->assign('type',-1);
        }


        $this->assign('pageres',$pageres);
        $this->assign('news',$news);
        $this->assign('positions',$positions);
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

        
            $webSiteMenu = D("Menu")->getBarMenus();
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
        $data = array(
            'id' =>intval($_POST['id']),
            'status' => intval($_POST['status']),

        );
        return parent::setStatus($data,'News');
    }
    public function listorder(){
        return parent::listorder("News");
    }
    public function push(){
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $positionId = intval($_POST['position_id']);
        $newsId = $_POST['push'];

        if(!$newsId || !is_array($newsId)){
            return show(0,'请选择推荐的文章id进行推荐');
        }

        if(!$positionId){
            return show(0,'没有选择推荐位');
        }
        try{
            $news = D("News")->getNewsByNewsIdIn($newsId);
            if(!$news){
                return show(0,'没有相关内容');
            }

            foreach ($news as $new) {
                $data = array(

                    'position_id' => $positionId,
                    'title' => $new['title'],
                    'thumb' => $new['thumb'],
                    'news_id' => $new['news_id'],
                    'status' => 1,
                    'create_time' => $new['create_time'],
                );
                $position = D("PositionContent")->insert($data);
            }
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
        return show(1,'推荐成功',array('jump_url'=>$jumpUrl));
    }

}