<?php
/** 推荐位内容相关 */
namespace Admin\Controller;
use Think\Controller;
class PositioncontentController extends CommonController{
    public function index(){
        $positions = D("Position")->getNormalPositions();
        //获取推荐位内容
        $data['status'] = array('neq',-1);
        if($_GET['title']){
            $data['title'] = trim($_GET['title']);
            $this->assign('title',$data['title']);
        }
        $data['position_id'] = $_GET['position_id'] ? intval($_GET['position_id']) : $positions[0]['id'];
        $contents = D("PositionContent")->select($data);
        $this->assign('positions',$positions);
        $this->assign('contents',$contents);
        $this->assign('positionId',$data['position_id']);
        $this->display();
    }
    public function add(){
        if($_POST){
            if(!isset($_POST['position_id'])|| !$_POST['position_id']){
                return show(0,'推荐位ID不能为空');
            }
            if(!isset($_POST['title'])|| !$_POST['title']){
                return show(0,'推荐位标题不能为空');
            }
            if(!isset($_POST['url']) && !$_POST['news_id']){
                return show(0,'url和news_id不能同时为空');
            }
            if(!isset($_POST['thumb'])|| !$_POST['thumb']){
                if($_POST['news_id']){
                    $res = D("News")->find($_POST['news_id']);
                    if($res && is_array($res)){
                        $_POST['thumb'] = $res['thumb'];
                    }
                }else{
                    return show(0,'图片不能为空');
    
                }
            }
            try{
                $id = D("PositionContent")->insert($_POST);
                if($id){
                    return show(1,'新增成功');
                }
                return show(0,'新增失败');
            }catch(Exception $e){
                return show(0,$e->getMessage());
            }
        }else{
            $positions = D("Position")->getNormalPositions();
            $this->assign('positions',$positions);
            $this->display();
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
                $res = D("PositionContent")->updateStatusById($id,$status);
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
}