<?php
//  ------------------------------------------------------------------------ //
//                      BOOKSHOP - MODULE FOR XOOPS 2                		 //
//                  Copyright (c) 2007, 2008 Instant Zero                    //
//                     <http://www.instant-zero.com/>                        //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
/**
 * Recherche avancée dans les livres, formulaire de sélection des critères
 */
if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
$sform = new XoopsThemeForm(bookshop_get_module_name().' - '._BOOKSHOP_SEARCHFOR, 'bookSearchForm', BOOKSHOP_URL.'search.php','post');
$sform->addElement(new XoopsFormText(_BOOKSHOP_TEXT,'book_text',50,255, ''), false);
$sform->addElement(new XoopsFormSelectMatchOption(_BOOKSHOP_TYPE, 'search_type', 3), false);


// Sélecteur de catégories ****************************************************
$categorySelect = new XoopsFormSelect(_BOOKSHOP_CATEGORY, 'book_category', 0);
$mytree = new Bookshop_XoopsObjectTree($tblCategories, 'cat_cid', 'cat_pid');
$select_categ = $mytree->makeSelBox('cat_pid', 'cat_title', '-');
$select_categ = str_replace("<select id='cat_pid' name='cat_pid'>", '', $select_categ);
$select_categ = str_replace('</select>', '', $select_categ);
$select_categ = explode("</option>",$select_categ);
$tblTmp = array();
$tblTmp[0] = _BOOKSHOP_ALL_CATEGORIES;
foreach($select_categ as $item) {
	$array = array();
	preg_match("/<option value=\'([0-9]*)\'>/", $item, $array);	// Pour récupérer l'ID de chaque catégorie
	$libelle = preg_replace("/<option value=\'([0-9]*)\'>/", '', $item);	// Pour ne conserver que le libellé
	if(isset($array[1])) {
		$catId = intval($array[1]);
		$tblTmp[$catId] = $libelle;
	}
}
$categorySelect->addOptionArray($tblTmp);
$sform->addElement($categorySelect, false);


// Sélecteur pour les auteurs *************************************************
$authorSelect = new XoopsFormSelect(_BOOKSHOP_AUTHOR, 'book_authors', 0, 5, true);
$tblTmp = array();
$tblTmp[0] = _BOOKSHOP_ALL_AUTHORS;
foreach($tblAuthors as $item) {
	$tblTmp[$item->getVar('auth_id')] = $item->getVar('auth_firstname').' '.$item->getVar('auth_name');
}
$authorSelect->addOptionArray($tblTmp);
$sform->addElement($authorSelect, false);


// Sélecteur pour les traducteurs *********************************************
$translatorSelect = new XoopsFormSelect(_BOOKSHOP_TRANSLATOR, 'book_translators', 0, 5, true);
$tblTmp = array();
$tblTmp[0] = _BOOKSHOP_ALL_TRANSLATORS;
foreach($tblTranslators as $item) {
	$tblTmp[$item->getVar('auth_id')] = $item->getVar('auth_firstname').' '.$item->getVar('auth_name');
}
$translatorSelect->addOptionArray($tblTmp);
$sform->addElement($translatorSelect, false);

// Sélecteur pour les langues *************************************************
$languageSelect = new XoopsFormSelect(_BOOKSHOP_LANG, 'book_language', 0, 1, false);
$tblTmp = array();
$tblTmp[0] = _BOOKSHOP_ALL_LANGUAGES;
foreach($tblLang as $item) {
	$tblTmp[$item->getVar('lang_id')] = $item->getVar('lang_lang');
}
$languageSelect->addOptionArray($tblTmp);
$sform->addElement($languageSelect, false);


$sform->addElement(new XoopsFormHidden('op', 'go'));

$button_tray = new XoopsFormElementTray('' ,'');
$submit_btn = new XoopsFormButton('', 'post', _SUBMIT, 'submit');
$button_tray->addElement($submit_btn);
$sform->addElement($button_tray);
?>