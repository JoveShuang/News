<?php
/** 推荐位相关 */
namespace Admin\Controller;
use Think\Controller;
class PositionController extends CommonController{
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
    public function setStatus(){
        try{
            if($_POST){
                $id = $_POST['id'];
                $status = $_POST['status'];
                if(!$id){
                    return show(0,'ID不存在');
                }
                $res = D("Position")->updateStatusById($id,$status);
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