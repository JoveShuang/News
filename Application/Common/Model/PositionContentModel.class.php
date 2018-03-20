<?php
namespace Common\Model;
use Think\Model;

/**
 * 推荐位wmodel操作
 * 
 */
class PositionContentModel extends Model{
    private $_db = '';
    public function __construct(){
        $this->_db = M('PositionContent');
    }
    public function insert($data = array()){
        if(!$data || !is_array($data)){
            return 0;
        }
        return $this->_db->add($data);
    }
    public function select($data = array()){

        $conditions = $data;
        $list = $this->_db->where($conditions)->order('id')->select();
        return $list;
    }
    public function find($id){
        $data = $this->_db->where('id='.$id)->find();
        return $data;
    }
    public function updateById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)){
            throw_exception("更新数据不合法");
        }

        return $this->_db->where('id='.$id)->save($data);
    }
    public function getNormalPositions(){
        $conditions = array('status'=>1);
        $list = $this->_db->where($conditions)->order('id')->select();
        return $list;
    }
}