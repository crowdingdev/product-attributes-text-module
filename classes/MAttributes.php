<?php

class MAttributes extends ObjectModel
{
	
	public static function attributeGroupList($id_lang)
	{
		$result = Db::getInstance()->ExecuteS('
		SELECT  al.*
		FROM  '._DB_PREFIX_.'attribute_group_lang al
		WHERE al.id_lang = '.(int) $id_lang.'
		ORDER BY al.name asc');
		return $result;
	}
	
	public static function insertAttributeDesc($id_attribute, $id_lang, $desc)
	{
		$sql = "
		INSERT INTO "._DB_PREFIX_."obs_attribute_desc_lang (`id_attribute`,`id_lang`,`desc`) VALUES (".(int) $id_attribute.",".(int) $id_lang.",'".pSQL($desc)."')
 		 ON DUPLICATE KEY UPDATE `desc`='".pSQL($desc)."'";
		
		$result = Db::getInstance()->Execute($sql);
		if($result)
			return true;
		else
			return false;
	}
	
	public static function attributeDesc($id_attribute, $id_lang)
	{
		$sql = '
		SELECT  ad.*
		FROM  '._DB_PREFIX_.'obs_attribute_desc_lang ad
		WHERE ad.`id_attribute` = '.(int) $id_attribute.' AND ad.`id_lang` = '.(int) $id_lang;
		
		$result = Db::getInstance()->getRow($sql);
		
		return $result['desc'];
	}
	
}

?>