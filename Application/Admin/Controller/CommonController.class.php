<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * use Common\Model 这块可以不需要使用，框架默认会加载里面的内容
 */
class CommonController extends Controller {


	public function __construct() {
		
		parent::__construct();
		$this->_init();
	}
	/**
	 * 初始化
	 * @return
	 */
	private function _init() {
		// 如果已经登录
		$isLogin = $this->isLogin();
		if(!$isLogin) {
			// 跳转到登录页面
			$this->redirect('/admin.php?c=login');
		}
	}

	/**
	 * 获取登录用户信息
	 * @return array
	 */
	public function getLoginUser() {
		return session("adminUser");
	}

	/**
	 * 判定是否登录
	 * @return boolean 
	 */
	public function isLogin() {
		$user = $this->getLoginUser();
		if($user && is_array($user)) {
			return true;
		}

		return false;
	}

	public function edit(){
		$newsId = $_GET['id'];
		if(!newsId ){
			//执行跳转
			$this->redirect('/admin.php?c=content');
		}
		$news = D("News")->find($newsId);
		if(!$news){
			$this->redirect('/admin.php?c=content');
		}
		$newsContent = D("NewsContent")->find($newsId);
		if($newsContent){
			$news['content'] = $newsContent['content'];
		}

		$webSiteMenu = D("Menu")->getBarMenus();
		$this->assign('webSiteMenu',$webSiteMenu);
		$this->assign('titleFontColor',C("TITLE_FONT_COLOR"));
		$this->assign('copyfrom',C("COPY_FROM"));

		$this->assign('news',$news);
		$this->display();
	}

	

}