<?php
namespace Home\Controller;
use Think\Controller;
// include './Application/Common/Common/function.php';
class IndexController extends CommonController {
    public function index($type = ''){
        //获取排行
        
        $rankNews = $this->getRank();
        
        // 获取首页大图数据
        $topPicNews = D("PositionContent")->select(array('status'=>1,'position_id'=>2),1);
        //首页3小图推荐
        $topSmailNews = D("PositionContent")->select(array('status'=>1,'position_id'=>3),3);

        $listNews = D("News")->select(array('status'=>1,'thumb'=>array('neq','')),30);

        $advNews = D("PositionContent")->select(array('status'=>1,'position_id'=>5),2);

        $this->assign('result',array(
            'topPicNews' => $topPicNews,
            'topSmailNews' => $topSmailNews,
            'ListNews' => $listNews,
            'advNews' => $advNews,
            'rankNews' => $rankNews,
            'catId' => 0,
        ));
        /**
         * 
         * 生成页面静态化
         */
        if($type == 'buildHtml'){
            $this->buildHtml('index',HTML_PATH,'Index/index');
        }else{ 
            $this->display("Index/index");
        }
    }

    public function build_html(){
        $this->index('buildHtml');
        return show(1,'首页缓存生成成功');
    }

    public function crontab_build_html(){
        $this->index('buildHtml');
    }

    public function getCount(){
        if(!$_POST){
            return show(0,'没有任何内容');
        }

        $newsIds = array_unique($_POST);

        try{
            $list = D("News")->getNewsByNewsIdIn($newsIds);
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
        if(!$list){
            return show(0,'notdata');

        }

        $data=array();
        foreach ($list as $k => $v) {
            $data[$v['news_id']] = $v['count']; 
        }
        return show(1,'success',$data);
    }

    public function test(){

        D("News","Logic")->echotest();

        // var_dump(U('Index/index',array('a'=>'nj')));

        // try {
            
        // } catch (Exception $e) {
            
        // }

    }
}