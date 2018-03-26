-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2018 年 03 月 26 日 09:28
-- 服务器版本: 5.6.12-log
-- PHP 版本: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `imooc_singcms`
--
CREATE DATABASE IF NOT EXISTS `imooc_singcms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `imooc_singcms`;

-- --------------------------------------------------------

--
-- 表的结构 `cms_admin`
--

CREATE TABLE IF NOT EXISTS `cms_admin` (
  `admin_id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `lastloginip` varchar(15) DEFAULT '0',
  `lastlogintime` int(10) unsigned DEFAULT '0',
  `email` varchar(40) DEFAULT '',
  `realname` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`admin_id`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `cms_admin`
--

INSERT INTO `cms_admin` (`admin_id`, `username`, `password`, `lastloginip`, `lastlogintime`, `email`, `realname`, `status`) VALUES
(1, 'admin', '55d6496c8ef24f3f78a80c7188c7402b', '0', 1461135752, 'tracywxh0830@126.com', '张三', 1),
(2, 'singwa', 'a8ea3a23aa715c8772dd5b4a981ba6f4', '0', 1458139801, '', '张三', -1),
(3, 'singwa', 'a8ea3a23aa715c8772dd5b4a981ba6f4', '0', 0, '', '', -1),
(4, 'singwa3', '79d4026540fdd95e4a0b627c77e6fa44', '0', 1458144621, '', 'singwa', 1);

-- --------------------------------------------------------

--
-- 表的结构 `cms_menu`
--

