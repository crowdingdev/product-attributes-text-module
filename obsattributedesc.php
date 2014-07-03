<?php
/*
* 2007-2011 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 6798 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class Obsattributedesc extends Module
{
	public function __construct() {
		$this->name = 'obsattributedesc';
		$this->tab = 'front_office_features';
		$this->version = '1.2';
		$this->author = 'OBSolutions.es';
		$this->module_key = '3fa949246d62ba11735ee75e8651b18a';

		parent::__construct();

		$this->_errors = array();

		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Attributes description');
		$this->description = $this->l('Adds descriptions in product attributes to show in product details.');

	}

	public function install() {
		if (!parent::install() OR !$this->registerHook('productOutOfStock') OR !$this->registerHook('header'))
			return false;

		require_once(dirname(__FILE__).'/install-sql.php');

		return true;
	}

	public function uninstall() {
		if (!parent::uninstall())
			return false;

		return true;
	}

	public function hookHeader($params) {
		if (version_compare(_PS_VERSION_,'1.5','<')) {
			//VERSION 1.4.x
			Tools::addCSS($this->_path.'css/obsattributedesc.css');
		} else {
			//VERSION 1.5.x
			$this->context->controller->addCSS($this->_path.'css/obsattributedesc.css', 'all');
		}
	}

	public function hookProductOutOfStock($params) {

		global $smarty, $cookie;

		require_once(_PS_MODULE_DIR_.'obsattributedesc/classes/MAttributes.php');

		$product = $params['product'];
		$attributeGroup = $product->getAttributesGroups((int)($cookie->id_lang));
		$attrDesc = array();
		foreach ($attributeGroup as $row){

			$description = MAttributes::attributeDesc($row['id_attribute'], $cookie->id_lang);

			$attrDesc[$row['id_attribute']] = $description;
		}

		$smarty->assign(array(
				'attrDesc' => $attrDesc
			));

		return $this->display(__FILE__, 'obsattributedesc.tpl');

	}


/**
	 * getContent used to display admin module form
	 *
	 * @return void
	 */
	public function getContent()
	{
		global $currentIndex, $protocol_content;
		require_once(_PS_MODULE_DIR_.'obsattributedesc/classes/MAttributes.php');

		//$this->postProcess();

		$current_dir=defined(__DIR__)?__DIR__:dirname(__FILE__);
		$output = '<fieldset><legend>'.$this->l('Attributes description').'</legend>';

		if (Tools::isSubmit('submitupdatedesc'))
			$output .= $this->getUpdateAttributesDesc();
		else
			$output .= $this->getAttributesList();

		$output .= '</fieldset>';
		return $output;
	}

	private function getUpdateAttributesDesc()
	{
		global $currentIndex, $cookie;

		$done = false;

		if(!Tools::getIsset('id_group') || !Tools::isSubmit('submitupdatedesc'))
			echo $this->displayError($this->l('Wrong data input'));

		else{
			$attributes = AttributeGroup::getAttributes((int)($cookie->id_lang), Tools::getValue('id_group'));
			foreach($attributes as $attr){

				$languages = Language::getLanguages();
				foreach($languages as $lang){

					if(Tools::getIsset('desc_'.$attr['id_attribute'].'_'.$lang['id_lang']))
						if(MAttributes::insertAttributeDesc($attr['id_attribute'], $lang['id_lang'], Tools::getValue('desc_'.$attr['id_attribute'].'_'.$lang['id_lang'])))
							$done = true;

				}

			}

			if(!$done)
				echo $this->displayError($this->l('Insert database error'));
			else
				Tools::redirectAdmin($currentIndex.'&configure=obsattributedesc&token='.Tools::getValue('token').'&id_group='.(int) Tools::getValue('id_group').'&submitlistattributes&conf=4');
		}
		return $this->getAttributesList();

	}

	private function getAttributesList()
	{
		global $currentIndex, $cookie;

		$attGroups = MAttributes::attributeGroupList($cookie->id_lang);

		$languages = Language::getLanguages();
		$defaultLanguage = intval($cookie->id_lang);
		$divLangName = '';

		$output = '<script type="text/javascript">
				id_language = Number('.$defaultLanguage.');
			</script>';

		$output .= '<h2>'.$this->l('Attributes description').'</h2>';

		$output .= '<div style="margin:10px; min-height: 500px;">';

		$output .= '<p style="clear: both"></p>';
 	 	$output .= '<fieldset style="width: 800px;">';
 	 	$output .= '<h3>'.$this->l('Module Instructions').'</h3>';
 	 	$output .='
 	 	<ul>
	 	 	<li style="padding-top:10px">'.$this->l('1. Select an attribute group and click View Attributes').'</li>
	 	 	<li style="padding-top:10px">'.$this->l('2. Enter a description for each attribute').'</li>
	 	 	<li style="padding-top:10px">'.$this->l('3. Follow the above steps for all attribute groups that you want to add a description').'</li>
 	 	</ul>
 	 	</fieldset>';

		$output .= '<a name="attributesGroup">&nbsp;</a>&nbsp;';

		$output.= '<form id="formu" method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">';


		$output .='<div style="margin-top: 20px">'.$this->l('Select an attribute group:');


		$output .='&nbsp;<select name="id_group">
					<option value="">'.$this->l('Select...').'</option>';
		foreach($attGroups as $group){
			$output .='<option value="'.$group['id_attribute_group'].'" '.((Tools::getIsset('id_group') && Tools::getValue('id_group') == $group['id_attribute_group'])?'selected':'').'>'.$group['name'].' </option>';

		}
		$output .= '</select>&nbsp;';
		$output .= '<input type="submit" name="submitlistattributes" value="'.$this->l('View Attributes').'" class="button" />';

		$output .='</form>
				</div>';


		if(Tools::getIsset('submitlistattributes') && Tools::getIsset('id_group') && Tools::getValue('id_group') != ''){

			$attributes = AttributeGroup::getAttributes((int)($cookie->id_lang), Tools::getValue('id_group'));
			foreach($attributes as $attr){
				$divLangName .='desc_'.$attr['id_attribute'].'¤';
			}

			if(strlen($divLangName) > 0)
				$divLangName = substr($divLangName, 0, strlen($divLangName)-2);

			$output.= '<form id="formu" method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">
						<input type="hidden" name="id_group" value="'.Tools::getValue('id_group').'"/>';

			foreach($attributes as $attr){
				$output .= '<div id="attr_'.$attr['id_attribute'].'" style="margin-top:20px">';

				$output .= '<table>
							<tr><td>
								'.$attr['name'].'
							</td></tr>

							<tr><td>';
				foreach ($languages as $lang){

					$desc = MAttributes::attributeDesc($attr['id_attribute'], $lang['id_lang']);
					$output .='<div id="desc_'.$attr['id_attribute'].'_'.$lang['id_lang'].'" style="display: '.($lang['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
					<textarea name="desc_'.$attr['id_attribute'].'_'.$lang['id_lang'].'" style="width: 700px;" rows="4" value="'.$desc.'">'.$desc.'</textarea>
					</div>';

				}
				$output .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'desc_'.$attr['id_attribute'], true);
				$output .= '</td></tr>
							</table>';

				$output .= '</div>';
			}
			$output .= '<br/>

			<center><input type="submit" name="submitupdatedesc" value="'.$this->l('Save data').'" class="button" /></center>';
		}



	$output .= '</div>';

		return $output;

	}

}