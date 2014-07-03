<?php

if(!defined('_MYSQL_ENGINE_')){
	define(_MYSQL_ENGINE_,'MyISAM');
}

Db::getInstance()->Execute("
			CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."obs_attribute_desc_lang` (
			  `id_attribute` int(11) NOT NULL,
			  `id_lang` int(11) NOT NULL,
			  `desc` text NOT NULL,
			  PRIMARY KEY (`id_attribute`,`id_lang`)
			) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;");
			
?>