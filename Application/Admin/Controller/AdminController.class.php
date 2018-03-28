<?php
/** 后台Index相关 ... */
namespace Admin\Controller;


/**
*  
*/
class AdminController extends CommonController
{
    
    public function index(){
        $admins = D('Admin')->getAdmins();
        $this->assign('admins',$admins);
        $this->display();
    }

    public function add(){

        //保存数据
        if(IS_POST){

            if(!isset($_POST['username']) || !$_POST['username']){
                return show(0,'用户名不能为空');
            }
            if(!isset($_POST['password']) || !$_POST['password']){
                return show(0,'密码不能为空');
            }
            $_POST['password'] = getMd5Password($_POST['password']);
            $admin = D("Admin")->getAdminByUsername($_POST['username']);
            if($admin && $admin['status']!=-1){
                return show(0,'该用户存在');
            }

            //新增
            $id = D("Admin")->insert($_POST);
            if(!$id){
                return show(0,'新增失败');
            }
            return show(1,'新增成功');
        }
        $this->display();
    }

    public function setStatus(){
        $data = array(
            'admin_id'=>intval($_POST['id']),
            'status'=>intval($_POST['status']),
        );
        return parent::setStatus($_POST,'Admin');
    }
    public function save($data){
            $adminId = $data['admin_id'];
            unset($data['admin_id']);
            try{
                $id = D("Admin")->updateById($adminId,$data);
                if($id === false){
                    return show(0,'更新失败');
                }else{
                    return show(1,'更新成功');
                }
            }catch(Exception $e){
                return show(0,$e->getMessage());
            }
            
        }
}