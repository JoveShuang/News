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
        
        $this->assign('positions',$positions);
        $this->assign('positionId',$data['position_id']);
        $this->display();
    }
    public function save($data){
        $id = $data['id'];
        unset($data['id']);
        try {
            $resId = D("PositionContent")->updateById($id,$data);

            if($resId === false){
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        } catch (Exception $e) {
            return show(0,$e->getMessage());
        }
    }
    public function setStatus(){
        $data = array(
            'id' =>intval($_POST['id']),
            'status' => intval($_POST['status']),

        );
       return parent::setStatus($data,'Position');
        
    }
}