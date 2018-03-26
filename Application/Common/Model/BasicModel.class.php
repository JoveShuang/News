<?php
namespace Common\Model;
/**
 * 基本设置
 */

class BasicModel{
    
    // public function __construct(){
    //     parent::__construct();
    //     $this->_db = M('Basic');
    // }
    public function save($data = array()){
        
        if(!$data){
            throw_exception('没有提交的数据');
            
        }

        $id = F('basic_web_config',$data);
        return $id;
    }

    public function select(){
        return F("basic_web_config");
    }
}