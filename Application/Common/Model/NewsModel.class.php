<?php
namespace Common\Model;
use Think\Model;

/**
 * 文章内容model操作
 * 
 */
class NewsModel extends Model{
    private $_db = '';

    public function __construct(){
        $this->_db = M('News');
    }

    public function insert($data = array()){
        if(!is_array($data) || !$data){
            return 0;
        }

        $data['create_time'] = time();
        $data['username'] = getLoginUsername();
        return $this->_db->add($data);
    }
    public function getNews($data,$page,$pageSize=10){
        $conditions = $data;
        if(isset($data['title']) && $data['title']){
            $conditions['title'] = array('like','%'.$data['title'].'%');
        }
        if(isset($data['catid']) && $data['catid']){
            $conditions['catid'] = intval($data['catid']);
        }

        $offset = ($page - 1) * $pageSize;
        $list = $this->_db->where($conditions)
        ->order('listorder desc,news_id desc')
        ->limit($offset,$pageSize)
        ->select();

        return $list;
    }

    public function getNewsCount($data = array()){
        $conditions = $data;
        if(isset($data['title']) && $data['title']){
            $conditions['title'] = array('like','%'.$data['title'].'%');
        }
        if(isset($data['catid']) && $data['catid']){
            $conditions['catid'] = intval($data['catid']);
        }
        return $this->_db->where($conditions)->count();
    }

    public function find($id){
        $data = $this->_db->where('news_id='.$id)->find();
        return $data;
    }

    public function updateById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)){
            throw_exception("更新数据不合法");
        }

        return $this->_db->where('news_id='.$id)->save($data);
    }
    public function updateStatusById($id,$status){
        if(!is_numeric($status)){
            throw_exception('status不能为非数字');
        }
        if(!$id || !is_numeric($id)){
            throw_exception('id不合法');
        }
        $data['status'] = $status;

        return $this->_db->where('news_id='.$id)->save($data);
    }
    public function updateNewsListorderById($id,$listorder){
        if(!$id || !is_numeric($id)){
            throw_exception('Id不合法');
        }
        $data = array('listorder'=>intval($listorder));
        return $this->_db->where('news_id='.$id)->save($data);
    }
}