<?php
/** 推荐位内容相关 */
namespace Admin\Controller;
use Think\Controller;
class PositioncontentController extends CommonController{
    public function index(){
        $positions = D("Position")->getNormalPositions();//获取推荐位里面的内容
        $data['status'] = array('neq',-1);
        if($_GET['title']){
            $data['title'] = trim($_GET['title']);
            $this->assign('title',$data['title']);
        }
        $data['position_id'] = $_GET['position_id'] ? intval($_GET['position_id']) : 1;
        $contents = D("PositionContent")->select($data);
        $this->assign('positions',$positions);
        $this->assign('contents',$contents);
        $this->assign('positionId',$data['position_id']);
        $this->display();
    }
    public function add(){
        $positions = D("Position")->getNormalPositions();
        $this->assign('positions',$positions);
        $this->display();
    }
    
}