<?php
namespace Common\Model;
use Think\Model;

class AdminModel extends Model {

    private $_db = '';
    public function __construct(){
        parent::__construct();
        $this->_db = M('Admin');
    }
    public function getAdminByUsername($username){
        $ret = $this->_db->where('username="'.$username.'"')->find();
        return $ret;
    }
    public function getAdminByAdminId($adminId=0){
        $res = $this->_db->where('admin_id='.$adminId)->find();
        return $res;
    }

    public function updateByAdminId($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)){
            throw_exception('更in的数据不合法');
        }
        return $this->_db->where('admin_id='.$id)->save($data);//
    }
    public function insert($data = array()){
        if(!$data || !is_array($data)){
            return 0;
        }
        return $this->_db->add($data);
    }
    public function getAdmins(){
        $data = array(
            'status' => array('neq',-1),
        );
        return $this->_db->where('admin_id')->order('admin_id asc')->select();
    }
    /**
     * 通过id更新的状态
     *@param $id
     *@param $status
     *@return bool
     */
    public function updateById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)){
            throw_exception("更新数据不合法");
        }

        return $this->_db->where('admin_id='.$id)->save($data);
    }
    public function updateStatusById($id,$status){
        if(!is_numeric($status)){
            throw_exception("status不能为非数字");
        }
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        $data['status'] = $status;
        return $this->_db->where('admin_id='.$id)->save($data);

    }


}