-- --------------------------------------------------------
-- 主機:                           172.16.1.229
-- 服務器版本:                        5.0.84-log - Source distribution
-- 服務器操作系統:                      redhat-linux-gnu
-- HeidiSQL 版本:                  9.0.0.4865
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 導出 youth_song 的資料庫結構
CREATE DATABASE IF NOT EXISTS `youth_song` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `youth_song`;


-- 導出  表 youth_song.member 結構
CREATE TABLE IF NOT EXISTS `member` (
  `member_no` varchar(36) NOT NULL COMMENT '會員序號',
  `account` varchar(100) NOT NULL COMMENT '帳號',
  `password` varchar(100) default NULL COMMENT '密碼',
  `name` varchar(50) NOT NULL COMMENT '會員姓名',
  `area` tinyint(1) NOT NULL default '1' COMMENT '所屬地區1為台灣2為中國大陸',
  `sex` tinyint(1) NOT NULL default '1' COMMENT '0為女生1為男生',
  `uid` varchar(20) NOT NULL COMMENT '身份證字號',
  `birthday` date NOT NULL COMMENT '出生年月日',
  `phone` varchar(50) NOT NULL COMMENT '行動電話',
  `address` varchar(100) NOT NULL COMMENT '通訊地址',
  `flag` tinyint(1) NOT NULL default '1' COMMENT '狀態0為停用1為啟用9為刪除',
  `verify_code` varchar(10) NOT NULL COMMENT '信箱驗證碼',
  `start_time` datetime default NULL COMMENT '帳號啟用時間',
  `created` datetime NOT NULL COMMENT '建立時間',
  `modified` datetime NOT NULL COMMENT '修改時間',
  `user_ip` varchar(30) NOT NULL COMMENT '註冊時的ip',
  `user_agent` varchar(200) NOT NULL COMMENT '搜尋引擎',
  `session_key` varchar(64) NOT NULL,
  `fb_name` varchar(255) default NULL COMMENT '臉書名稱',
  `from_fb` tinyint(1) NOT NULL default '0' COMMENT '是否使用fb帳號',
  `is_sync` tinyint(1) NOT NULL default '0' COMMENT '是否同步0未同步1已同步',
  PRIMARY KEY  (`member_no`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `uid` (`uid`),
  UNIQUE KEY `account` (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前台基本會員';

-- 正在導出表  youth_song.member 的資料：~11 rows (大約)
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` (`member_no`, `account`, `password`, `name`, `area`, `sex`, `uid`, `birthday`, `phone`, `address`, `flag`, `verify_code`, `start_time`, `created`, `modified`, `user_ip`, `user_agent`, `session_key`, `fb_name`, `from_fb`, `is_sync`) VALUES
	('2', 'jiexun.lu@gmail.com', '3003dfebd41f46562c1e61f8a7a9c106', '測試', 1, 1, 'F128153788', '1991-01-01', '0927689934', '台北市內湖區內湖路一段91巷17號', 1, 'QdbkgXPKyv', '2016-12-22 14:06:44', '2016-12-22 14:06:38', '2016-12-22 14:06:38', '172.16.50.16', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36', 'df5492d6a10194b95117cc42946ac62c', NULL, 0, 0),
	('3', 'jerry.he@iwant-in.net', 'b09b7146913661abe5f2c40ca94aff49', '王小明', 1, 1, 'F127783541', '1990-06-03', '0939268299', '台北市內湖區內湖路1段17巷91號', 1, 'Fuktn2AR8w', '2017-03-09 10:52:58', '2017-03-09 10:52:28', '2017-04-10 15:55:39', '172.16.58.8', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', '11b8aff30879ef02d89270b59785f9ef', NULL, 0, 0),
	('4', 'test@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '測試0313', 1, 1, 'F127783542', '1990-06-03', '0939123456', '台北市內湖區內湖路1段17巷91號', 1, 'THtgB9yffp', '2017-03-13 15:59:07', '2017-03-13 13:56:36', '2017-03-13 14:18:30', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', '19gfc3fpvkj1e2um2pu6evbms1', NULL, 0, 0),
	('460A3BB0-BEC9-9722-9CD8-D8785897EC55', 'd98191151@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '測試', 1, 1, 'F127783540', '1983-01-19', '0939268295', '台北市內湖區內湖路1段17巷91號', 1, 'y6g9NxFAnE', '2017-04-10 17:28:03', '2017-04-10 17:19:48', '2017-04-10 17:19:48', '172.16.58.8', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36', '0faf162cb9c44c2f89e970499d4db3f3', '測試', 0, 0),
	('5', 'gt9060319_a@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '何嘉興', 1, 1, 'F127783543', '1990-06-03', '0939268298', '台北市內湖區內湖路1段17巷91號', 1, 'V2SabYncW2', '2017-03-13 16:09:23', '2017-03-13 16:08:53', '2017-03-13 17:27:44', '172.16.58.8', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', 'b477310217894c15f55c9c97dfbc2e21', NULL, 0, 0),
	('701E1F1A-6233-A2C4-9B64-B36C249F9594', 'winnie_791124@yahoo.com.tw', 'e10adc3949ba59abbe56e057f20f883e', '謝宣圖', 1, 0, 'H224008106', '1990-11-24', '0922487955', '台北市內湖區內湖路一段91巷17號3樓之5', 1, 'U3s5D5vuQ8', '2017-04-07 19:46:49', '2017-04-07 19:45:23', '2017-04-07 19:45:23', '172.16.52.5', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', '549195e63bc057c94c13483f3d55db2f', 'Annakova Orange', 0, 0),
	('714A65D2-E87A-370B-B033-FAD4073251DB', 'blue790603@yahoo.com.tw', 'd41d8cd98f00b204e9800998ecf8427e', '測試名稱', 1, 1, 'F127783545', '1990-06-03', '0939268294', '台北市內湖區內湖路1段17巷91號', 1, 'ySThrhqD4x', '2017-03-29 16:16:50', '2017-03-29 16:16:09', '2017-03-29 16:16:09', '172.16.58.8', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36', 'b477310217894c15f55c9c97dfbc2e21', '測試', 1, 0),
	('81BEC187-6B77-6D96-AF64-296187B16525', 'bill.yeh@iwant-in.net', 'd41d8cd98f00b204e9800998ecf8427e', '晶亮牙醫', 1, 1, 'Y120479949', '1999-02-15', '0965389374', '台北市內湖路一段91巷17號3樓-5', 1, 'tMSvX5u3b9', '2017-04-10 15:56:00', '2017-04-10 14:05:38', '2017-04-10 14:05:38', '172.16.50.15', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:52.0) Gecko/20100101 Firefox/52.0', '07eb4d913a04e7d864f8e9611276cc7c', 'yaesjgahk', 1, 0),
	('CAF52BE0-3832-407B-C7CB-5EF912B4A347', 'b1195029@hotmail.com', '275b689ff84d8f2a9ffb896f55fe2ee6', '郭小橘', 1, 0, 'B221984710', '1986-01-23', '0988181521', '台北市內湖區內湖路一段91巷17號3樓之5', 1, 'bvAHNnRFWX', '2017-04-10 14:59:42', '2017-04-10 14:56:29', '2017-04-10 16:44:06', '172.16.52.5', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36', '66505211dfc7d21f5ba38701f8c50de9', 'Annakova Orange', 1, 0),
	('F0585AC9-1833-B093-FA2C-BBD63935BEF0', 'mianjhih.fang@iwant-in.net', 'e9bb4f6a6c5f32a1ad65afe01994c9a7', '方勉之', 1, 1, 'f127633894', '1990-01-01', '0983080568', '台北市內湖區內湖路一段91巷', 1, '8YZv2ZBX7B', '2017-04-10 12:29:53', '2017-04-10 12:29:36', '2017-04-10 12:29:36', '172.16.57.4', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Mobile Safari/537.36', 'cc086e66766518e9ab3fb76dc111329f', '方勉之', 0, 0),
	('F0AF32E6-29AD-F244-F09B-42EF6051F953', 'gt9060319@yahoo.com.tw', 'd41d8cd98f00b204e9800998ecf8427e', '測試名稱', 1, 1, 'A144790053', '1986-02-19', '0939268293', '台北市內湖區內湖路1段17巷91號', 1, 'R7t9wEtyxD', '2017-04-10 11:21:46', '2017-04-10 11:21:31', '2017-04-10 11:21:31', '172.16.58.8', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36', '989d1abdb61aa571422bb2a4212321fb', '測試', 1, 0);
/*!40000 ALTER TABLE `member` ENABLE KEYS */;


-- 導出  表 youth_song.music_tool 結構
CREATE TABLE IF NOT EXISTS `music_tool` (
  `sign_no` varchar(36) NOT NULL COMMENT '報名表序號',
  `tool_no` int(3) NOT NULL COMMENT '樂器序號（相對應的樂器名稱寫在程式內）',
  `level` tinyint(1) NOT NULL COMMENT '等級0精通1普通2略懂',
  `is_sync` tinyint(1) NOT NULL default '0' COMMENT '是否同步0未同步1已同步'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='擅長樂器明細表';

-- 正在導出表  youth_song.music_tool 的資料：~9 rows (大約)
/*!40000 ALTER TABLE `music_tool` DISABLE KEYS */;
INSERT INTO `music_tool` (`sign_no`, `tool_no`, `level`, `is_sync`) VALUES
	('1', 1, 1, 0),
	('1', 3, 0, 0),
	('2', 1, 1, 0),
	('2', 3, 0, 0),
	('5586A837-14E8-B0D5-AC29-448EC8A79FE8', 1, 1, 0),
	('5586A837-14E8-B0D5-AC29-448EC8A79FE8', 6, 2, 0),
	('7068E093-174A-7FF2-8DC3-0F36E54A6077', 1, 1, 0),
	('7068E093-174A-7FF2-8DC3-0F36E54A6077', 7, 0, 0),
	('6AFDEF5E-8F3D-AD12-75B4-D03370A67F71', 1, 1, 0);
/*!40000 ALTER TABLE `music_tool` ENABLE KEYS */;


-- 導出  表 youth_song.news 結構
CREATE TABLE IF NOT EXISTS `news` (
  `news_no` varchar(36) NOT NULL COMMENT '序號',
  `area` tinyint(3) NOT NULL default '0' COMMENT '顯示地區0兩者都顯示1台灣2中國',
  `type` varchar(36) NOT NULL default '1' COMMENT '最新消息類別',
  `title` varchar(50) NOT NULL COMMENT '標題',
  `publish_date` date NOT NULL COMMENT '發佈日期',
  `file_name` varchar(50) default NULL COMMENT '原始檔名',
  `file_file` varchar(100) default NULL COMMENT '上傳檔名',
  `flag` tinyint(1) NOT NULL default '0' COMMENT '狀態',
  `description` text COMMENT '描述',
  `created` datetime NOT NULL COMMENT '建立日期',
  `modified` datetime NOT NULL COMMENT '修改日期',
  `is_sync` tinyint(1) NOT NULL default '0' COMMENT '是否同步0未同步1已同步',
  PRIMARY KEY  (`news_no`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='最新消息';

-- 正在導出表  youth_song.news 的資料：6 rows
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` (`news_no`, `area`, `type`, `title`, `publish_date`, `file_name`, `file_file`, `flag`, `description`, `created`, `modified`, `is_sync`) VALUES
	('5', 0, '1', '青春頌原創金曲大選~2017唱出青春！', '2017-03-24', NULL, NULL, 1, '青春！人生最璀璨耀眼的時刻！不管處於何種年代，總有膾炙人口的流行金曲迴盪在耳邊，朗朗上口、字字句句充滿生命力，成為大家共同的美好回憶。\r\n2017年《青春頌─兩岸青年原創金曲大選》邀請兩岸熱愛歌唱、勇於創作的青年，透過原創音樂站上舞台，為自己發聲，用音符揮灑青春的光采！徵集正面積極、真摯情感、貼近校園生活的原創音樂作品，不限曲風，舉凡搖滾、民謠、嘻哈、電音、爵士等，只要符合本活動主題，皆歡迎報名參加。\r\n特別邀請知名音樂創作人、作詞大師陳樂融擔任評審團召集人，並於活動期間辦理網路票選，廣為宣傳青年原創音樂。最後年度十強作品將由專業的音樂製作團隊破天荒免費打造歌曲EP！\r\n十三億人的聆聽期待，通往華人原創音樂最高榮耀，等你來挑戰！\r\n敬請鎖定活動官網獲得最新資訊！', '2016-12-14 10:02:27', '2017-03-24 09:45:15', 0),
	('22', 0, '7', '青春頌測試0313', '2017-03-13', NULL, NULL, 0, '測試青春頌最新消息', '2017-03-13 15:37:42', '2017-03-23 11:38:00', 0),
	('17', 0, '6', '圖片flickr測試', '2016-12-22', NULL, NULL, 0, '<p> </p>\r\n<p><iframe src="https://www.youtube.com/embed/yG4PKwPCKz0" frameborder="0" width="350" height="197"></iframe></p>\r\n<p><a title="page" href="https://www.flickr.com/photos/yehchge1029/30958618664/in/dateposted-public/" data-flickr-embed="true"><img src="https://c1.staticflickr.com/1/265/30958618664_5146a05800.jpg" alt="page" width="300" height="300" /></a></p>\r\n<script charset="utf-8" type="text/javascript" src="http://embedr.flickr.com/assets/client-code.js"></script>', '2016-12-21 10:20:57', '2016-12-22 18:44:37', 0),
	('18', 1, '2', '青春頌徵選作品Test_台灣', '2016-12-22', NULL, NULL, 0, '<p>&nbsp;</p>\r\n<p>2017年<strong>《青春頌─兩岸青年流行音樂創作大賽》</strong>邀請兩岸熱愛歌唱、勇於創作的青年，透過原創音樂站上舞台，為自己發聲，用音符揮灑青春的光采！徵集正面積極、真摯情感、貼近校園生活的原創音樂作品，不限曲風，舉凡搖滾、民謠、嘻哈、電音、爵士等，只要符合本活動主題，皆歡迎報名參加。</p>\r\n<p>第一季徵選時間：2017/2/8~2017/3/19日 23:59截止</p>\r\n<p><iframe src="http://www.youtube.com/embed/Z6z2N5u9TCM" frameborder="0" width="425" height="350"></iframe></p>\r\n<p><span><br /></span></p>\r\n<p><a title="iCD NO1.4 專輯冊" href="https://www.flickr.com/photos/iwant-music/11110455644/in/album-72157638163585795/" data-flickr-embed="true"><img src="https://c5.staticflickr.com/4/3819/11110455644_78329128cd.jpg" alt="iCD NO1.4 專輯冊" width="425" height="241" /></a></p>\r\n<script charset="utf-8" type="text/javascript" src="http://embedr.flickr.com/assets/client-code.js"></script>\r\n<p><span>郭旻溫柔細緻的嗓音配上充滿中國風的編曲</span><br /><span>彷彿一下子將時間凝結</span><br /><span>聽到的看到的不再是車水馬龍</span><br /><span>只剩下最珍貴的聲音</span><br /><span>提醒不斷前進的我們</span><br /><span>用心體會每一天</span></p>', '2016-12-22 15:28:11', '2016-12-27 14:33:13', 0),
	('21', 0, '5', '測試', '2017-03-13', NULL, NULL, 9, '0313測試', '2017-03-13 15:33:28', '2017-03-13 15:33:28', 0),
	('20', 2, '2', '青春頌徵選作品Test_大陸', '2016-12-22', NULL, NULL, 0, '<p><a title="iCD 馬曉安《如果我是一張卡片》Cover" href="https://www.flickr.com/photos/iwant-music/14852418673/in/datetaken-public/" data-flickr-embed="true"><img style="vertical-align: top;" src="https://c2.staticflickr.com/3/2903/14852418673_3471b15b58.jpg" alt="iCD 馬曉安《如果我是一張卡片》Cover" width="200" height="200" /></a></p>\r\n<script charset="utf-8" type="text/javascript" src="http://embedr.flickr.com/assets/client-code.js"></script>\r\n<p>2017年《青春頌─兩岸青年流行音樂創作大賽》邀請兩岸熱愛歌唱、勇於創作的青年，透過原創音樂站上舞台，為自己發聲，用音符揮灑青春的光采！徵集正面積極、真摯情感、貼近校園生活的原創音樂作品，不限曲風，舉凡搖滾、民謠、嘻哈、電音、爵士等，只要符合本活動主題，皆歡迎報名參加。</p>\r\n<p> </p>\r\n<p><iframe src="http://player.youku.com/embed/XNjQ2Mjc2ODU2" frameborder="0" width="425" height="415"></iframe></p>', '2016-12-22 17:10:16', '2017-03-13 15:34:52', 0);
/*!40000 ALTER TABLE `news` ENABLE KEYS */;


-- 導出  表 youth_song.news_type 結構
CREATE TABLE IF NOT EXISTS `news_type` (
  `type_no` varchar(36) NOT NULL COMMENT '類別序號',
  `type_name` varchar(50) NOT NULL COMMENT '類別名稱',
  `flag` tinyint(3) NOT NULL default '1' COMMENT '狀態0停用1啟用',
  `created` datetime NOT NULL COMMENT '建立時間',
  `modified` datetime NOT NULL COMMENT '修改時間',
  `is_sync` tinyint(1) NOT NULL default '0' COMMENT '是否同步0未同步1已同步',
  PRIMARY KEY  (`type_no`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='最新消息類別管理';

-- 正在導出表  youth_song.news_type 的資料：6 rows
/*!40000 ALTER TABLE `news_type` DISABLE KEYS */;
INSERT INTO `news_type` (`type_no`, `type_name`, `flag`, `created`, `modified`, `is_sync`) VALUES
	('1', '賽程', 1, '2016-12-20 15:05:27', '2016-12-22 15:13:01', 0),
	('2', '徵選', 1, '2016-12-20 15:05:36', '2016-12-22 15:13:17', 0),
	('3', '入圍', 1, '2016-12-20 15:05:45', '2016-12-22 15:13:55', 0),
	('5', '票選', 1, '2016-12-22 15:14:23', '2016-12-22 15:14:23', 0),
	('6', '成績', 1, '2016-12-22 15:15:45', '2016-12-22 15:15:45', 0),
	('7', '異動', 1, '2016-12-22 15:16:11', '2016-12-22 15:16:11', 0);
/*!40000 ALTER TABLE `news_type` ENABLE KEYS */;


-- 導出  表 youth_song.sessions 結構
CREATE TABLE IF NOT EXISTS `sessions` (
  `sesskey` varchar(64) NOT NULL default '',
  `expiry` datetime NOT NULL,
  `expireref` varchar(250) default '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `sessdata` longtext,
  PRIMARY KEY  (`sesskey`),
  KEY `sess2_expiry` (`expiry`),
  KEY `sess2_expireref` (`expireref`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 正在導出表  youth_song.sessions 的資料：2 rows
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`sesskey`, `expiry`, `expireref`, `created`, `modified`, `sessdata`) VALUES
	('1d4865357a03ae90ebe5890d85dafef3', '2017-04-11 15:58:21', '', '2017-04-11 14:10:07', '2017-04-11 14:58:21', 'oCurrentUser|O:5:"CUser":18:{s:10:"\0*\0iUserNo";s:1:"1";s:5:"sName";s:9:"管理者";s:11:"\0*\0sAccount";s:5:"admin";s:16:"\0CUser\0sPassword";s:32:"21232f297a57a5a743894a0e4a801fc3";s:6:"sEmail";s:0:"";s:4:"sTel";s:0:"";s:4:"sFax";s:0:"";s:7:"sMobile";s:0:"";s:7:"sAddrId";i:0;s:5:"sAddr";s:0:"";s:7:"created";N;s:8:"modified";N;s:8:"sCreated";s:19:"2016-11-21 14:16:01";s:9:"sModified";s:19:"2016-11-21 14:16:01";s:7:"bStatus";s:1:"1";s:12:"\0*\0__iUserNo";s:1:"1";s:14:"\0*\0__oLastUser";N;s:9:"user_name";s:9:"管理者";}user_name|s:9:"管理者";user_no|s:1:"1";user_password|s:5:"admin";s_sign_key|s:9:"管理者";s_sign_terms|s:9:"user_name";'),
	('07eb4d913a04e7d864f8e9611276cc7c', '2017-04-11 15:59:52', '', '2017-04-11 14:58:20', '2017-04-11 14:59:52', 'oCurrentUser|O:5:"CUser":18:{s:10:"\0*\0iUserNo";s:1:"1";s:5:"sName";s:9:"管理者";s:11:"\0*\0sAccount";s:5:"admin";s:16:"\0CUser\0sPassword";s:32:"21232f297a57a5a743894a0e4a801fc3";s:6:"sEmail";s:0:"";s:4:"sTel";s:0:"";s:4:"sFax";s:0:"";s:7:"sMobile";s:0:"";s:7:"sAddrId";i:0;s:5:"sAddr";s:0:"";s:7:"created";N;s:8:"modified";N;s:8:"sCreated";s:19:"2016-11-21 14:16:01";s:9:"sModified";s:19:"2016-11-21 14:16:01";s:7:"bStatus";s:1:"1";s:12:"\0*\0__iUserNo";s:1:"1";s:14:"\0*\0__oLastUser";N;s:9:"user_name";s:9:"管理者";}user_name|s:9:"管理者";user_no|s:1:"1";user_password|s:5:"admin";gCharSet|s:1:"1";'),
	('ec750fb528fba5ea7a153bd643edcdcf', '2017-04-11 15:59:26', '', '2017-04-11 14:59:18', '2017-04-11 14:59:26', 'gCharSet|s:1:"1";token|s:32:"60fba9771ca0ab17d9e8b9cade6d04ba";'),
	('c4f82b0a4aacfebd026a6826af12f883', '2017-04-11 15:59:53', '', '2017-04-11 14:59:30', '2017-04-11 14:59:53', 'gCharSet|s:1:"1";token|s:32:"84c1cad674e9c7e6352dfc8d67ab45ee";'),
	('318c6deb165c37c5b3229f5563eefe9f', '2017-04-11 15:59:49', '', '2017-04-11 14:59:49', '2017-04-11 14:59:49', 'gCharSet|s:1:"1";');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;


-- 導出  表 youth_song.sign 結構
CREATE TABLE IF NOT EXISTS `sign` (
  `sign_no` varchar(36) NOT NULL COMMENT '報名表序號',
  `member_no` varchar(36) NOT NULL COMMENT '會員編號',
  `member_name` varchar(50) default NULL COMMENT '會員名稱',
  `type` int(3) NOT NULL COMMENT '報名期別',
  `sign_type` int(3) default NULL COMMENT '報名組別1學生組2青年組',
  `team_name` varchar(50) default NULL COMMENT '團隊名稱（個人免填）',
  `school` varchar(50) NOT NULL COMMENT '學校名稱',
  `department` varchar(50) default NULL COMMENT '系所名稱',
  `management` tinyint(1) NOT NULL default '0' COMMENT '有無經紀約0無1有',
  `specialty` varchar(100) default NULL COMMENT '演藝專長序號 相關對照中文放在程式內',
  `music_tool` varchar(100) default NULL COMMENT '擅長樂器',
  `experience` text NOT NULL COMMENT '演藝/參賽經歷',
  `img_file_file_1` varchar(128) default NULL COMMENT '個人照片 上傳檔名 1',
  `img_file_name_1` varchar(128) default NULL COMMENT '個人照片 原始檔名 1',
  `img_file_file_2` varchar(128) default NULL COMMENT '個人照片 上傳檔名 2',
  `img_file_name_2` varchar(128) default NULL COMMENT '個人照片 原始檔名 2',
  `img_file_file_3` varchar(128) default NULL COMMENT '個人照片 上傳檔名 3',
  `img_file_name_3` varchar(128) default NULL COMMENT '個人照片 原始檔名 3',
  `flag` tinyint(1) NOT NULL default '1' COMMENT '狀態',
  `created` datetime NOT NULL COMMENT '建立時間',
  `modified` datetime NOT NULL COMMENT '修改時間',
  `is_sync` tinyint(1) NOT NULL default '0' COMMENT '是否同步0未同步1已同步',
  PRIMARY KEY  (`sign_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='報名投稿主檔';

-- 正在導出表  youth_song.sign 的資料：~7 rows (大約)
/*!40000 ALTER TABLE `sign` DISABLE KEYS */;
INSERT INTO `sign` (`sign_no`, `member_no`, `member_name`, `type`, `sign_type`, `team_name`, `school`, `department`, `management`, `specialty`, `music_tool`, `experience`, `img_file_file_1`, `img_file_name_1`, `img_file_file_2`, `img_file_name_2`, `img_file_file_3`, `img_file_name_3`, `flag`, `created`, `modified`, `is_sync`) VALUES
	('0F0C1DFB-6936-C9EB-CAFF-E51914700D23', '460A3BB0-BEC9-9722-9CD8-D8785897EC55', '測試', 1, 0, 'aaa', 'aaa', 'aaa', 1, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2017-04-10 18:33:30', '2017-04-10 18:33:30', 0),
	('3CA6A456-295D-3E02-9363-5875E10B950D', '81BEC187-6B77-6D96-AF64-296187B16525', '晶亮牙醫', 1, 0, 'ftyutuftyfutyfut', 'klhjkl', 'hjklhjklh', 0, 'jkhljk', 'ljkhljkhl', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2017-04-10 14:07:38', '2017-04-10 14:07:38', 0),
	('506296E5-6809-382E-BAED-889AAAAF7E21', 'CAF52BE0-3832-407B-C7CB-5EF912B4A347', '郭小橘', 1, 0, 'test', 'yest', 'zfsfs', 0, 'safaf', 'afsadg', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2017-04-10 17:34:36', '2017-04-10 17:34:36', 0),
	('70A44B79-F396-AA6A-0DFF-C8B204E1408F', '701E1F1A-6233-A2C4-9B64-B36C249F9594', '謝宣圖', 1, 0, '', '', '', 0, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2017-04-07 19:49:40', '2017-04-07 19:49:40', 0),
	('A8A27B91-6B9A-1C79-8357-76544B9328BF', '714A65D2-E87A-370B-B033-FAD4073251DB', '測試名稱', 1, 0, '', '銀河', '資訊', 1, '唱歌', '鋼琴', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2017-04-11 14:33:15', '2017-04-11 14:33:15', 0),
	('CBD73CE5-4D34-4DA8-D051-9A300730A7B0', 'F0AF32E6-29AD-F244-F09B-42EF6051F953', '測試名稱', 1, 0, 'aaa', 'aaa', 'aaa', 0, 'aaa', '鋼琴', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2017-04-10 14:20:16', '2017-04-10 14:20:16', 0),
	('F93C8A90-3C41-F2E1-D934-4353C1B9623E', '3', '王小明', 1, 0, '', '台大', '資訊', 0, '唱歌', '鋼琴', '沒有', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2017-04-10 10:07:41', '2017-04-10 10:07:41', 0);
/*!40000 ALTER TABLE `sign` ENABLE KEYS */;


-- 導出  表 youth_song.talent_verify 結構
CREATE TABLE IF NOT EXISTS `talent_verify` (
  `ta_mobile` varchar(36) NOT NULL COMMENT '手機',
  `verify_code` varchar(10) NOT NULL COMMENT '認證碼',
  `created` datetime NOT NULL COMMENT '建立日期',
  `is_sync` tinyint(1) NOT NULL default '0' COMMENT '是否同步0未同步1已同步'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='手機認證';

-- 正在導出表  youth_song.talent_verify 的資料：2 rows
/*!40000 ALTER TABLE `talent_verify` DISABLE KEYS */;
INSERT INTO `talent_verify` (`ta_mobile`, `verify_code`, `created`, `is_sync`) VALUES
	('0939268295', '938048', '2017-04-10 12:14:32', 0),
	('0988181521', '112080', '2017-04-10 14:54:11', 0);
/*!40000 ALTER TABLE `talent_verify` ENABLE KEYS */;


-- 導出  表 youth_song.upload_file 結構
CREATE TABLE IF NOT EXISTS `upload_file` (
  `up_no` varchar(36) NOT NULL COMMENT '流水號',
  `works_no` varchar(36) NOT NULL COMMENT '作品序號',
  `up_type` tinyint(1) default NULL COMMENT '上傳類型1作品歌詞2作品歌曲',
  `file_file` varchar(128) NOT NULL default '' COMMENT '上傳檔名',
  `file_name` varchar(128) NOT NULL default '' COMMENT '原始檔名',
  `file_type` varchar(150) NOT NULL default '' COMMENT '檔案類型',
  `file_size` int(10) NOT NULL default '0' COMMENT '檔案大小',
  `created` datetime NOT NULL COMMENT '建立日期',
  `modified` datetime NOT NULL COMMENT '編修日期',
  `is_sync` tinyint(1) NOT NULL default '0' COMMENT '是否同步0未同步1已同步',
  PRIMARY KEY  (`up_no`),
  KEY `ta_id` (`works_no`,`up_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='檔案上傳';

-- 正在導出表  youth_song.upload_file 的資料：~15 rows (大約)
/*!40000 ALTER TABLE `upload_file` DISABLE KEYS */;
INSERT INTO `upload_file` (`up_no`, `works_no`, `up_type`, `file_file`, `file_name`, `file_type`, `file_size`, `created`, `modified`, `is_sync`) VALUES
	('0BB6BE80-62B5-30D1-24C9-AB41662C3235', 'FBD34267-A93D-3B1A-803F-5C1FF30ED2F5', 1, 'a7ae0de95464c1664d6987b8dbb10947_src.txt', 'aaa.txt', 'text/plain', 8, '2017-04-10 14:20:06', '2017-04-10 14:20:06', 0),
	('28FECCE4-124E-AFE5-705F-3A47554E15B1', '5EA5F6BC-5641-7399-11ED-007CCF541809', 2, '6a786a1e22fc03aab19f9a7ef2a63a2d_src.mp3', 'Sleep Away.mp3', 'audio/mp3', 4842585, '2017-04-07 20:14:36', '2017-04-07 20:14:36', 0),
	('3A781CE0-D8AE-4BDB-642F-644F0A31FDD5', '5EA5F6BC-5641-7399-11ED-007CCF541809', 1, '104a74ddf732b765236e5edd1e302102_src.docx', '娜塔不邋遢-HD2143發文brief.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 18100, '2017-04-07 20:14:36', '2017-04-07 20:14:36', 0),
	('3FA8C96F-900E-AEF1-48E3-156B5A006219', '1C65F890-C9F9-8F63-68D5-8AD2E537ACDB', 2, 'de78b8acb8c66608af2d8e9e6ad9f8b3_src.mp3', 'iCD NO4.5 過客 - 王芊樺.mp3', 'audio/mp3', 9166734, '2017-04-10 17:35:56', '2017-04-10 17:35:56', 0),
	('44A503A6-A319-2AE4-499E-740BAEFA8183', 'E750C516-320C-4C2F-F596-15D0EE4D16A5', 2, '338c567e9cdec89aed557c43f3e19376_src.mp3', 'Developers.mp3', 'audio/mp3', 2905266, '2017-04-07 18:57:19', '2017-04-07 18:57:19', 0),
	('46B99C50-E5EE-0763-1AB5-70937E1B1C65', '3E4A5B9F-8CEB-567F-B6AB-1330D9DFBBE8', 1, '39699f720c6a7e1e026bd62dc97c14d5_src.txt', '終.txt', 'text/plain', 386, '2017-04-10 11:28:08', '2017-04-10 11:28:08', 0),
	('845D0E89-81D7-2156-FAEA-A5600511833F', 'FBD34267-A93D-3B1A-803F-5C1FF30ED2F5', 2, 'f3a2b6a41c66e17f4326e4bf53534757_src.mp3', 'DBBB.mp3', 'audio/mp3', 2905266, '2017-04-10 14:20:06', '2017-04-10 14:20:06', 0),
	('91BCC3B4-62E5-F190-32AE-3F7D5C4CEF2B', '4359FBEC-0C6E-2761-DA3A-CC6D34E2698A', 1, 'be5308b825c0ae9acf12a7b033d425b3_src.txt', 'bbb.txt', 'text/plain', 8, '2017-04-07 18:11:09', '2017-04-07 18:11:09', 0),
	('92509925-F04B-5983-0160-ACBB9C9C07FB', 'D40CB21A-78DC-2F61-9BDF-45706DF29729', 1, 'ae7f31beac389f7a28f85043aa8fc56a_src.txt', 'aaa.txt', 'text/plain', 8, '2017-04-11 11:57:16', '2017-04-11 11:57:16', 0),
	('9E597E89-2492-30A4-20DD-5F510350C550', '1C65F890-C9F9-8F63-68D5-8AD2E537ACDB', 1, 'e131b08f743f299d5923ef85a9b3bd2a_src.txt', '專輯上架文案_iCDEX018_過客.txt', 'text/plain', 14110, '2017-04-10 17:35:56', '2017-04-10 17:35:56', 0),
	('A352A41A-982C-3B3C-3B4D-C01186A37008', 'D40CB21A-78DC-2F61-9BDF-45706DF29729', 2, 'ad959cef5a1fc6e7dcd26db3a921386b_src.mp3', 'DBBB.mp3', 'audio/mp3', 2905266, '2017-04-11 11:57:16', '2017-04-11 11:57:16', 0),
	('D7058522-1F55-B1B9-1D1E-53C1DD541630', '4359FBEC-0C6E-2761-DA3A-CC6D34E2698A', 2, 'd9c4ee36acf8764a4f22e13f2f7e6f38_src.mp3', 'Developers.mp3', 'audio/mp3', 2905266, '2017-04-07 18:13:03', '2017-04-07 18:13:03', 0),
	('DCE62780-0684-A53B-6FA9-22CFB416C9BD', 'E750C516-320C-4C2F-F596-15D0EE4D16A5', 1, 'b86101e07d3285e33b2e65d6929fa3b2_src.txt', 'bbb.txt', 'text/plain', 8, '2017-04-07 18:57:19', '2017-04-07 18:57:19', 0),
	('E0478055-648D-1758-C5FC-0D5933A3507D', '7FB7EBDB-13DE-01C0-E6F5-0F963AFB8C35', 1, '53558372d5c5eba39a8ccbc4d75870a2_src.doc', '青春頌海報文案_台灣版.doc', 'application/msword', 17920, '2017-04-07 19:24:34', '2017-04-07 19:24:34', 0),
	('EFA97B4C-551D-975B-4A87-5D3789591E5F', '7FB7EBDB-13DE-01C0-E6F5-0F963AFB8C35', 2, '556e5456b92b8afe36eee02ae9dd03fc_src.mp3', 'iCD1_廖濠緯 - 妳說的都對.mp3', 'audio/mp3', 10307763, '2017-04-07 19:24:34', '2017-04-07 19:24:34', 0);
/*!40000 ALTER TABLE `upload_file` ENABLE KEYS */;


-- 導出  表 youth_song.user 結構
CREATE TABLE IF NOT EXISTS `user` (
  `user_no` varchar(36) NOT NULL COMMENT '序號',
  `user_name` varchar(100) NOT NULL,
  `user_account` varchar(60) NOT NULL COMMENT '帳號',
  `user_password` varchar(70) NOT NULL COMMENT '密碼',
  `created` datetime NOT NULL COMMENT '建立日期',
  `modified` datetime NOT NULL COMMENT '修改日期',
  `flag` tinyint(1) NOT NULL default '1' COMMENT '狀態 1開/0關',
  `is_sync` tinyint(1) NOT NULL default '0' COMMENT '是否同步0未同步1已同步',
  PRIMARY KEY  (`user_no`),
  KEY `admin_user` (`user_account`),
  KEY `admin_status` (`flag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='後台基本使用者';

-- 正在導出表  youth_song.user 的資料：~1 rows (大約)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`user_no`, `user_name`, `user_account`, `user_password`, `created`, `modified`, `flag`, `is_sync`) VALUES
	('1', '管理者', 'admin', '21232f297a57a5a743894a0e4a801fc3', '2016-11-21 14:16:01', '2016-11-21 14:16:01', 1, 0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;


-- 導出  表 youth_song.works 結構
CREATE TABLE IF NOT EXISTS `works` (
  `works_no` varchar(36) NOT NULL COMMENT '作品序號',
  `sign_no` varchar(36) NOT NULL COMMENT '報名表序號',
  `type` tinyint(1) NOT NULL COMMENT '1=作品1  2=作品2',
  `song_name` varchar(50) NOT NULL COMMENT '歌曲名稱',
  `lyricist` varchar(50) NOT NULL COMMENT '作詞人',
  `composer` varchar(50) NOT NULL COMMENT '作曲人',
  `creative_concept` text NOT NULL COMMENT '創作理念',
  `img_file_file_1` varchar(128) default NULL COMMENT '個人照片 上傳檔名 1',
  `img_file_name_1` varchar(128) default NULL COMMENT '個人照片 原始檔名 1',
  `img_file_file_2` varchar(128) default NULL COMMENT '個人照片 上傳檔名 2',
  `img_file_name_2` varchar(128) default NULL COMMENT '個人照片 原始檔名 2',
  `img_file_file_3` varchar(128) default NULL COMMENT '個人照片 上傳檔名 3',
  `img_file_name_3` varchar(128) default NULL COMMENT '個人照片 原始檔名 3',
  `created` datetime NOT NULL COMMENT '建立時間',
  `modified` datetime NOT NULL COMMENT '修改時間',
  `is_sync` tinyint(1) NOT NULL default '0' COMMENT '是否同步0未同步1已同步',
  PRIMARY KEY  (`works_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='作品明細檔';

-- 正在導出表  youth_song.works 的資料：~6 rows (大約)
/*!40000 ALTER TABLE `works` DISABLE KEYS */;
INSERT INTO `works` (`works_no`, `sign_no`, `type`, `song_name`, `lyricist`, `composer`, `creative_concept`, `img_file_file_1`, `img_file_name_1`, `img_file_file_2`, `img_file_name_2`, `img_file_file_3`, `img_file_name_3`, `created`, `modified`, `is_sync`) VALUES
	('1C65F890-C9F9-8F63-68D5-8AD2E537ACDB', '506296E5-6809-382E-BAED-889AAAAF7E21', 1, 'test', '郭小橘', '郭小橘', '雖然只是短短的一小時，但還好有這樣一堂課，讓我們了解到這裏的理念。音樂真的我們的好伙伴，心情好的時候可以聽、心情不好也可以聽，讓音樂可以美化我們的人生，這也是我們希望音樂在我們小孩的生活中能夠扮演的角色，不期待他能變成周杰倫，但在從小接受正式音樂的薰陶，在一連串的課程中循序漸進的學習音樂，相信小孩可以有更快樂更健康的人生', 'fa7210246f65a3a527b11788693e1bf0_src.png', 'icd_專輯(立)_280x280.png', NULL, NULL, NULL, NULL, '2017-04-10 17:35:56', '2017-04-10 17:35:56', 0),
	('4359FBEC-0C6E-2761-DA3A-CC6D34E2698A', 'F93C8A90-3C41-F2E1-D934-4353C1B9623E', 1, 'aaa', 'bbb', 'ccc', '創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念', '2e9526a365f1193a25dbf968c117056d_src.jpg', '91DEB60C2B2DD461C9CFDFE1537E9BB1C6A22240.jpg', 'e6e548c080a4c1a9fa69bb7a48eee54a_src.jpg', 'image-2.jpg', '', '', '2017-04-07 17:47:07', '2017-04-07 18:57:19', 0),
	('5EA5F6BC-5641-7399-11ED-007CCF541809', '70A44B79-F396-AA6A-0DFF-C8B204E1408F', 1, 'test', 'test', '李曉景', '娜塔不邋遢-HD2143發文brief\r\n1.先以娜塔家中適合的產品擺放位置，帶出都會紅的產品特色，強調產品外型美觀時尚，有別於過往萬用鍋的外型。\r\n2.帶出家人對於料理、食物的需求，例如育兒生活忙碌，老公也喜歡吃到新菜色，', '35399c71960fe910d314cb95f27b0a2a_src.png', '兩岸音樂創作大賽海報3 繁簡版-02.png', NULL, NULL, NULL, NULL, '2017-04-07 20:14:36', '2017-04-07 20:14:36', 0),
	('D40CB21A-78DC-2F61-9BDF-45706DF29729', 'A8A27B91-6B9A-1C79-8357-76544B9328BF', 1, '測試曲', '測試詞者', '測試曲者', '創作的理念是創作理念f創作的理念是創作的理念是創作的理念是創作的理念是創作的理念是創作的理念是創作的理念是創作的理念是創作的理念是創作的理念是創作的理念是創作的理念是創作的理念是創作的理念是創作理念創作理念創作理念創作理念創作理念創作理念念創作理念念創作理念念創作理念hhhh', '53e209d7a64e1fd57de52a62eb4cedd4_src.png', 'logo.png', NULL, NULL, NULL, NULL, '2017-04-11 11:57:16', '2017-04-11 14:31:01', 0),
	('E750C516-320C-4C2F-F596-15D0EE4D16A5', 'F93C8A90-3C41-F2E1-D934-4353C1B9623E', 2, 'qqq', 'www', 'eee', '2222創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念創作理念', '04863eeb994a0b092c06304d67fef101_src.png', 'logo.png', '1a1317b90a6c0658405d492ee7bfbc27_src.jpg', 'image-2.jpg', NULL, NULL, '2017-04-07 18:57:19', '2017-04-07 18:57:19', 0),
	('FBD34267-A93D-3B1A-803F-5C1FF30ED2F5', 'CBD73CE5-4D34-4DA8-D051-9A300730A7B0', 1, '測試曲', '測試詞者', '測試曲者', '創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1創作理念1', '3384d630441e1b1b7acb82336efd8380_src.png', 'logo.png', NULL, NULL, NULL, NULL, '2017-04-10 14:20:06', '2017-04-10 14:20:06', 0);
/*!40000 ALTER TABLE `works` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
