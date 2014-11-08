<?php

class m130315_070133_init_deployment extends CDbMigration
{
	public function up()
	{
                $sql = <<<EOD
CREATE TABLE IF NOT EXISTS `attempt_recovery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(10) DEFAULT NULL,
  `ip` varchar(32) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `result` tinyint(4) NOT NULL COMMENT '0 - fail 1 - success',
  PRIMARY KEY (`id`),
  KEY `idx_1` (`phone`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `attempt_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(40) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_blacklisted` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_user`,`id_blacklisted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_region` int(11) NOT NULL,
  `count_users` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_region` (`id_region`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bonus_money_return` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `id_region` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_region`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `name` varchar(48) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `is_phone_contact` tinyint(1) DEFAULT NULL,
  `subject` tinyint(4) NOT NULL,
  `text` text NOT NULL,
  `answer` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `geoip` (
  `long_ip1` bigint(20) NOT NULL,
  `long_ip2` bigint(20) NOT NULL,
  `id_city` int(11) NOT NULL,
  `upd` datetime DEFAULT NULL COMMENT 'актуальность',
  KEY `INDEX` (`long_ip1`,`long_ip2`),
  KEY `id_city` (`id_city`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) DEFAULT NULL,
  `cost` float(10,2) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `image_big` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_event` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `im_profile_ihave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ihave` int(11) NOT NULL,
  `id_profile` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_ihave`,`id_profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `im_profile_target` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_target` int(11) NOT NULL,
  `id_profile` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_target`,`id_profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `im_user_gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sender` int(11) NOT NULL,
  `id_reciever` int(11) NOT NULL,
  `id_gift` int(11) NOT NULL,
  `postcard` text,
  `is_private` tinyint(4) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_sender`,`id_reciever`,`id_gift`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `im_user_meetmethod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_meetmethod` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_user`),
  KEY `idx_2` (`id_meetmethod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `im_user_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_news` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `issue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `useragent` varchar(255) DEFAULT NULL,
  `window_size` varchar(16) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  `text` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_from` varchar(255) DEFAULT NULL,
  `mail_to` varchar(255) DEFAULT NULL,
  `name_from` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body_type` tinyint(4) DEFAULT NULL,
  `body` text,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` int(11) NOT NULL DEFAULT '0',
  `fl_process` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sender` int(11) NOT NULL,
  `id_reciever` int(11) NOT NULL,
  `id_offer` int(11) DEFAULT NULL,
  `text` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_sender`,`id_reciever`,`id_offer`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `news_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `alias` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_news_template_1` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

CREATE TABLE IF NOT EXISTS `newstype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(32) NOT NULL,
  `id_news` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_news`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `new_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `code` varchar(64) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_user`,`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `new_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `code` varchar(16) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_user`,`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `new_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(11) NOT NULL,
  `password` varchar(32) NOT NULL,
  `code` varchar(16) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sender` int(11) NOT NULL COMMENT 'кто предложил',
  `id_reciever` int(11) NOT NULL COMMENT 'кому предложили',
  `id_method` int(11) NOT NULL COMMENT 'что продложили',
  `status` tinyint(4) NOT NULL,
  `date_offer` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_1` (`id_method`,`id_sender`,`id_reciever`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='таблица предложений' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `pay_intellectmoney` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `data` varchar(255) NOT NULL,
  `order_id` varchar(24) NOT NULL,
  `payment_id` varchar(32) NOT NULL,
  `service_name` int(11) NOT NULL,
  `operation` tinyint(2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `pay_sms_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(64) DEFAULT NULL,
  `phone_user` varchar(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `operation` tinyint(2) NOT NULL,
  `code` varchar(16) NOT NULL,
  `data` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `pay_sms_cost` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_number` varchar(4) DEFAULT NULL,
  `operator` varchar(32) DEFAULT NULL,
  `cost_user` decimal(6,2) DEFAULT NULL,
  `cost_client` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`short_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `filename_big` varchar(64) NOT NULL,
  `filename_medium` varchar(64) NOT NULL,
  `filename_faceribbon` varchar(64) NOT NULL,
  `filename_small` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `height` int(2) DEFAULT NULL COMMENT 'Рост',
  `weight` int(3) DEFAULT NULL COMMENT 'Вес',
  `age_min` int(2) DEFAULT NULL,
  `age_max` int(2) DEFAULT NULL,
  `id_seeking` tinyint(1) DEFAULT NULL,
  `id_orientation` int(1) DEFAULT NULL COMMENT 'Ориентация',
  `id_children` int(1) DEFAULT NULL COMMENT 'Наличие детей',
  `id_welfare` int(1) DEFAULT NULL COMMENT 'Материальное положение',
  `id_status` int(1) DEFAULT NULL COMMENT 'Семейное положение',
  `id_housing` int(1) DEFAULT NULL COMMENT 'Наличие жилья',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_orientation` (`id_orientation`),
  KEY `id_children` (`id_children`),
  KEY `id_welfare` (`id_welfare`),
  KEY `id_status` (`id_status`),
  KEY `id_housing` (`id_housing`),
  KEY `idx_1` (`id_seeking`),
  KEY `idx_2` (`height`),
  KEY `idx_3` (`weight`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `id_capital` int(11) DEFAULT NULL,
  `timezone` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_capital`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(64) NOT NULL DEFAULT 'system',
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_key` (`category`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `spam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sender` int(11) NOT NULL,
  `id_subject` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_1` (`id_sender`,`id_subject`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `stat_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `count_messages` int(11) NOT NULL DEFAULT '0',
  `count_gifts` int(11) NOT NULL DEFAULT '0',
  `count_count_offers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(24) NOT NULL,
  `salt` varchar(4) NOT NULL,
  `password` varchar(33) NOT NULL,
  `role` varchar(16) NOT NULL,
  `register_step` int(11) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `id_userpic` int(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `id_region` int(11) DEFAULT NULL,
  `timezone` varchar(32) DEFAULT NULL,
  `id_city` int(11) DEFAULT NULL,
  `id_gender` tinyint(1) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `date_lastvisit` timestamp NULL DEFAULT NULL,
  `date_register` timestamp NULL DEFAULT NULL,
  `date_rating` timestamp NULL DEFAULT NULL,
  `account` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'монетки',
  `account_bonus` decimal(11,2) NOT NULL DEFAULT '0.00',
  `counter_offer` int(11) NOT NULL DEFAULT '0',
  `fl_banned` tinyint(1) DEFAULT NULL,
  `fl_deleted` tinyint(1) DEFAULT NULL,
  `fl_newmail` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`),
  KEY `idx_1` (`role`),
  KEY `idx_2` (`register_step`),
  KEY `idx_3` (`name`),
  KEY `idx_4` (`birthday`),
  KEY `idx_5` (`id_region`,`id_city`),
  KEY `idx_6` (`id_gender`),
  KEY `idx_7` (`date_register`),
  KEY `idx_8` (`fl_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id_user`),
  KEY `idx_2` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=200 ;
EOD;
                
                $this->execute($sql);
            
	}
       
	public function down()
	{            
                $this->dropTable('attempt_recovery');
                $this->dropTable('attempt_register');
                $this->dropTable('blacklist');
                $this->dropTable('bonus');
                $this->dropTable('bonus_money_return');
                $this->dropTable('city');
                $this->dropTable('feedback');
                $this->dropTable('geoip');
                $this->dropTable('gift');
                $this->dropTable('history');
                $this->dropTable('im_profile_ihave');
                $this->dropTable('im_profile_target');
                $this->dropTable('im_user_gift');
                $this->dropTable('im_user_meetmethod');
                $this->dropTable('im_user_news');
                $this->dropTable('issue');
                $this->dropTable('mail');
                $this->dropTable('message');
                $this->dropTable('news_template');
                $this->dropTable('newstype');
                $this->dropTable('new_email');
                $this->dropTable('new_phone');
                $this->dropTable('new_user');
                $this->dropTable('offer');
                $this->dropTable('pay_intellectmoney');
                $this->dropTable('pay_sms_code');
                $this->dropTable('pay_sms_cost');
                $this->dropTable('photo');
                $this->dropTable('profile');
                $this->dropTable('region');
                $this->dropTable('settings');
                $this->dropTable('spam');
                $this->dropTable('stat_daily');
                $this->dropTable('user');
                $this->dropTable('_action');
	}
}