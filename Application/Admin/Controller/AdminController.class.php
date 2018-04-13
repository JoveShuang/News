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

        $post = I('post.');

        //保存数据
        if(IS_POST){
            if(!is_string($post['username']) || ($post['username']) === '') {
            // if(!isset($post['username']) || !$post['username']){
                return $this->show(0,'用户名不能为空');
            }
            if(!isset($post['password']) || !$post['password']){
                return show(0,'密码不能为空');
            }
            $post['password'] = getMd5Password($post['password']);
            $admin = D("Admin")->getAdminByUsername($post['username']);
            if($admin && $admin['status']!=-1){
                return show(0,'该用户存在');
            }

            //新增
            $id = D("Admin")->insert($post);
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

    public function personal(){
        $res = $this->getLoginUser();
        $user = D("Admin")->getAdminByAdminId($res['admin_id']);
        $this->assign('vo',$user);
        $this->display();
    }

    public function save(){
            $user = $this->getLoginUser();
            if(!$user){
                return show(0,'用户不存在');
            }

            $data['realname'] = $_POST['realname'];
            $data['email'] = $_POST['email'];

            try{
                $id = D("Admin")->updateByAdminId($user['admin_id'],$data);
                if($id === false){
                    return show(0,'配置失败');
                }
                return show(1,'配置成功');
            }catch(Exception $e){
                return show(0,$e->getMessage());
            }
    }
}