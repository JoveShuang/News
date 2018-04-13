<?php if (!defined('THINK_PATH')) exit();?>
<?php
 $config = D("Basic")->select(); $navs = D("Menu")->getBarMenus(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo ($config["title"]); ?></title>
  <meta name="keywords" content="{config.keywords}">
  <meta name="description" content="{config.description}">
  <link rel="stylesheet" href="./Public/css/bootstrap.min.css" type="text/css" />
  <link rel="stylesheet" href="./Public/css/home/main.css" type="text/css" />
</head>
<body>
<header id="header">
  <div class="navbar-inverse">
    <div class="container">
      <div class="navbar-header">
        <a href="./">
          <img src="./Public/images/logo.png" alt="">
        </a>
      </div>
      <ul class="nav navbar-nav navbar-left">
        <li><a href="./" <?php if($result['catId'] == 0): ?>class="curr"<?php endif; ?>>首页</a></li>
        <?php if(is_array($navs)): foreach($navs as $key=>$vo): ?><li><a href="./index.php?c=cat&id=<?php echo ($vo["menu_id"]); ?>" <?php if($vo['menu_id'] == $result['catId']): ?>class="curr"<?php endif; ?>><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; ?>
      </ul>
    </div>
  </div>
</header>
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-9">
                <div class="news-list">
                  <?php if(is_array($result['listNews'])): $i = 0; $__LIST__ = $result['listNews'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl>
                    <dt> <a target="_blank" href="./index.php?c=detail&id=<?php echo ($vo["news_id"]); ?>"><?php echo ($vo["title"]); ?></a></dt>
                    <dd class="news-img">
                       <a target="_blank" href="./index.php?c=detail&id=<?php echo ($vo["news_id"]); ?>"><img src="<?php echo ($vo["thumb"]); ?>" height="200"  width="200" alt="<?php echo ($vo["title"]); ?>"></a>
                    </dd>
                    <dd class="news-intro">
                      <?php echo ($vo["description"]); ?>
                    </dd>
                    <dd class="news-info">
                      <?php echo ($vo["keywords"]); ?> <span><?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?></span> 阅读(0)
                    </dd>
                  </dl><?php endforeach; endif; else: echo "" ;endif; ?>
                  <?php echo ($result['pageres']); ?>
                </div>
            </div>
        </div>
    </div>
</section>