CREATE TABLE IF NOT EXISTS `cms_menu` (
  `menu_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `parentid` smallint(6) NOT NULL DEFAULT '0',
  `m` varchar(20) NOT NULL DEFAULT '',
  `c` varchar(20) NOT NULL DEFAULT '',
  `f` varchar(20) NOT NULL DEFAULT '',
  `data` varchar(100) NOT NULL DEFAULT '',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`menu_id`),
  KEY `listorder` (`listorder`),
  KEY `parentid` (`parentid`),
  KEY `module` (`m`,`c`,`f`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `cms_menu`
--

INSERT INTO `cms_menu` (`menu_id`, `name`, `parentid`, `m`, `c`, `f`, `data`, `listorder`, `status`, `type`) VALUES
(1, '菜单管理', 0, 'admin', 'menu', 'index', '', 10, 1, 1),
(2, '文章管理', 0, 'admin', 'Content', 'index', '', 0, -1, 1),
(3, '体育', 0, 'home', 'cat', 'index', '', 3, 1, 0),
(4, '科技', 0, 'home', 'cat ', 'index', '', 0, -1, 0),
(5, '汽车', 0, 'home', 'cat', 'index', '', 0, -1, 0),
(6, '文章管理', 0, 'admin', 'content', 'index', '', 11, 1, 1),
(7, '用户管理', 0, 'admin', 'user', 'index', '', 0, -1, 1),
(8, '科技', 0, 'home', 'cat', 'index', '', 0, 1, 0),
(9, '推荐位管理', 0, 'admin', 'position', 'index', '', 4, 1, 1),
(10, '推荐位内容管理', 0, 'admin', 'positioncontent', 'index', '', 1, 1, 1),
(11, '基本管理', 0, 'admin', 'basic', 'index', '', 13, 1, 1),
(12, '新闻', 0, 'home', 'cat', 'index', '', 0, 1, 0),
(13, '用户管理', 0, 'admin', 'admin', 'index', '', 0, 1, 1),
(14, '菜单管理1', 0, 'admin', 'menu', 'index', '', 0, -1, 1),
(15, '菜单管理', 0, 'admin', 'index', 'index', '', 0, -1, 1),
(16, '菜单管理', 0, 'admin', 'menu', 'index', '', 0, -1, 1),
(17, '菜单管理', 0, 'admin', 'menu', 'index', '', 0, -1, 1),
(18, '菜单管理', 0, 'admin', 'menu', 'index', '', 0, -1, 1),
(19, '菜单管理', 0, 'admin', 'menu', 'index', '', 0, -1, 1),
(20, 'sass', 0, 'ewq', 'zxc', 'qwe', '', 0, -1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `cms_news`
--

CREATE TABLE IF NOT EXISTS `cms_news` (
  `news_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(80) NOT NULL DEFAULT '',
  `small_title` varchar(30) NOT NULL DEFAULT '',
  `title_font_color` varchar(250) DEFAULT NULL COMMENT '标题颜色',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `keywords` char(40) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL COMMENT '文章描述',
  `posids` varchar(250) NOT NULL DEFAULT '',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `copyfrom` varchar(250) DEFAULT NULL COMMENT '来源',
  `username` char(20) NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`news_id`),
  KEY `status` (`status`,`listorder`,`news_id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`news_id`),
  KEY `catid` (`catid`,`status`,`news_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- 转存表中的数据 `cms_news`
--

INSERT INTO `cms_news` (`news_id`, `catid`, `title`, `small_title`, `title_font_color`, `thumb`, `keywords`, `description`, `posids`, `listorder`, `status`, `copyfrom`, `username`, `create_time`, `update_time`, `count`) VALUES
(17, 3, 'test', 'test', '#5674ed', '/upload/2016/03/06/56dbdc0c483af.JPG', 'sss', 'sss', '', 1, -1, '0', 'admin', 1455756856, 0, 0),
(18, 3, '你好ssss', '你好', '#ed568b', '/upload/2016/03/06/56dbdc015e662.JPG', '你好', '你好sssss  ss', '', 2, -1, '3', 'admin', 1455756999, 0, 0),
(19, 8, '1', '11', '#5674ed', '/upload/2016/02/28/56d312b12ccec.png', '1', '1', '', 0, -1, '0', 'admin', 1456673467, 0, 0),
(20, 3, '事实上', '11', '', '/upload/2016/02/28/56d3185781237.png', '1', '11', '', 0, -1, '0', 'admin', 1456674909, 0, 0),
(21, 12, '习近平今日下午出席解放军代表团全体会议', '习近平出席解放军代表团全体会议', '', '/upload/2018/03/23/5ab4b57ccc93c.jpg', '中共中央总书记 国家主席 中央军委主席 习近平', '中共中央总书记', '', 0, 1, '1', 'admin', 1457854896, 0, 16),
(22, 12, '李克强让部长们当第一新闻发言人', '李克强让部长们当第一新闻发言人', '', '/upload/2018/03/23/5ab4b4f835dff.jpg', '李克强  新闻发言人', '部长直接面对媒体回应关切，还能直接读到民情民生民意，而不是看别人的舆情汇报。', '', 0, 1, '0', 'admin', 1457855362, 0, 19),
(23, 3, '重庆美女球迷争芳斗艳', '重庆美女球迷争芳斗艳', '', '/upload/2018/03/26/5ab8afe849f79.png', '重庆美女 球迷 争芳斗艳', '重庆美女球迷争芳斗艳', '', 3, 1, '0', 'admin', 1457855680, 0, 4),
(24, 3, '中超-汪嵩世界波制胜 富力客场1-0力擒泰达', '中超-汪嵩世界波制胜 富力客场1-0力擒泰达', '', '/upload/2018/03/26/5ab8b06ea770f.jpg', '中超 汪嵩世界波  富力客场 1-0力擒泰达', '中超-汪嵩世界波制胜 富力客场1-0力擒泰达', '', 0, 1, '0', 'admin', 1457856460, 0, 13),
(38, 3, 'test', 'test', '#5674ed', '', 'test', 'test', '', 0, -1, '0', 'admin', 1521183389, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cms_news_content`
--

CREATE TABLE IF NOT EXISTS `cms_news_content` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` mediumint(8) unsigned NOT NULL,
  `content` mediumtext NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `cms_news_content`
--

INSERT INTO `cms_news_content` (`id`, `news_id`, `content`, `create_time`, `update_time`) VALUES
(7, 17, '&lt;p&gt;\r\n  gsdggsgsgsgsg\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n    sgsg\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n gsdgsg \r\n&lt;/p&gt;\r\n&lt;p style=&quot;text-align:center;&quot;&gt;\r\n        ggg\r\n&lt;/p&gt;', 1455756856, 0),
(8, 18, '&lt;p&gt;\r\n  你好\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n   我很好dsggfg\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n    &lt;br /&gt;\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n gsgfgdfgd\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n    &lt;br /&gt;\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n &lt;br /&gt;\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n &lt;br /&gt;\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n gggg\r\n&lt;/p&gt;', 1455756999, 0),
(9, 19, '111', 1456673467, 0),
(10, 20, '111', 1456674909, 0),
(11, 21, '', 1457854896, 0),
(12, 22, '', 1457855362, 0),
(13, 23, '', 1457855680, 0),
(14, 24, '', 1457856460, 0),
(15, 38, '', 1521183389, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cms_position`
--

CREATE TABLE IF NOT EXISTS `cms_position` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `description` char(100) DEFAULT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `cms_position`
--

INSERT INTO `cms_position` (`id`, `name`, `status`, `description`, `create_time`, `update_time`) VALUES
(1, '首页大图', -1, '展示首页大图的推荐位1', 1455634352, 0),
(2, '首页大图', 1, '展示首页大图的', 1455634715, 0),
(3, '小图推荐', 1, '小图推荐位', 1456665873, 0),
(4, '首页后侧推荐位', -1, '', 1457248469, 0),
(5, '右侧广告位', 1, '右侧广告位', 1457873143, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cms_position_content`
--

CREATE TABLE IF NOT EXISTS `cms_position_content` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` int(5) unsigned NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) DEFAULT NULL,
  `news_id` mediumint(8) unsigned NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- 转存表中的数据 `cms_position_content`
--

INSERT INTO `cms_position_content` (`id`, `position_id`, `title`, `thumb`, `url`, `news_id`, `listorder`, `status`, `create_time`, `update_time`) VALUES
(39, 3, '测试', '/upload/2018/03/23/5ab4b9fa6f894.gif', '', 0, 4, 1, 0, 0),
(40, 3, '测试1', '/upload/2018/03/23/5ab4bb8c09ade.png', '', 0, 0, 1, 0, 0),
(27, 2, '文章18测试sss', '/upload/2016/03/07/56dcc0158f70e.JPG', '', 18, 0, -1, 1457306930, 0),
(26, 2, 'ss', '/upload/2016/03/07/56dcbce02cab9.JPG', 'http://sina.com.cn', 0, 0, -1, 1457306890, 0),
(25, 3, 'test', '/upload/2016/03/06/56dbdc0c483af.JPG', NULL, 17, 0, -1, 1455756856, 0),
(23, 2, 'test', '/upload/2016/03/06/56dbdc0c483af.JPG', NULL, 17, 1, -1, 1455756856, 0),
(24, 2, '你好ssss', '/upload/2016/03/06/56dbdc015e662.JPG', NULL, 18, 2, -1, 1455756999, 0),
(29, 3, '李克强让部长们当第一新闻发言人', '/upload/2018/03/23/5ab4bbfd9b7e4.png', '', 22, 3, 1, 1457855362, 0),
(30, 3, '重庆美女球迷争芳斗艳', '/upload/2016/03/13/56e51cbd34470.png', NULL, 23, 0, 1, 1457855680, 0),
(31, 3, '中超-汪嵩世界波制胜 富力客场1-0力擒泰达', '/upload/2016/03/13/56e51fc82b13a.png', NULL, 24, 0, 1, 1457856460, 0),
(32, 5, '2015劳伦斯国际体坛精彩瞬间', '/upload/2016/03/13/56e5612d525c3.png', 'http://sports.sina.com.cn/laureus/moment2015/', 0, 0, 1, 1457873220, 0),
(33, 5, 'singwa老师教您如何学PHP', '/upload/2016/03/13/56e56211c68e7.jpg', 'http://t.imooc.com/space/teacher/id/255838', 0, 0, 1, 1457873435, 0),
(34, 2, '重庆美女球迷争芳斗艳', '/upload/2018/03/23/5ab4b8b59dde0.jpg', '', 23, 6, 1, 1457855680, 0),
(35, 2, 'test', '/upload/2018/03/16/5aab6977610d0.jpg', NULL, 36, 0, -1, 1521183103, 0),
(36, 2, '文章28sss', '/upload/2018/03/22/5ab34f6430440.jpg', 'www.shuangstar@icloud.com', 28, 4, 1, 0, 0),
(37, 2, 'test', '/upload/2018/03/16/5aab6977610d0.jpg', NULL, 36, 5, 1, 1521183103, 0),
(38, 2, '习近平今日下午出席解放军代表团全体会议', '/upload/2018/03/23/5ab4b83ab44fe.jpg', '', 21, 0, 1, 1457854896, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
