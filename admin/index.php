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

include_once '../../../include/cp_header.php';
include_once '../include/common.php';

include_once BOOKSHOP_PATH.'admin/functions.php';
include_once XOOPS_ROOT_PATH.'/class/tree.php';
include_once XOOPS_ROOT_PATH.'/class/uploader.php';
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

$op = 'default';
if (isset($_POST['op'])) {
	$op = $_POST['op'];
} else {
	if ( isset($_GET['op'])) {
    	$op = $_GET['op'];
	}
}
$destname = '';

// TODO: TVA par défaut, Langue par défaut

// Mise à jour automatique pour ajouter les nouveaux champs
if (!booksop_FieldExists('book_recommended', $xoopsDB->prefix('bookshop_books'))) {
	booksop_AddField("`book_recommended` DATE NOT NULL",$xoopsDB->prefix('bookshop_books'));
}

if (!booksop_FieldExists('book_metakeywords', $xoopsDB->prefix('bookshop_books'))) {
	booksop_AddField("`book_metakeywords` VARCHAR( 255 ) NOT NULL",$xoopsDB->prefix('bookshop_books'));
	booksop_AddField("`book_metadescription` VARCHAR( 255 ) NOT NULL",$xoopsDB->prefix('bookshop_books'));
	booksop_AddField("`book_metatitle` VARCHAR( 255 ) NOT NULL",$xoopsDB->prefix('bookshop_books'));
}

if (!booksop_FieldExists('cat_metatitle', $xoopsDB->prefix('bookshop_cat'))) {
	booksop_AddField("`cat_metakeywords` VARCHAR( 255 ) NOT NULL",$xoopsDB->prefix('bookshop_cat'));
	booksop_AddField("`cat_metadescription` VARCHAR( 255 ) NOT NULL",$xoopsDB->prefix('bookshop_cat'));
	booksop_AddField("`cat_metatitle` VARCHAR( 255 ) NOT NULL",$xoopsDB->prefix('bookshop_cat'));
}

if (!booksop_FieldExists('disc_shipping_amount', $xoopsDB->prefix('bookshop_discounts'))) {
	booksop_AddField("`disc_shipping_amount` DOUBLE( 7, 2 ) NOT NULL",$xoopsDB->prefix('bookshop_discounts'));
	booksop_AddField("`disc_shipping_amount_next` DOUBLE( 7, 2 ) NOT NULL",$xoopsDB->prefix('bookshop_discounts'));
}

if (!booksop_FieldExists('disc_qty_criteria', $xoopsDB->prefix('bookshop_discounts'))) {
	booksop_AddField("`disc_qty_criteria` tinyint(1) unsigned NOT NULL",$xoopsDB->prefix('bookshop_discounts'));
}

if (!booksop_FieldExists('disc_qty_value', $xoopsDB->prefix('bookshop_discounts'))) {
	booksop_AddField("`disc_qty_value`  mediumint(8) unsigned NOT NULL",$xoopsDB->prefix('bookshop_discounts'));
}

function bookshop_upload($indice)
{
	global $destname;
	if(isset($_POST['xoops_upload_file'])) {
		include_once XOOPS_ROOT_PATH.'/class/uploader.php';
		$fldname = $_FILES[$_POST['xoops_upload_file'][$indice]];
		$fldname = (get_magic_quotes_gpc()) ? stripslashes($fldname['name']) : $fldname['name'];
		if(xoops_trim($fldname != '')) {
			$dstpath = XOOPS_UPLOAD_PATH;
			$destname = bookshop_createUploadName($dstpath ,$fldname, true);

			$permittedtypes = explode("\n",str_replace("\r",'', bookshop_getmoduleoption('mimetypes')));
			array_walk($permittedtypes, 'trim');

			$uploader = new XoopsMediaUploader($dstpath, $permittedtypes, bookshop_getmoduleoption('maxuploadsize'));
			$uploader->setTargetFileName($destname);
			if ($uploader->fetchMedia($_POST['xoops_upload_file'][$indice])) {
				if ($uploader->upload()) {
					return true;
				} else {
					echo _AM_BOOKSHOP_ERROR_3.$uploader->getErrors();
				}
			} else {
				echo $uploader->getErrors();
			}
		}
	}
	return false;
}

function show_footer()
{
	echo "<br /><br /><div align='center'><a href='http://www.instant-zero.com' target='_blank'><img src='../images/instantzero.gif'></a></div>";
}

$limit = bookshop_getmoduleoption('items_count');	// Nombre maximum d'éléments à afficher dans l'admin
$baseurl = BOOKSHOP_URL.'admin/'.basename(__FILE__);	// URL de ce script
$conf_msg = bookshop_JavascriptLinkConfirm(_AM_BOOKSHOP_CONF_DELITEM);
$manual_meta = bookshop_getmoduleoption('manual_meta');

global $xoopsConfig;
if (file_exists( BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/modinfo.php')) {
	include_once BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/modinfo.php';
} else {
	include_once BOOKSHOP_PATH.'language/english/modinfo.php';
}

if (file_exists( BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/main.php')) {
	include_once BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/main.php';
} else {
	include_once BOOKSHOP_PATH.'language/english/main.php';
}


// ******************************************************************************************************************************************
// **** Main ********************************************************************************************************************************
// ******************************************************************************************************************************************
switch ($op) {

	// ****************************************************************************************************************
	case 'texts':	// Gestion de la page d'index
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(9);
		include_once BOOKSHOP_PATH.'class/registryfile.php';
		$registry = new bookshop_registryfile();

		$sform = new XoopsThemeForm(_MI_BOOKSHOP_ADMENU8, 'frmatxt', $baseurl);
		$sform->addElement(new XoopsFormHidden('op', 'savetexts'));
		$editor1 = bookshop_getWysiwygForm(_AM_BOOKSHOP_INDEX_PAGE,'welcome1', $registry->getfile('bookshop_index.txt'), 5, 60, 'hometext1_hidden');
		if($editor1) {
			$sform->addElement($editor1, false);
		}

		$editor2 = bookshop_getWysiwygForm(_BOOKSHOP_CGV,'welcome2', $registry->getfile('bookshop_cgv.txt'), 5, 60, 'hometext2_hidden');
		if($editor2) {
			$sform->addElement($editor2, false);
		}

		$editor3 = bookshop_getWysiwygForm(_AM_BOOKSHOP_RECOMM_TEXT,'welcome3', $registry->getfile('bookshop_recomm.txt'), 5, 60, 'hometext3_hidden');
		if($editor3) {
			$sform->addElement($editor3, false);
		}

		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', _AM_BOOKSHOP_MODIFY, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray);
		$sform = bookshop_formMarkRequiredFields($sform);
		$sform->display();
		break;

	// ****************************************************************************************************************
	case 'savetexts':		// Sauvegarde des textes d'accueil ********************************************************
	// ****************************************************************************************************************
		include_once BOOKSHOP_PATH.'class/registryfile.php';
		$registry = new bookshop_registryfile();
		$registry->savefile($myts->stripSlashesGPC($_POST['welcome1']),'bookshop_index.txt');
		$registry->savefile($myts->stripSlashesGPC($_POST['welcome2']),'bookshop_cgv.txt');
		$registry->savefile($myts->stripSlashesGPC($_POST['welcome3']),'bookshop_recomm.txt');
		bookshop_updateCache();
		bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl, 2);
		break;


	// ****************************************************************************************************************
	case 'instant-zero';	// Publicité
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(11);
		echo "<iframe src='http://www.instant-zero.com/modules/liaise/?form_id=2' width='100%' height='600' frameborder='0'></iframe>";
		show_footer();
		break;


	// ****************************************************************************************************************
	case 'lang':	// Gestion des langues
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(1);
		$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$tbl_lang = array();
		echo "<form method='post' action='$baseurl' name='frmaddlang' id='frmaddlang'><input type='hidden' name='op' id='op' value='addlang' /><input type='submit' name='btngo' id='btngo' value='"._AM_BOOKSHOP_ADD_ITEM."' /></form>";
		bookshop_htitle(_MI_BOOKSHOP_ADMENU0,4);
		$tbl_lang = $h_bookshop_lang->GetAllLang($start, $limit);
		$class='';
		echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
		echo "<tr><th align='center'>"._AM_BOOKSHOP_ID."</th><th align='center'>"._BOOKSHOP_LANG."</th><th align='center'>"._AM_BOOKSHOP_ACTION."</th></tr>";
		foreach ($tbl_lang as $item) {
			$class = ($class == 'even') ? 'odd' : 'even';
			$action_edit = "<a href='$baseurl?op=editlang&id=".$item->getVar('lang_id')."' title='"._BOOKSHOP_EDIT."'>".$icones['edit'].'</a>';
			$action_delete = "<a href='$baseurl?op=deletelang&id=".$item->getVar('lang_id')."' title='"._BOOKSHOP_DELETE."'".$conf_msg.">".$icones['delete'].'</a>';
			echo "<tr class='".$class."'>\n";
			echo "<td>".$item->getVar('lang_id')."</td><td align='center'>".$item->getVar('lang_lang')."</td><td align='center'>".$action_edit.' '.$action_delete."</td>\n";
			echo "<tr>\n";
		}
		$class = ($class == 'even') ? 'odd' : 'even';
		echo "<tr class='".$class."'>\n";
		echo "<td colspan='3' align='center'><form method='post' action='$baseurl' name='frmaddlang' id='frmaddlang'><input type='hidden' name='op' id='op' value='addlang' /><input type='submit' name='btngo' id='btngo' value='"._AM_BOOKSHOP_ADD_ITEM."' /></form></td>\n";
		echo "</tr>\n";
		echo '</table>';
        show_footer();
		break;

	// ****************************************************************************************************************
	case 'addlang':		// Ajout d'une langue
	case 'editlang':	// Edition d'une langue
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(1);
		if($op == 'editlang') {
			$title = _AM_BOOKSHOP_EDIT_LANG;
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			if(empty($id)) {
				bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
			}
			// Item exits ?
			$item = null;
			$item = $h_bookshop_lang->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$edit = true;
			$label_submit = _AM_BOOKSHOP_MODIFY;
		} else {
			$title = _AM_BOOKSHOP_ADD_LANG;
			$item = $h_bookshop_lang->create(true);
			$label_submit = _AM_BOOKSHOP_ADD;
			$edit = false;
		}
		$sform = new XoopsThemeForm($title, 'frmaddlang', $baseurl);
		$sform->addElement(new XoopsFormHidden('op', 'saveeditlang'));
		$sform->addElement(new XoopsFormHidden('lang_id', $item->getVar('lang_id')));
		$sform->addElement(new XoopsFormText(_BOOKSHOP_LANG,'lang_lang',50,150, $item->getVar('lang_lang','e')), true);

		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', $label_submit, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray);
		$sform = bookshop_formMarkRequiredFields($sform);
		$sform->display();
		show_footer();
		break;


	// ****************************************************************************************************************
	case 'saveeditlang':	// Sauvegarde d'une langue (édition et ajout)
	// ****************************************************************************************************************
		xoops_cp_header();
		$id = isset($_POST['lang_id']) ? intval($_POST['lang_id']) : 0;
		if(!empty($id)) {
			$edit = true;
			$item = $h_bookshop_lang->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$item->unsetNew();
		} else {
			$item= $h_bookshop_lang->create(true);
		}

		$item->setVars($_POST);
		$res = $h_bookshop_lang->insert($item);
		if($res) {
			bookshop_updateCache();
			bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=lang', 2);
		} else {
			bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=lang',5);
		}
		break;


	// ****************************************************************************************************************
	case 'deletelang':	// Suppression d'une langue
	// ****************************************************************************************************************
        xoops_cp_header();
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(empty($id)) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
		}
		// On vérifie que cette langue n'est pas utilisée par un livre
		$criteria = new Criteria('book_lang_id', $id, '=');
		$cnt = $h_bookshop_books->getCount($criteria);
		if($cnt == 0) {
			$item = null;
			$item = $h_bookshop_lang->get($id);
			if(is_object(($item))) {
				$res = $h_bookshop_lang->delete($item, true);
				if($res) {
					bookshop_updateCache();
					bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=lang',2);
				} else {
					bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=lang',5);
				}
			} else {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl.'?op=lang',5);
			}
		} else {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_6, $baseurl.'?op=lang',5);
		}
		break;


	// ****************************************************************************************************************
	case 'vat':	// Gestion des TVA
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(2);
		$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$tbl_vat = array();
		echo "<form method='post' action='$baseurl' name='frmaddvat' id='frmaddvat'><input type='hidden' name='op' id='op' value='addvat' /><input type='submit' name='btngo' id='btngo' value='"._AM_BOOKSHOP_ADD_ITEM."' /></form>";
		bookshop_htitle(_MI_BOOKSHOP_ADMENU1,4);
		$tbl_vat = $h_bookshop_vat->GetAllVats($start, $limit);
		$class='';
		echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
		echo "<tr><th align='center'>"._AM_BOOKSHOP_ID."</th><th align='center'>"._AM_BOOKSHOP_RATE."</th><th align='center'>"._AM_BOOKSHOP_ACTION."</th></tr>";
		foreach ($tbl_vat as $item) {
			$class = ($class == 'even') ? 'odd' : 'even';
			$action_edit = "<a href='$baseurl?op=editvat&id=".$item->getVar('vat_id')."' title='"._BOOKSHOP_EDIT."'>".$icones['edit'].'</a>';
			$action_delete = "<a href='$baseurl?op=deletevat&id=".$item->getVar('vat_id')."' title='"._BOOKSHOP_DELETE."'".$conf_msg.">".$icones['delete'].'</a>';
			echo "<tr class='".$class."'>\n";
			echo "<td>".$item->getVar('vat_id')."</td><td align='right'>".$item->getVar('vat_rate')."</td><td align='center'>".$action_edit.' '.$action_delete."</td>\n";
			echo "<tr>\n";
		}
		$class = ($class == 'even') ? 'odd' : 'even';
		echo "<tr class='".$class."'>\n";
		echo "<td colspan='3' align='center'><form method='post' action='$baseurl' name='frmaddvat' id='frmaddvat'><input type='hidden' name='op' id='op' value='addvat' /><input type='submit' name='btngo' id='btngo' value='"._AM_BOOKSHOP_ADD_ITEM."' /></form></td>\n";
		echo "</tr>\n";
		echo '</table>';
        show_footer();
		break;


	// ****************************************************************************************************************
	case 'saveeditvat':	// Sauvegarde d'une TVA
	// ****************************************************************************************************************
		xoops_cp_header();
		$id = isset($_POST['vat_id']) ? intval($_POST['vat_id']) : 0;
		if(!empty($id)) {
			$edit = true;
			$item = $h_bookshop_vat->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$item->unsetNew();
		} else {
			$item= $h_bookshop_vat->create(true);
		}

		$item->setVars($_POST);
		$res = $h_bookshop_vat->insert($item);
		if($res) {
			bookshop_updateCache();
			bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=vat', 2);
		} else {
			bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=vat',5);
		}
		break;


	// ****************************************************************************************************************
	case 'deletevat':	// Suppression d'une TVA
	// ****************************************************************************************************************
        xoops_cp_header();
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(empty($id)) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
		}
		// On vérifie que cette TVA n'est pas utilisée par un livre
		$criteria = new Criteria('book_vat_id', $id, '=');
		$cnt = $h_bookshop_books->getCount($criteria);
		if($cnt == 0) {
			$item = null;
			$item = $h_bookshop_vat->get($id);
			if(is_object(($item))) {
				$critere = new Criteria('vat_id', $id, '=');
				$res = $h_bookshop_vat->deleteAll($critere);
				if($res) {
					bookshop_updateCache();
					bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=vat',2);
				} else {
					bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=vat',5);
				}
			} else {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl.'?op=vat',5);
			}
		} else {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_2, $baseurl.'?op=vat',5);
		}
		break;


	// ****************************************************************************************************************
	case 'addvat':	// Ajout d'une TVA
	case 'editvat':	// Edition d'une TVA
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(2);
		if($op == 'editvat') {
			$title = _AM_BOOKSHOP_EDIT_VAT;
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			if(empty($id)) {
				bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
			}
			// Item exits ?
			$item = null;
			$item = $h_bookshop_vat->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$edit = true;
			$label_submit = _AM_BOOKSHOP_MODIFY;
		} else {
			$title = _AM_BOOKSHOP_ADD_VAT;
			$item = $h_bookshop_vat->create(true);
			$label_submit = _AM_BOOKSHOP_ADD;
			$edit = false;
		}
		$sform = new XoopsThemeForm($title, 'frmaddvat', $baseurl);
		$sform->addElement(new XoopsFormHidden('op', 'saveeditvat'));
		$sform->addElement(new XoopsFormHidden('vat_id', $item->getVar('vat_id')));
		$sform->addElement(new XoopsFormText(_AM_BOOKSHOP_RATE,'vat_rate',10,15, $item->getVar('vat_rate','e')), true);

		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', $label_submit, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray);
		$sform = bookshop_formMarkRequiredFields($sform);
		$sform->display();
		show_footer();
		break;

	// ****************************************************************************************************************
	case 'savechunks':	// Save chunks order
	// ****************************************************************************************************************
        bookshop_set_module_option('chunk1', intval($_POST['chunk1']));
        bookshop_set_module_option('chunk2', intval($_POST['chunk2']));
        bookshop_set_module_option('chunk3', intval($_POST['chunk3']));
        bookshop_set_module_option('chunk4', intval($_POST['chunk4']));
        bookshop_updateCache();
		bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=categories');
		break;

	// ****************************************************************************************************************
	case 'addcategory':		// Ajout d'une catégorie
	case 'editcategory':	// Edition d'une catégorie
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(3);

        if($op == 'editcategory') {
			$title = _AM_BOOKSHOP_EDIT_CATEG;
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			if(empty($id)) {
				bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
			}
			// Item exits ?
			$item = null;
			$item = $h_bookshop_cat->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$edit = true;
			$label_submit = _AM_BOOKSHOP_MODIFY;
		} else {
			$title = _AM_BOOKSHOP_ADD_CATEG;
			$item = $h_bookshop_cat->create(true);
			$label_submit = _AM_BOOKSHOP_ADD;
			$edit = false;
		}
		$tbl_categories = $h_bookshop_cat->GetAllCategories();
		$mytree = new XoopsObjectTree($tbl_categories, 'cat_cid', 'cat_pid');
		$select_categ = $mytree->makeSelBox('cat_pid', 'cat_title', '-', $item->getVar('cat_pid'), true);

		$sform = new XoopsThemeForm($title, 'frmcategory', $baseurl);
		$sform->setExtra('enctype="multipart/form-data"');
		$sform->addElement(new XoopsFormHidden('op', 'saveeditcategory'));
		$sform->addElement(new XoopsFormHidden('cat_cid', $item->getVar('cat_cid')));
		$sform->addElement(new XoopsFormText(_AM_BOOKSHOP_CATEG_TITLE,'cat_title',50,255, $item->getVar('cat_title','e')), true);
		$sform->addElement(new XoopsFormLabel(_AM_BOOKSHOP_PARENT_CATEG, $select_categ), false);

		if( $op == 'editcategory' && trim($item->getVar('cat_imgurl')) != '' && file_exists(XOOPS_UPLOAD_PATH.'/'.trim($item->getVar('cat_imgurl'))) ) {
			$pictureTray = new XoopsFormElementTray(_AM_BOOKSHOP_CURRENT_PICTURE ,'<br />');
			$pictureTray->addElement(new XoopsFormLabel('', "<img src='".XOOPS_UPLOAD_URL.'/'.$item->getVar('cat_imgurl')."' alt='' border='0' />"));
			$deleteCheckbox = new XoopsFormCheckBox('', 'delpicture');
			$deleteCheckbox->addOption(1, _DELETE);
			$pictureTray->addElement($deleteCheckbox);
			$sform->addElement($pictureTray);
		}
		$sform->addElement(new XoopsFormFile(_AM_BOOKSHOP_PICTURE , 'attachedfile', bookshop_getmoduleoption('maxuploadsize')), false);
		$editor = bookshop_getWysiwygForm(_AM_BOOKSHOP_DESCRIPTION,'cat_description', $item->getVar('cat_description','e'), 15, 60, 'description_hidden');
		if($editor) {
			$sform->addElement($editor, false);
		}

		$editor2 = bookshop_getWysiwygForm(_MI_BOOKSHOP_ADVERTISEMENT,'cat_advertisement', $item->getVar('cat_advertisement','e'), 15, 60, 'advertisement_hidden');
		if($editor2) {
			$sform->addElement($editor2, false);
		}

		// META Data
		if($manual_meta) {
			$sform->addElement(new XoopsFormText(_AM_BOOKSHOP_META_KEYWORDS,'cat_metakeywords',50,255, $item->getVar('cat_metakeywords','e')), false);
			$sform->addElement(new XoopsFormText(_AM_BOOKSHOP_META_DESCRIPTION,'cat_metadescription',50,255, $item->getVar('cat_metadescription','e')), false);
			$sform->addElement(new XoopsFormText(_AM_BOOKSHOP_META_PAGETITLE,'cat_metatitle',50,255, $item->getVar('cat_metatitle','e')), false);
		}

		$button_tray = new XoopsFormElementTray('' ,'');
		$button_tray->addElement(new XoopsFormButton('', 'post', $label_submit, 'submit'));
		$sform->addElement($button_tray);
		$sform = bookshop_formMarkRequiredFields($sform);
		$sform->display();
		show_footer();
		break;


	// ****************************************************************************************************************
	case 'saveeditcategory':	// Sauvegarde d'une catégorie
	// ****************************************************************************************************************
		xoops_cp_header();
		$id = isset($_POST['cat_cid']) ? intval($_POST['cat_cid']) : 0;
		if(!empty($id)) {
			$edit = true;
			$item = $h_bookshop_cat->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$item->unsetNew();
			$add = false;
		} else {
			$item= $h_bookshop_cat->create(true);
			$add = true;
		}

		$item->setVars($_POST);

		// Suppression de l'image ?
		if(isset($_POST['delpicture']) && intval($_POST['delpicture']) == 1) {
			$item->setVar('cat_imgurl', '');
		}

		// Upload du fichier
		if(bookshop_upload(0)) {
			$item->setVar('cat_imgurl', basename($destname));
		}
		$res = $h_bookshop_cat->insert($item);
		if($res) {
			bookshop_updateCache();
			if($add) {
				$notification_handler =& xoops_gethandler('notification');
				$tags['CATEGORY_NAME'] = $item->getVar('cat_title');
				$tags['CATEGORY_URL'] = BOOKSHOP_URL.'category.php?cat_cid=' . $item->getVar('cat_cid');
				$tags['X_MODULE_URL'] = BOOKSHOP_URL;
				$notification_handler->triggerEvent('global', 0, 'new_category', $tags);
			}
			bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=categories', 2);
		} else {
			bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=categories',5);
		}
		break;

    // ****************************************************************************************************************
    case 'categories':	// List categories
    // ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(3);

		// Display categories **********************************************************************
		$tbl_categories = array();
		bookshop_htitle(_AM_BOOKSHOP_CATEGORIES,4);

		$tbl_categories = $h_bookshop_cat->GetAllCategories();
		$mytree = new XoopsObjectTree($tbl_categories, 'cat_cid', 'cat_pid');
		$select_categ = $mytree->makeSelBox('id', 'cat_title');

		echo "<div class='even'><form method='post' name='quickaccess' id='quickaccess' action='$baseurl' >"._AM_BOOKSHOP_LIST." $select_categ<input type='radio' name='op' id='op' value='editcategory' />"._EDIT." <input type='radio' name='op' id='op' value='deletecategory' />"._DELETE." <input type='submit' name='btnquick' id='btnquick' value='"._GO."' /></form></div>";
		echo "<div class='odd' align='center'><form method='post' name='frmadd' id='frmadd' action='$baseurl' ><input type='hidden' name='op' id='op' value='addcategory' /><input type='submit' name='btnadd' id='btnadd' value='"._AM_BOOKSHOP_ADD_CATEG."' /></form></div>";
		echo '<br /><br />';

		// Categories preferences *****************************************************************
        $chunk1 = bookshop_getmoduleoption('chunk1');
        $chunk2 = bookshop_getmoduleoption('chunk2');
        $chunk3 = bookshop_getmoduleoption('chunk3');
        $chunk4 = bookshop_getmoduleoption('chunk4');
		$tbl_positions = array(0 => _AM_BOOKSHOP_INVISIBLE, 1 => "1", 2 => "2", 3 => "3", 4 => "4");

		$sform = new XoopsThemeForm(_AM_BOOKSHOP_CATEG_CONFIG, 'frmchunk', $baseurl);
		$sform->addElement(new XoopsFormHidden('op', 'savechunks'));
		$sform->addElement(new XoopsFormLabel(_AM_BOOKSHOP_CHUNK, _AM_BOOKSHOP_POSITION));

		$chunk = null;
		$chunk = new XoopsFormSelect(_MI_BOOKSHOP_CHUNK1, 'chunk1', $chunk1, 1, false);
		$chunk->addOptionArray($tbl_positions);
		$sform->addElement($chunk, true);

		unset($chunk);
		$chunk = new XoopsFormSelect(_MI_BOOKSHOP_CHUNK2, 'chunk2', $chunk2, 1, false);
		$chunk->addOptionArray($tbl_positions);
		$sform->addElement($chunk, true);

		unset($chunk);
		$chunk = new XoopsFormSelect(_MI_BOOKSHOP_CHUNK3, 'chunk3', $chunk3, 1, false);
		$chunk->addOptionArray($tbl_positions);
		$sform->addElement($chunk, true);

		unset($chunk);
		$chunk = new XoopsFormSelect(_MI_BOOKSHOP_CHUNK4, 'chunk4', $chunk4, 1, false);
		$chunk->addOptionArray($tbl_positions);
		$sform->addElement($chunk, true);

		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', _AM_BOOKSHOP_OK, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray);
		$sform = bookshop_formMarkRequiredFields($sform);
		$sform->display();
        show_footer();
        break;


	// ****************************************************************************************************************
	case 'deletecategory':	// Demande de confirmation de suppression d'une catégorie
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(3);
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		if(empty($id)) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
		}
		$category = null;
		$category = $h_bookshop_cat->get($id);
		if(!is_object($category)) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_10, $baseurl, 5);
		}
		$msg = sprintf(_AM_BOOKSHOP_CONF_DEL_CATEG, $category->getVar('cat_title'));
		xoops_confirm(array( 'op' => 'confdeletecategory', 'id' => $id), 'index.php', $msg);
		break;

	// ****************************************************************************************************************
	case 'confdeletecategory':	// Suppression effective d'une catégorie
	// ****************************************************************************************************************
		xoops_cp_header();
        bookshop_adminMenu(3);
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		if(empty($id)) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
		}
		// On vérifie que cette catégorie n'est pas utilisée par des livres
		$tbl_categories = $tbl_childs = $tbl_chids_ids = array();
		$cnt = 0;
		$lst_ids = '';
		// Recherche des sous catégories de cette catégorie
		$tbl_categories = $h_bookshop_cat->GetAllCategories();
		$mytree = new XoopsObjectTree($tbl_categories, 'cat_cid', 'cat_pid');
		$tbl_childs = $mytree->getAllChild($id);
		$tbl_chids_ids[] = $id;
		if(count($tbl_childs) > 0) {
			foreach ($tbl_childs as $onechild) {
				$tbl_chids_ids[] = $onechild->getVar('cat_cid');
			}
		}
		$lst_ids = implode(',', $tbl_chids_ids);
		$criteria = new Criteria('book_cid', '('.$lst_ids.')', 'IN');
		$cnt = $h_bookshop_books->getCount($criteria);
		if($cnt == 0) {
			$item = null;
			$item = $h_bookshop_cat->get($id);
			if(is_object(($item))) {
				$critere = new Criteria('cat_cid', $id, '=');
				xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'new_category', $id);
				$res = $h_bookshop_cat->deleteAll($critere);
				if($res) {
					bookshop_updateCache();
					bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=categories',2);
				} else {
					bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=categories',5);
				}
			} else {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl.'?op=categories',5);
			}
		} else {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_4, $baseurl.'?op=categories',5);
		}
		break;

	// ****************************************************************************************************************
	case 'authors':		// Liste des auteurs / traducteurs
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(4);
		$tbl_vat = array();
		echo "<form method='post' action='$baseurl' name='frmaddauthor' id='frmaddauthor'><input type='hidden' name='op' id='op' value='addauthor' /><input type='submit' name='btngo' id='btngo' value='"._AM_BOOKSHOP_ADD_ITEM."' /></form>";
		$authorsCount = $h_bookshop_authors->getCount(new Criteria('auth_type', 1, '='));
		$translatorsCount = $h_bookshop_authors->getCount(new Criteria('auth_type', 2, '='));

		bookshop_htitle(sprintf(_AM_BOOKSHOP_AT_COUNT,$authorsCount,$translatorsCount),4);

		$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$filter = 0;
		if(isset($_POST['filter'])) {
			$filter = intval($_POST['filter']);
		} elseif(isset($_SESSION['filter'])) {
			$filter = intval($_SESSION['filter']);
		}
		$_SESSION['filter'] = $filter;
		$selected = array('','','','');
		$selected[$filter] = " selected='selected'";

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('auth_id', 0, '<>'));

		if($filter == 1) {	// Ne voir que les auteurs
			$criteria->add(new Criteria('auth_type', 1, '='));
		} elseif($filter == 2) {	// Ne voir que les traducteurs
			$criteria->add(new Criteria('auth_type', 2, '='));
		}

		$authors_count = $h_bookshop_authors->getCount($criteria);	// Recherche du nombre total d'auteurs
		$pagenav = new XoopsPageNav( $authors_count, $limit, $start, 'start', 'op=authors');

		$criteria->setLimit($limit);
		$criteria->setStart($start);
		$criteria->setSort('auth_name, auth_firstname');

		$tbl_autheurs = $h_bookshop_authors->getObjects($criteria);
		$class='';
		echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
		$form ="<form method='post' name='frmfilter' id='frmfilter' action='$baseurl'>". _AM_BOOKSHOP_LIMIT_TO." <select name='filter' id='filter'><option value='0'".$selected[0].">"._AM_BOOKSHOP_ALL."</option><option value='1'".$selected[1].">"._AM_BOOKSHOP_AUTHORS."</option><option value='2'".$selected[2].">"._AM_BOOKSHOP_TRANSLATORS."</option></select> <input type='hidden' name='op' id='op' value='authors' /><input type='submit' name='btnfilter' id='btnfilter' value='"._AM_BOOKSHOP_FILTER."' /></form>";

		echo "<tr><td colspan='2' align='left'>".$pagenav->renderNav()."</td><td align='right' colspan='3'>".$form."</td></tr>\n";
		echo "<tr><th align='center'>"._BOOKSHOP_LASTNAME."</th><th align='center'>"._BOOKSHOP_FIRSTNAME."</th><th align='center'>"._BOOKSHOP_EMAIL."</th><th align='center'>"._BOOKSHOP_TYPE."</th><th align='center'>"._AM_BOOKSHOP_ACTION."</th></tr>";
		foreach ($tbl_autheurs as $item) {
			$class = ($class == 'even') ? 'odd' : 'even';
			$action_edit = "<a href='$baseurl?op=editauthor&id=".$item->getVar('auth_id')."' title='"._BOOKSHOP_EDIT."'>".$icones['edit'].'</a>';
			$action_delete = "<a href='$baseurl?op=deleteauthor&id=".$item->getVar('auth_id')."' title='"._BOOKSHOP_DELETE."'".$conf_msg.">".$icones['delete'].'</a>';
			$type = $item->getVar('auth_type') == 1 ? _BOOKSHOP_AUTHOR : _BOOKSHOP_TRANSLATOR;
			echo "<tr class='".$class."'>\n";
			echo "<td>".$item->getVar('auth_name')."</td><td align='left'>".$item->getVar('auth_firstname')."</td><td align='center'>".$item->getVar('auth_email')."</td><td align='center'>".$type."</td><td align='center'>".$action_edit.' '.$action_delete."</td>\n";
			echo "<tr>\n";
		}
		$class = ($class == 'even') ? 'odd' : 'even';
		echo "<tr class='".$class."'>\n";
		echo "<td colspan='5' align='center'><form method='post' action='$baseurl' name='frmaddauthor' id='frmaddauthor'><input type='hidden' name='op' id='op' value='addauthor' /><input type='submit' name='btngo' id='btngo' value='"._AM_BOOKSHOP_ADD_ITEM."' /></form></td>\n";
		echo "</tr>\n";
		echo '</table>';
		echo "<div align='right'>".$pagenav->renderNav()."</div>";
        show_footer();
		break;


	// ****************************************************************************************************************
	case 'saveeditauthor':	// Sauvegarde d'un auteur / traducteur
	// ****************************************************************************************************************
		xoops_cp_header();
		$id = isset($_POST['auth_id']) ? intval($_POST['auth_id']) : 0;
		if(!empty($id)) {
			$edit = true;
			$item = $h_bookshop_authors->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$item->unsetNew();
		} else {
			$item= $h_bookshop_authors->create(true);
		}

		$item->setVars($_POST);

		if(isset($_POST['delpicture1']) && intval($_POST['delpicture1']) == 1) {
			$item->setVar('auth_photo1', '');
		}
		if(isset($_POST['delpicture2']) && intval($_POST['delpicture2']) == 1) {
			$item->setVar('auth_photo2', '');
		}
		if(isset($_POST['delpicture3']) && intval($_POST['delpicture3']) == 1) {
			$item->setVar('auth_photo3', '');
		}
		if(isset($_POST['delpicture4']) && intval($_POST['delpicture4']) == 1) {
			$item->setVar('auth_photo4', '');
		}
		if(isset($_POST['delpicture5']) && intval($_POST['delpicture5']) == 1) {
			$item->setVar('auth_photo5', '');
		}

		// Upload du fichier
		if(bookshop_upload(0)) {
			$item->setVar('auth_photo1', basename($destname));
		}

		if(bookshop_upload(1)) {
			$item->setVar('auth_photo2', basename($destname));
   		}

		if(bookshop_upload(2)) {
			$item->setVar('auth_photo3', basename($destname));
   		}

		if(bookshop_upload(3)) {
			$item->setVar('auth_photo4', basename($destname));
   		}

		if(bookshop_upload(4)) {
			$item->setVar('auth_photo5', basename($destname));
   		}
		$res = $h_bookshop_authors->insert($item);
		if($res) {
			bookshop_updateCache();
			bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=authors', 2);
		} else {
			bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=authors',5);
		}
		break;


	// ****************************************************************************************************************
	case 'deleteauthor':	// Suppression d'un auteur
	// ****************************************************************************************************************
        xoops_cp_header();
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(empty($id)) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
		}
		// On vérifie que cet auteur n'est pas utilisée par un livre
		$criteria = new Criteria('ba_auth_id', $id, '=');
		$cnt = $h_bookshop_booksauthors->getCount($criteria);
		if($cnt == 0) {
			$item = null;
			$item = $h_bookshop_authors->get($id);
			if(is_object(($item))) {
				$critere = new Criteria('auth_id', $id, '=');
				$res = $h_bookshop_authors->deleteAll($critere);
				if($res) {
					bookshop_updateCache();
					bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=authors',2);
				} else {
					bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=authors',5);
				}
			} else {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl.'?op=authors',5);
			}
		} else {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_5, $baseurl.'?op=authors',5);
		}
		break;


	// ****************************************************************************************************************
	case 'addauthor':	// Ajout d'un auteur / traducteur
	case 'editauthor':	// Edition d'un auteur / traducteur
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(4);

        if($op == 'editauthor') {
			$title = _AM_BOOKSHOP_EDIT_AUTH;
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			if(empty($id)) {
				bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
			}
			// Item exits ?
			$item = null;
			$item = $h_bookshop_authors->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$edit = true;
			$label_submit = _AM_BOOKSHOP_MODIFY;
		} else {
			$title = _AM_BOOKSHOP_ADD_AUTH;
			$item = $h_bookshop_authors->create(true);
			$item->setVar('auth_type', 1);
			$label_submit = _AM_BOOKSHOP_ADD;
			$edit = false;
		}

		$sform = new XoopsThemeForm($title, 'frmauteur', $baseurl);
		$sform->setExtra('enctype="multipart/form-data"');
		$sform->addElement(new XoopsFormHidden('op', 'saveeditauthor'));
		$sform->addElement(new XoopsFormHidden('auth_id', $item->getVar('auth_id')));
		$sform->addElement(new XoopsFormText(_BOOKSHOP_LASTNAME,'auth_name',50,255, $item->getVar('auth_name','e')), true);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_FIRSTNAME,'auth_firstname',50,255, $item->getVar('auth_firstname','e')), false);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_EMAIL,'auth_email',50,255, $item->getVar('auth_email','e')), false);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_SITEURL,'auth_url',50,255, $item->getVar('auth_url','e')), false);
		$radio_type = new XoopsFormRadio(_BOOKSHOP_TYPE, 'auth_type', $item->getVar('auth_type','e'));
		$radio_type->addOptionArray(array('1' => _BOOKSHOP_AUTHOR, '2' => _BOOKSHOP_TRANSLATOR));
		$sform->addElement($radio_type, true);

		$editor = bookshop_getWysiwygForm(_BOOKSHOP_BIO,'auth_bio', $item->getVar('auth_bio','e'), 15, 60, 'bio_hidden');
		if($editor) {
			$sform->addElement($editor, false);
		}

		if( $op == 'editauthor' && trim($item->getVar('auth_photo1')) != '' && file_exists(XOOPS_UPLOAD_PATH.'/'.trim($item->getVar('auth_photo1'))) ) {
			$pictureTray = new XoopsFormElementTray(_AM_BOOKSHOP_CURRENT_PICTURE ,'<br />');
			$pictureTray->addElement(new XoopsFormLabel('', "<img src='".XOOPS_UPLOAD_URL.'/'.$item->getVar('auth_photo1')."' alt='' border='0' />"));
			$deleteCheckbox = new XoopsFormCheckBox('', 'delpicture1');
			$deleteCheckbox->addOption(1, _DELETE);
			$pictureTray->addElement($deleteCheckbox);
			$sform->addElement($pictureTray);
			unset($pictureTray, $deleteCheckbox);
		}
		$sform->addElement(new XoopsFormFile(_AM_BOOKSHOP_PICTURE , 'attachedfile1', bookshop_getmoduleoption('maxuploadsize')), false);

		if( $op == 'editauthor' && trim($item->getVar('auth_photo2')) != '' && file_exists(XOOPS_UPLOAD_PATH.'/'.trim($item->getVar('auth_photo2'))) ) {
			$pictureTray = new XoopsFormElementTray(_AM_BOOKSHOP_CURRENT_PICTURE ,'<br />');
			$pictureTray->addElement(new XoopsFormLabel('', "<img src='".XOOPS_UPLOAD_URL.'/'.$item->getVar('auth_photo2')."' alt='' border='0' />"));
			$deleteCheckbox = new XoopsFormCheckBox('', 'delpicture2');
			$deleteCheckbox->addOption(1, _DELETE);
			$pictureTray->addElement($deleteCheckbox);
			$sform->addElement($pictureTray);
			unset($pictureTray, $deleteCheckbox);
		}
		$sform->addElement(new XoopsFormFile(_AM_BOOKSHOP_PICTURE , 'attachedfile2', bookshop_getmoduleoption('maxuploadsize')), false);

		if( $op == 'editauthor' && trim($item->getVar('auth_photo3')) != '' && file_exists(XOOPS_UPLOAD_PATH.'/'.trim($item->getVar('auth_photo3'))) ) {
			$pictureTray = new XoopsFormElementTray(_AM_BOOKSHOP_CURRENT_PICTURE ,'<br />');
			$pictureTray->addElement(new XoopsFormLabel('', "<img src='".XOOPS_UPLOAD_URL.'/'.$item->getVar('auth_photo3')."' alt='' border='0' />"));
			$deleteCheckbox = new XoopsFormCheckBox('', 'delpicture3');
			$deleteCheckbox->addOption(1, _DELETE);
			$pictureTray->addElement($deleteCheckbox);
			$sform->addElement($pictureTray);
			unset($pictureTray, $deleteCheckbox);
		}
		$sform->addElement(new XoopsFormFile(_AM_BOOKSHOP_PICTURE , 'attachedfile3', bookshop_getmoduleoption('maxuploadsize')), false);

		if( $op == 'editauthor' && trim($item->getVar('auth_photo4')) != '' && file_exists(XOOPS_UPLOAD_PATH.'/'.trim($item->getVar('auth_photo4'))) ) {
			$pictureTray = new XoopsFormElementTray(_AM_BOOKSHOP_CURRENT_PICTURE ,'<br />');
			$pictureTray->addElement(new XoopsFormLabel('', "<img src='".XOOPS_UPLOAD_URL.'/'.$item->getVar('auth_photo4')."' alt='' border='0' />"));
			$deleteCheckbox = new XoopsFormCheckBox('', 'delpicture4');
			$deleteCheckbox->addOption(1, _DELETE);
			$pictureTray->addElement($deleteCheckbox);
			$sform->addElement($pictureTray);
			unset($pictureTray, $deleteCheckbox);

		}
		$sform->addElement(new XoopsFormFile(_AM_BOOKSHOP_PICTURE , 'attachedfile4', bookshop_getmoduleoption('maxuploadsize')), false);

		if( $op == 'editauthor' && trim($item->getVar('auth_photo5')) != '' && file_exists(XOOPS_UPLOAD_PATH.'/'.trim($item->getVar('auth_photo5'))) ) {
			$pictureTray = new XoopsFormElementTray(_AM_BOOKSHOP_CURRENT_PICTURE ,'<br />');
			$pictureTray->addElement(new XoopsFormLabel('', "<img src='".XOOPS_UPLOAD_URL.'/'.$item->getVar('auth_photo5')."' alt='' border='0' />"));
			$deleteCheckbox = new XoopsFormCheckBox('', 'delpicture5');
			$deleteCheckbox->addOption(1, _DELETE);
			$pictureTray->addElement($deleteCheckbox);
			$sform->addElement($pictureTray);
			unset($pictureTray, $deleteCheckbox);
		}
		$sform->addElement(new XoopsFormFile(_AM_BOOKSHOP_PICTURE , 'attachedfile5', bookshop_getmoduleoption('maxuploadsize')), false);

		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', $label_submit, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray);
		$sform = bookshop_formMarkRequiredFields($sform);
		$sform->display();
		show_footer();
		break;


	// ****************************************************************************************************************
	case 'books':	// Gestion des livres
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(5);

		$tbl_books = $tbl_categories = $tbl_number = $tbl_tome = $tbl_author = array();

		// Récupération des données uniques
		$tbl_categories = $h_bookshop_cat->GetAllCategories(0, 0, 'cat_title', 'ASC', true);
		$tbl_numbers = $h_bookshop_books->getDistincts('book_number');
		$tbl_tome = $h_bookshop_books->getDistincts('book_tome');
		$tbl_author = $h_bookshop_authors->getDistincts('auth_name');

		$mytree = new XoopsObjectTree($tbl_categories, 'cat_cid', 'cat_pid');
		$select_categ = $mytree->makeSelBox('id', 'cat_title');

		echo "<form method='post' action='$baseurl' name='frmaddbook' id='frmaddbook'><input type='hidden' name='op' id='op' value='addbook' /><input type='submit' name='btngo' id='btngo' value='"._AM_BOOKSHOP_ADD_ITEM."' /></form>";
		echo "<br /><form method='get' action='$baseurl' name='frmaddeditbook' id='frmaddeditbook'>"._BOOKSHOP_BOOK_ID." <input type='text' name='id' id='id' value='' size='4'/> <input type='radio' name='op' id='op' value='editbook' />"._BOOKSHOP_EDIT." <input type='radio' name='op' id='op' value='deletebook' />"._BOOKSHOP_DELETE." <input type='submit' name='btngo' id='btngo' value='"._GO."' /></form>";
		bookshop_htitle(_MI_BOOKSHOP_ADMENU4, 4);

		//TODO: Ajouter les listes permettant de filtrer les livres
		$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$filter = 0;

		$filter2 = 0;
		if(isset($_POST['filter2'])) {
			$filter2 = intval($_POST['filter2']);
		} elseif(isset($_SESSION['filter2'])) {
			$filter2 = intval($_SESSION['filter2']);
		}
		$_SESSION['filter2'] = $filter2;
		$selected = array('','','','');
		$selected[$filter2] = " selected='selected'";

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('book_id', 0, '<>'));

		if($filter2 == 1) {	// Ne voir que les auteurs
			$criteria->add(new Criteria('auth_type', 1, '='));
		} elseif($filter2 == 2) {	// Ne voir que les traducteurs
			$criteria->add(new Criteria('auth_type', 2, '='));
		}

		$books_count = $h_bookshop_books->getCount($criteria);	// Recherche du nombre total d'auteurs
		$pagenav = new XoopsPageNav( $books_count, $limit, $start, 'start', 'op=books');

		$criteria->setLimit($limit);
		$criteria->setStart($start);
		$criteria->setSort('book_title');

		$tbl_vat = array();
		$tbl_vat = $h_bookshop_vat->GetAllVats();
		$tbl_books = $h_bookshop_books->getObjects($criteria);
		$class='';
		echo "<div align='left'>".$pagenav->renderNav()."</div>";
		echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
		echo "<tr><th align='center'>"._BOOKSHOP_TITLE."</th><th align='center'>"._BOOKSHOP_CATEGORY."</th><th align='center'>"._BOOKSHOP_NUMBER.'<br />'._BOOKSHOP_TOME."</th><th align='center'>"._AM_BOOKSHOP_RECOMMENDED."</th><th align='center'>"._BOOKSHOP_ONLINE."</th><th align='center'>"._BOOKSHOP_DATE."</th><th align='center'>"._BOOKSHOP_PRICE."</th><th align='center'>"._AM_BOOKSHOP_ACTION."</th></tr>";
		foreach ($tbl_books as $item) {
			$id = $item->getVar('book_id');
			$class = ($class == 'even') ? 'odd' : 'even';
			$action_edit = "<a href='$baseurl?op=editbook&id=".$id."' title='"._BOOKSHOP_EDIT."'>".$icones['edit'].'</a>';
			$action_duplicate = "<a href='$baseurl?op=copybook&id=".$id."' title='"._BOOKSHOP_DUPLICATE_BOOK."'>".$icones['copy'].'</a>';
			$action_delete = "<a href='$baseurl?op=deletebook&id=".$id."' title='"._BOOKSHOP_DELETE."'".$conf_msg.">".$icones['delete'].'</a>';
			$online = $item->getVar('book_online') == 1 ? _YES : _NO;
			echo "<tr class='".$class."'>\n";
			$prix = $item->getVar('book_price');
			$vatId = $item->getVar('book_vat_id');
			if(isset($tbl_vat[$vatId])) {
				$vatItem = $tbl_vat[$vatId];
				$tva = $vatItem->getVar('vat_rate');
				$TTC = bookshop_getTTC($prix, $tva, false);
			} else {
				$TTC = _AM_BOOKSHOP_ERROR_11;
			}

			if(floatval($item->getVar('book_discount_price')) > 0) {
				if(isset($tbl_vat[$vatId])) {
					$vatItem = $tbl_vat[$vatId];
					$tva = $vatItem->getVar('vat_rate');
					$TTC2 = bookshop_getTTC($item->getVar('book_discount_price'), $tva, false);
					$TTC = '<s>'.$TTC.'</s> '.$TTC2;
				}
			}
			if($item->isRecommended()) {	// Si le livre est recommandé, on affiche le lien qui permet d'arrêter de le recommander
				$recommended = "<a href='".$baseurl."?op=unrecommendbook&book_id=".$id."' title='"._AM_BOOKSHOP_DONOTRECOMMEND_IT."'><img alt='"._AM_BOOKSHOP_DONOTRECOMMEND_IT."' src='".BOOKSHOP_IMAGES_URL."heart_delete.png' alt='' /></a>";
			} else {	// Sinon on affiche le lien qui permet de le recommander
				$recommended = "<a href='".$baseurl."?op=recommendbook&book_id=".$id."' title='"._AM_BOOKSHOP_RECOMMEND_IT."'><img alt='"._AM_BOOKSHOP_RECOMMEND_IT."' src='".BOOKSHOP_IMAGES_URL."heart_add.png' alt='' /></a>";
			}
			echo '<td>'.$item->getVar('book_title')."</td><td align='left'>".$tbl_categories[$item->getVar('book_cid')]->getVar('cat_title')."</td><td align='center'>".$item->getVar('book_number').' / '.$item->getVar('book_tome')."</td><td align='center'>".$recommended."</td><td align='center'>".$online."</td><td align='center'>".$item->getVar('book_date')."</td><td align='right'>".$TTC."</td><td align='center'>".$action_edit.'&nbsp;'.$action_duplicate.'&nbsp;'.$action_delete."</td>\n";
			echo "<tr>\n";
		}
		$class = ($class == 'even') ? 'odd' : 'even';
		echo "<tr class='".$class."'>\n";
		echo "<td colspan='8' align='center'><form method='post' action='$baseurl' name='frmaddbook' id='frmaddbook'><input type='hidden' name='op' id='op' value='addbook' /><input type='submit' name='btngo' id='btngo' value='"._AM_BOOKSHOP_ADD_ITEM."' /></form></td>\n";
		echo "</tr>\n";
		echo '</table>';
		echo "<div align='right'>".$pagenav->renderNav()."</div>";
        show_footer();
		break;

	// ****************************************************************************************************************
	case 'recommendbook':	// Recommander un livre
	// ****************************************************************************************************************
		$opRedirect = '?op=books';
		if(isset($_GET['book_id'])) {
			$book_id = intval($_GET['book_id']);
			$book = null;
			$book = $h_bookshop_books->get($book_id);
			if(is_object($book)) {
				$book->setRecommended();
				if($h_bookshop_books->insert($book, true)) {
					bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.$opRedirect, 1);
				} else {
					bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.$opRedirect, 4);
				}
			} else {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl.$opRedirect, 4);
			}
		} else {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl.$opRedirect, 4);
		}
		break;

	// ****************************************************************************************************************
	case 'unrecommendbook':	// Arrêter de recommender un livre
	// ****************************************************************************************************************
		$opRedirect = '?op=books';
		if(isset($_GET['book_id'])) {
			$book_id = intval($_GET['book_id']);
			$book = null;
			$book = $h_bookshop_books->get($book_id);
			if(is_object($book)) {
				$book->unsetRecommended();
				if($h_bookshop_books->insert($book, true)) {
					bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.$opRedirect, 1);
				} else {
					bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.$opRedirect, 4);
				}
			} else {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl.$opRedirect, 4);
			}
		} else {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl.$opRedirect, 4);
		}
		break;

	// ****************************************************************************************************************
	case 'addbook':		// Ajout d'un livre
	case 'editbook':	// Edition d'un livre
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(5);
        global $xoopsUser;

        if($op == 'editbook') {
			$title = _AM_BOOKSHOP_EDIT_BOOK;
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			if(empty($id)) {
				bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
			}
			// Item exits ?
			$item = null;
			$item = $h_bookshop_books->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$edit = true;
			$label_submit = _AM_BOOKSHOP_MODIFY;
		} else {
			$title = _AM_BOOKSHOP_ADD_BOOK;
			$item = $h_bookshop_books->create(true);
			$label_submit = _AM_BOOKSHOP_ADD;
			$edit = false;
		}

		$tbl_categories = $h_bookshop_cat->GetAllCategories(0, 0, 'cat_title', 'ASC', true);
		if(count($tbl_categories) == 0) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_8, $baseurl, 5);
		}

		$tbl_vat = $tbl_vat_display = array();
		$tbl_vat = $h_bookshop_vat->GetAllVats(0, 0);
		if(count($tbl_vat) == 0) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_9, $baseurl, 5);
		}
		foreach($tbl_vat as $onevat) {
			$tbl_vat_display[$onevat->getVar('vat_id')] = $onevat->getVar('vat_rate');
		}

		$mytree = new XoopsObjectTree($tbl_categories, 'cat_cid', 'cat_pid');
		$select_categ = $mytree->makeSelBox('book_cid', 'cat_title', '-', $item->getVar('book_cid'));

		$sform = new XoopsThemeForm($title, 'frmbook', $baseurl);
		$sform->setExtra('enctype="multipart/form-data"');
		$sform->addElement(new XoopsFormHidden('op', 'saveeditbook'));
		$sform->addElement(new XoopsFormHidden('book_id', $item->getVar('book_id')));
		$sform->addElement(new XoopsFormHidden('book_submitter', $xoopsUser->getVar('uid')));

		$sform->addElement(new XoopsFormText(_BOOKSHOP_TITLE,'book_title',50,255, $item->getVar('book_title','e')), true);

		// Langues *************************************************************
		$tbl_lang = $tbl_lang_display = array();
		$tbl_lang = $h_bookshop_lang->GetAllLang();
		foreach($tbl_lang as $onelang) {
			$tbl_lang_display[$onelang->getVar('lang_id')] = $onelang->getVar('lang_lang');
		}
		$lang_select = new XoopsFormSelect(_BOOKSHOP_LANG, 'book_lang_id', $item->getVar('book_lang_id'));
		$lang_select->addOptionArray($tbl_lang_display);
		$sform->addElement($lang_select, true);

		$sform->addElement(new XoopsFormLabel(_AM_BOOKSHOP_CATEG_HLP, $select_categ), true);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_NUMBER,'book_number',10,60, $item->getVar('book_number','e')), false);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_TOME,'book_tome',10,50, $item->getVar('book_tome','e')), false);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_VOLUMES,'book_volumes_count',10,50, $item->getVar('book_volumes_count','e')), false);

		$sform->addElement(new XoopsFormText(_AM_BOOKSHOP_FORMAT_HLP,'book_format',50,100, $item->getVar('book_format','e')), false);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_ISBN,'book_isbn',13,13, $item->getVar('book_isbn','e')), false);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_EAN,'book_ean',13,13, $item->getVar('book_ean','e')), false);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_PAGES,'book_pages',13,13, $item->getVar('book_pages','e')), false);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_PAGES_COLLECTION,'book_pages_collection',13,13, $item->getVar('book_pages_collection','e')), false);
		$sform->addElement(new XoopsFormText(_AM_BOOKSHOP_URL_HLP,'book_url',50,255, $item->getVar('book_url','e')), false);

		// Images *************************************************************
		if( $op == 'editbook' && trim($item->getVar('book_image_url')) != '' && file_exists(XOOPS_UPLOAD_PATH.'/'.trim($item->getVar('book_image_url'))) ) {
			$pictureTray = new XoopsFormElementTray(_AM_BOOKSHOP_IMAGE1_HELP ,'<br />');
			$pictureTray->addElement(new XoopsFormLabel('', "<img src='".XOOPS_UPLOAD_URL.'/'.$item->getVar('book_image_url')."' alt='' border='0' />"));
			$deleteCheckbox = new XoopsFormCheckBox('', 'delpicture1');
			$deleteCheckbox->addOption(1, _DELETE);
			$pictureTray->addElement($deleteCheckbox);
			$sform->addElement($pictureTray);
			unset($pictureTray, $deleteCheckbox);
		}
		$sform->addElement(new XoopsFormFile(_AM_BOOKSHOP_IMAGE1_CHANGE , 'attachedfile1', bookshop_getmoduleoption('maxuploadsize')), false);

		if( $op == 'editbook' && trim($item->getVar('book_thumb_url')) != '' && file_exists(XOOPS_UPLOAD_PATH.'/'.trim($item->getVar('book_thumb_url'))) ) {
			$pictureTray = new XoopsFormElementTray(_AM_BOOKSHOP_IMAGE2_HELP ,'<br />');
			$pictureTray->addElement(new XoopsFormLabel('', "<img src='".XOOPS_UPLOAD_URL.'/'.$item->getVar('book_thumb_url')."' alt='' border='0' />"));
			$deleteCheckbox = new XoopsFormCheckBox('', 'delpicture2');
			$deleteCheckbox->addOption(1, _DELETE);
			$pictureTray->addElement($deleteCheckbox);
			$sform->addElement($pictureTray);
			unset($pictureTray, $deleteCheckbox);
		}
		$sform->addElement(new XoopsFormFile(_AM_BOOKSHOP_IMAGE2_CHANGE, 'attachedfile2', bookshop_getmoduleoption('maxuploadsize')), false);

		// En ligne ? *********************************************************
		$sform->addElement(new XoopsFormRadioYN(_BOOKSHOP_ONLINE_HLP,'book_online', $item->getVar('book_online')), true);

		// Recommandé ?
		$sform->addElement(new XoopsFormRadioYN(_AM_BOOKSHOP_RECOMMENDED,'book_isrecommended', $item->isRecommended()), false);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_DATE,'book_date',50,255, $item->getVar('book_date','e')), false);

		$date_submit = new XoopsFormTextDateSelect(_BOOKSHOP_DATE_SUBMIT, 'book_submitted', 15, $item->getVar('book_submitted','e'));
		$date_submit->setDescription(_AM_BOOKSHOP_SUBDATE_HELP);
		$sform->addElement($date_submit, false);

		$sform->addElement(new XoopsFormHidden('book_hits',$item->getVar('book_hits')));
		$sform->addElement(new XoopsFormHidden('book_rating',$item->getVar('book_rating')));
		$sform->addElement(new XoopsFormHidden('book_votes',$item->getVar('book_votes')));
		$sform->addElement(new XoopsFormHidden('book_comments',$item->getVar('book_comments')));

		// Auteurs ************************************************************
		$tbl_authors = $tbl_book_authors = $tbl_authors_d = $tbl_book_authors_d = array();
		// Recherche de tous les auteurs
		$criteria = new Criteria('auth_type', 1, '=');
		$criteria->setSort('auth_name');
		$tbl_authors = $h_bookshop_authors->getObjects($criteria);
		foreach($tbl_authors as $oneitem) {
			$tbl_authors_d[$oneitem->getVar('auth_id')] = xoops_trim($oneitem->getVar('auth_name')).' '. xoops_trim($oneitem->getVar('auth_firstname'));
		}
		// Recherche des auteurs de ce livre
		if($edit) {
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('ba_type', 1, '='));
			$criteria->add(new Criteria('ba_book_id', $item->getVar('book_id'), '='));
			$tbl_book_authors = $h_bookshop_booksauthors->getObjects($criteria);
			foreach($tbl_book_authors as $onebook) {
				$tbl_book_authors_d[] = $onebook->getVar('ba_auth_id');
			}
		}
		$author_select = new XoopsFormSelect(_BOOKSHOP_AUTHORS, 'authors', $tbl_book_authors_d, 5, true);
		$author_select->addOptionArray($tbl_authors_d);
		$author_select->setDescription(_AM_BOOKSHOP_SELECT_HLP);
		$sform->addElement($author_select, true);

		// Traducteurs ********************************************************
		$tbl_translators = $tbl_book_translators = $tbl_translators_d = $tbl_book_translators_d = array();
		// Recherche de tous les auteurs
		$criteria = new Criteria('auth_type', 2, '=');
		$criteria->setSort('auth_name');
		$tbl_translators = $h_bookshop_authors->getObjects($criteria);
		foreach($tbl_translators as $oneitem) {
			$tbl_translators_d[$oneitem->getVar('auth_id')] = xoops_trim($oneitem->getVar('auth_name')).' '. xoops_trim($oneitem->getVar('auth_firstname'));
		}
		// Recherche des traducteurs du livre *********************************
		if($edit) {
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('ba_type', 2, '='));
			$criteria->add(new Criteria('ba_book_id', $item->getVar('book_id'), '='));
			$tbl_book_translators = $h_bookshop_booksauthors->getObjects($criteria);
			foreach($tbl_book_translators as $onebook) {
				$tbl_book_translators_d[] = $onebook->getVar('ba_auth_id');
			}
		}
		$translator_select = new XoopsFormSelect(_BOOKSHOP_TRANSLATORS, 'translators', $tbl_book_translators_d, 5, true);
		$translator_select->addOptionArray($tbl_translators_d);
		$translator_select->setDescription(_AM_BOOKSHOP_SELECT_HLP);
		$sform->addElement($translator_select, false);

		// Livres relatifs ****************************************************
		$tbl_related = $tbl_book_related = $tbl_related_d = $tbl_book_related_d = array();
		// Recherche de tous les livres sauf celui-là
		$criteria = new Criteria('book_id', $item->getVar('book_id'), '<>');
		$criteria->setSort('book_title');
		$tbl_related = $h_bookshop_books->getObjects($criteria);
		foreach($tbl_related as $oneitem) {
			$tbl_related_d[$oneitem->getVar('book_id')] = xoops_trim($oneitem->getVar('book_title'));
		}
		// Recherche des livres relatifs à ce livre
		if($edit) {
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('related_book_id', $item->getVar('book_id'), '='));
			$tbl_book_related = $h_bookshop_related->getObjects($criteria);
			foreach($tbl_book_related as $onebook) {
				$tbl_book_related_d[] = $onebook->getVar('related_book_related');
			}
		}
		$related_select = new XoopsFormSelect(_BOOKSHOP_RELATED_BOOKS, 'relatedbooks', $tbl_book_related_d, 5, true);
		$related_select->setDescription(_AM_BOOKSHOP_RELATED_HELP.'<br />'._AM_BOOKSHOP_SELECT_HLP);
		$related_select->addOptionArray($tbl_related_d);
		$sform->addElement($related_select, false);
		// ********************************************************************

		// TVA ****************************************************************
		$vat_select = new XoopsFormSelect(_BOOKSHOP_VAT, 'book_vat_id', $item->getVar('book_vat_id'));
		$vat_select->addOptionArray($tbl_vat_display);
		$sform->addElement($vat_select, true);

		$TTC1 = $item->getVar('book_price','e');
		$TTC2 = $item->getVar('book_discount_price','e');
		if( $op == 'editbook' ) {
			$bookVat = null;
			$bookVat = $h_bookshop_vat->get($item->getVar('book_vat_id'));
			if(is_object($bookVat)) {
				$TTC1 = bookshop_getTTC(floatval($TTC1), $bookVat->getVar('vat_rate'), true);
				$TTC2 = bookshop_getTTC(floatval($TTC2), $bookVat->getVar('vat_rate'), true);
			}
		}
		$sform->addElement(new XoopsFormText(_BOOKSHOP_PRICE,'book_price',20,20, $TTC1), false);
		$sform->addElement(new XoopsFormText(_AM_BOOKSHOP_DISCOUNT_HLP,'book_discount_price',20,20, $TTC2), false);

		$sform->addElement(new XoopsFormText(_BOOKSHOP_SHIPPING_PRICE,'book_shipping_price',20,20, $item->getVar('book_shipping_price','e')), false);
		$sform->addElement(new XoopsFormText(_BOOKSHOP_STOCK_QUANTITY,'book_stock',10,10, $item->getVar('book_stock','e')), false);

		$alertStock = new XoopsFormText(_BOOKSHOP_STOCK_ALERT,'book_alert_stock',10,10, $item->getVar('book_alert_stock','e'));
		$alertStock->setDescription(_AM_BOOKSHOP_STOCK_HLP);
		$sform->addElement($alertStock, false);

		$editor2 = bookshop_getWysiwygForm(_BOOKSHOP_SUMMARY,'book_summary', $item->getVar('book_summary','e'), 15, 60, 'summary_hidden');
		if($editor2) {
			$sform->addElement($editor2, false);
		}

		$editor = bookshop_getWysiwygForm(_BOOKSHOP_DESCRIPTION,'book_description', $item->getVar('book_description','e'), 15, 60, 'description_hidden');
		if($editor) {
			$sform->addElement($editor, false);
		}

		// META Data
		if($manual_meta) {
			$sform->addElement(new XoopsFormText(_AM_BOOKSHOP_META_KEYWORDS,'book_metakeywords',50,255, $item->getVar('book_metakeywords','e')), false);
			$sform->addElement(new XoopsFormText(_AM_BOOKSHOP_META_DESCRIPTION,'book_metadescription',50,255, $item->getVar('book_metadescription','e')), false);
			$sform->addElement(new XoopsFormText(_AM_BOOKSHOP_META_PAGETITLE,'book_metatitle',50,255, $item->getVar('book_metatitle','e')), false);
		}

		if( $op == 'editbook' && trim($item->getVar('book_attachment')) != '' && file_exists(XOOPS_UPLOAD_PATH.'/'.trim($item->getVar('book_attachment'))) ) {
			$attachedTray = new XoopsFormElementTray(_BOOKSHOP_ATTACHED_FILE ,'<br />');
			$attachedTray->addElement(new XoopsFormLabel('', "<a href='".XOOPS_UPLOAD_URL.'/'.$item->getVar('book_attachment')."' target='_blank'>".XOOPS_UPLOAD_URL.'/'.$item->getVar('book_attachment')."</a>"));
			$deleteCheckbox = new XoopsFormCheckBox('', 'delattach');
			$deleteCheckbox->addOption(1, _DELETE);
			$attachedTray->addElement($deleteCheckbox);
			$sform->addElement($attachedTray);
			unset($attachedTray, $deleteCheckbox);

		}

		// Attached file
		$downloadFile = new XoopsFormFile(_BOOKSHOP_ATTACHED_FILE , 'attachedfile3', bookshop_getmoduleoption('maxuploadsize'));
		$downloadFile->setDescription(_AM_BOOKSHOP_ATTACHED_HLP);
		$sform->addElement($downloadFile, false);

		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', $label_submit, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray);
		$sform = bookshop_formMarkRequiredFields($sform);
		$sform->display();
		show_footer();
		break;


	// ****************************************************************************************************************
	case 'saveeditbook':	// Sauvegarde des informations d'un livre
	// ****************************************************************************************************************
		xoops_cp_header();
		$id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
		if($id > 0) {
			$edit = true;
			$item = $h_bookshop_books->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$item->unsetNew();
			$add = false;
		} else {
			$item = $h_bookshop_books->create(true);
			$edit = false;
			$add = true;
		}

		$item->setVars($_POST);
		if(isset($_POST['delpicture1']) && intval($_POST['delpicture1']) == 1) {
			$item->setVar('book_image_url', '');
		}

		if(isset($_POST['delpicture2']) && intval($_POST['delpicture2']) == 1) {
			$item->setVar('book_thumb_url', '');
		}

		if(isset($_POST['delattach']) && intval($_POST['delattach']) == 1) {
			$item->setVar('book_attachment', '');
		}

		$item->setVar('book_submitted', strtotime($_POST['book_submitted']));
		// Calcul des HT
		// On commence par récupérer la TVA
		$bookVat = null;
		$bookVat = $h_bookshop_vat->get(intval($_POST['book_vat_id']));
		if(is_object($bookVat)) {
			$item->setVar('book_price', bookshop_getHT(floatval($_POST['book_price']), $bookVat->getVar('vat_rate')));
			$item->setVar('book_discount_price', bookshop_getHT(floatval($_POST['book_discount_price']), $bookVat->getVar('vat_rate')));
		}

		// Upload du fichier
		if(bookshop_upload(0)) {
			$item->setVar('book_image_url', basename($destname));
   		}

		if(bookshop_upload(1)) {
			$item->setVar('book_thumb_url', basename($destname));
   		}

		if(bookshop_upload(2)) {
			$item->setVar('book_attachment', basename($destname));
   		}

		$res = $h_bookshop_books->insert($item);
		if($res) {
			$id = $item->getVar('book_id');
			// Notifications ******************************************************
			if($add == true) {
				if(intval($item->getVar('book_online')) == 1) {
					$notification_handler =& xoops_gethandler('notification');
					$tags['BOOK_NAME'] = $item->getVar('book_title');
					$tags['BOOK_SUMMARY'] = strip_tags($item->getVar('book_summary'));
					$tags['BOOK_URL'] = $h_bookshop_books->GetBookLink($item->getVar('book_id'), $item->getVar('book_title'));
					$notification_handler->triggerEvent('global', 0, 'new_book', $tags);
				}
			}
			// Gestion des auteurs ************************************************
			if($edit) {
				// Suppression préalable
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('ba_book_id', $id, '='));
				$criteria->add(new Criteria('ba_type', 1, '='));
				$h_bookshop_booksauthors->deleteAll($criteria);
			}
			// Puis sauvegarde des données
			if(isset($_POST['authors'])) {
				foreach ($_POST['authors'] as $id2) {
					$item2 = $h_bookshop_booksauthors->create(true);
					$item2->setVar('ba_book_id', $id);
					$item2->setVar('ba_auth_id', intval($id2));
					$item2->setVar('ba_type', 1);
					$res = $h_bookshop_booksauthors->insert($item2);
				}
			}

			// Gestion des traducteurs ********************************************
			if($edit) {
				// Suppression préalable
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('ba_book_id', $id, '='));
				$criteria->add(new Criteria('ba_type', 2, '='));
				$h_bookshop_booksauthors->deleteAll($criteria);
			}
			// Puis sauvegarde des données
			if(isset($_POST['translators'])) {
				foreach ($_POST['translators'] as $id2) {
					$item2 = $h_bookshop_booksauthors->create(true);
					$item2->setVar('ba_book_id', $id);
					$item2->setVar('ba_auth_id', intval($id2));
					$item2->setVar('ba_type', 2);
					$res = $h_bookshop_booksauthors->insert($item2);
				}
			}

			// Gestion des livres relatifs ****************************************
			if($edit) {
				// Suppression préalable
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('related_book_id', $id, '='));
				$h_bookshop_related->deleteAll($criteria);
			}
			// Puis sauvegarde des données
			if(isset($_POST['relatedbooks'])) {
				foreach ($_POST['relatedbooks'] as $id2) {
					$item2 = $h_bookshop_related->create(true);
					$item2->setVar('related_book_id', $id);
					$item2->setVar('related_book_related', intval($id2));
					$res = $h_bookshop_related->insert($item2);
				}
			}
			bookshop_updateCache();
			bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=books', 2);
		} else {
			bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=books', 5);
		}
		break;


	// ****************************************************************************************************************
	case 'copybook':	// Copier un livre
	// ****************************************************************************************************************
        xoops_cp_header();
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(empty($id)) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
		}
		$book = null;
		$book = $h_bookshop_books->get($id);
		if(is_object(($book))) {
			$newBook = $book->xoopsClone();
			$newBook->setVar('book_title', $book->getvar('book_title').' '._AM_BOOKSHOP_DUPLICATED);
			$newBook->setVar('book_id', 0);
			$newBook->setNew();
			$res = $h_bookshop_books->insert($newBook, true);
			if($res) {
				$newBookId = $newBook->getVar('book_id');
				// Copie des auteurs
				$tblTmp = array();
				$criteria  = new Criteria('ba_book_id', $book->getVar('book_id'), '=');
				$tblTmp = $h_bookshop_booksauthors->getObjects($criteria);
				foreach($tblTmp as $bookAuthor) {
					$newBookAuthor = $bookAuthor->xoopsClone();
					$newBookAuthor->setVar('ba_book_id', $newBookId);
					$newBookAuthor->setVar('ba_id', 0);
					$newBookAuthor->setNew();
					$h_bookshop_booksauthors->insert($newBookAuthor, true);
				}
				// Copie des livres relatifs
				$tblTmp = array();
				$criteria  = new Criteria('related_book_id', $book->getVar('book_id'), '=');
				$tblTmp = $h_bookshop_related->getObjects($criteria);
				foreach($tblTmp as $related) {
					$newRelated = $related->xoopsClone();
					$newRelated->setVar('related_book_id', $newBookId);
					$newRelated->setVar('related_id', 0);
					$newRelated->setNew();
					$h_bookshop_related->insert($newRelated, true);
				}
				bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=books',2);
			} else {
				bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=books',5);
			}
		}
		break;

	// ****************************************************************************************************************
	case 'deletebook':	// Suppression d'un livre
	// ****************************************************************************************************************
        xoops_cp_header();
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if($id == 0) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
		}
		$tblTmp = array();
		$tblTmp = $h_bookshop_caddy->getCommandIdFromBook($id);
		if(count($tblTmp) == 0) {
			// On commence par supprimer les commentaires
			$mid= $xoopsModule->getVar('mid');
			xoops_comment_delete($mid, $id);
			// Puis les votes
			$criteria = new Criteria('vote_book_id', $id, '=');
			$h_bookshop_votedata->deleteAll($criteria);
			// Puis les livres relatifs
			unset($criteria);
			$criteria = new Criteria('related_book_id', $id, '=');
			$h_bookshop_related->deleteAll($criteria);
			// Puis le livre
			$item = null;
			$item = $h_bookshop_books->get($id);
			if(is_object(($item))) {
				$res = $h_bookshop_books->delete($item, true);
				if($res) {
					bookshop_updateCache();
					xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'new_book', $id);
					bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=books',2);
				} else {
					bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=books',5);
				}
			} else {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl.'?op=books',5);
			}
		} else {
        	bookshop_adminMenu(5);
			bookshop_htitle(_AM_BOOKSHOP_SORRY_NOREMOVE, 4);
			$tblTmp2 = array();
			$tblTmp2 = $h_bookshop_commands->getObjects(new Criteria('cmd_id', '('.implode(',', $tblTmp).')', 'IN'), true);
			echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
			$class='';
			echo "<tr><th align='center'>"._AM_BOOKSHOP_ID."</th><th align='center'>"._AM_BOOKSHOP_DATE."</th><th align='center'>"._AM_BOOKSHOP_CLIENT."</th><th align='center'>"._AM_BOOKSHOP_TOTAL_SHIPP."</th></tr>";
			foreach ($tblTmp2 as $item) {
				$class = ($class == 'even') ? 'odd' : 'even';
				$date = formatTimestamp(strtotime($item->getVar('cmd_date')), 's');
				echo "<tr class='".$class."'>\n";
				echo "<td align='right'>".$item->getVar('cmd_id')."</td><td align='center'>".$date."</td><td align='center'>".$item->getVar('cmd_lastname').' '.$item->getVar('cmd_firstname')."</td><td align='center'>".$item->getVar('cmd_total').' '.bookshop_getmoduleoption('money_short').' / '.$item->getVar('cmd_shipping').' '.bookshop_getmoduleoption('money_short')."</td>\n";
				echo "<tr>\n";
			}
			echo '</table>';
        	show_footer();
		}
		break;


	// ****************************************************************************************************************
	case 'discount':	// Gestion des réductions
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(7);
		echo "<form method='post' action='$baseurl' name='frmadddiscount' id='frmadddiscount'><input type='hidden' name='op' id='op' value='adddiscount' /><input type='submit' name='btngo' id='btngo' value='"._AM_BOOKSHOP_ADD_ITEM."' /></form>";
		bookshop_htitle(_MI_BOOKSHOP_ADMENU6, 4);

		$tbl_discount = $tbl_groups = $tbl_on = $tbl_when = array();
		$discount_count = 0;

		$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$tbl_on = array(_BOOKSHOP_DISCOUNT4, _BOOKSHOP_DISCOUNT5, _BOOKSHOP_DISCOUNT6, _BOOKSHOP_DISCOUNT7);
		$tbl_when = array(_BOOKSHOP_DISCOUNT8, _BOOKSHOP_DISCOUNT9, _BOOKSHOP_DISCOUNT10,_BOOKSHOP_DISCOUNT15);

		$discount_count = $h_bookshop_discounts->getCount();	// Recherche du nombre total d'auteurs
		$pagenav = new XoopsPageNav( $discount_count, $limit, $start, 'start', 'op=discount');

		$criteria = new Criteria('disc_id', 0, '<>');
		$criteria->setLimit($limit);
		$criteria->setStart($start);

		// Chargement de la liste des groupes Xoops
		$member_handler =& xoops_gethandler('member');
		$tbl_groups = $member_handler->getGroupList();

		$tbl_discount = $h_bookshop_discounts->getObjects($criteria);
		$class='';
		$money = bookshop_getmoduleoption('money_short');

		echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
		echo "<tr><th align='center'>"._BOOKSHOP_GROUP."</th><th align='center'>"._BOOKSHOP_DISCOUNT1."</th><th align='center'>"._BOOKSHOP_DISCOUNT3."</th><th align='center'>"._BOOKSHOP_DISCOUNT14."</th><th align='center'>"._BOOKSHOP_DISCOUNT7."</th><th align='center'>"._AM_BOOKSHOP_ACTION."</th></tr>";
		foreach ($tbl_discount as $item) {
			$class = ($class == 'even') ? 'odd' : 'even';
			$action_edit = "<a href='$baseurl?op=editdiscount&id=".$item->getVar('disc_id')."' title='"._BOOKSHOP_EDIT."'>".$icones['edit'].'</a>';
			$action_delete = "<a href='$baseurl?op=deletediscount&id=".$item->getVar('disc_id')."' title='"._BOOKSHOP_DELETE."'".$conf_msg.">".$icones['delete'].'</a>';
			echo "<tr class='".$class."'>\n";
			$nature = $item->getVar('disc_percent_monney') == 0 ? _BOOKSHOP_DISCOUNT2 : $money;
			if($item->getVar('disc_shipping') == 0) {
				$shippings = _BOOKSHOP_DISCOUNT11;
			} else {
				$shippings = _BOOKSHOP_DISCOUNT12;
				if($item->getVar('disc_if_amount') > 0) {
					$amount = bookshop_formatMoney($item->getVar('disc_if_amount'));
					$shippings .=  ' '.sprintf(_BOOKSHOP_DISCOUNT13, $amount, $money);
				}
			}
			if($item->getVar('disc_when') != DISCOUNT_WHEN4) {
				$on_when = $tbl_when[$item->getVar('disc_when')];
			} else {
				$tblCriteriaQty = array('=','>','>=','<','<=');
				$on_when = $tbl_when[$item->getVar('disc_when')].$tblCriteriaQty[$item->getVar('disc_qty_criteria')].' '.$item->getVar('disc_qty_value');
			}
			echo '<td>'.$tbl_groups[$item->getVar('disc_group')]."</td><td align='left'>".$item->getVar('disc_amount').' '.$nature."</td><td align='center'>".$tbl_on[$item->getVar('disc_on_what')]."</td><td align='center'>".$on_when."</td><td align='center'>".$shippings."</td><td align='center'>".$action_edit.' '.$action_delete."</td>\n";
			echo "<tr>\n";
		}
		$class = ($class == 'even') ? 'odd' : 'even';
		echo "<tr class='".$class."'>\n";
		echo "<td colspan='6' align='center'><form method='post' action='$baseurl' name='frmadddiscount' id='frmadddiscount'><input type='hidden' name='op' id='op' value='adddiscount' /><input type='submit' name='btngo' id='btngo' value='"._AM_BOOKSHOP_ADD_ITEM."' /></form></td>\n";
		echo "</tr>\n";
		echo '</table>';
		echo "<div align='right'>".$pagenav->renderNav()."</div>";
        show_footer();
		break;


	// ****************************************************************************************************************
	case 'adddiscount':		// Ajout d'une promotion
	case 'editdiscount':	// Edition d'une promo
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(7);
		if($op == 'editdiscount') {
			$title = _AM_BOOKSHOP_EDIT_DISCOUNT;
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			if(empty($id)) {
				bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
			}
			// Item exits ?
			$item = null;
			$item = $h_bookshop_discounts->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$edit = true;
			$label_submit = _AM_BOOKSHOP_MODIFY;
		} else {
			$title = _AM_BOOKSHOP_ADD_DSICOUNT;
			$item = $h_bookshop_discounts->create(true);
			$label_submit = _AM_BOOKSHOP_ADD;
			$edit = false;
		}
		$money = bookshop_getmoduleoption('money_full');
		$sform = new XoopsThemeForm($title, 'frmadddiscount', $baseurl);
		$sform->addElement(new XoopsFormHidden('op', 'saveeditdiscount'));
		$sform->addElement(new XoopsFormHidden('disc_id', $item->getVar('disc_id')));

		$group_select = new XoopsFormSelect(_BOOKSHOP_GROUP, 'disc_group', $item->getVar('disc_group'));
		$member_handler =& xoops_gethandler('member');
		$tbl_groups = array();
		$tbl_groups = $member_handler->getGroupList();
		$group_select->addOptionArray($tbl_groups);
		$sform->addElement($group_select, true);


		$apply_tray = new XoopsFormElementTray(_BOOKSHOP_DISCOUNT1 ,'');
		$zone1 = new XoopsFormText('','disc_amount',10,15, $item->getVar('disc_amount','e'));
		$apply_tray->addElement($zone1, true);

		$radio_type = new XoopsFormRadio('', 'disc_percent_monney', $item->getVar('disc_percent_monney','e'));
		$radio_type->addOptionArray(array(DISCOUNT_TYPE1 => _BOOKSHOP_DISCOUNT2, DISCOUNT_TYPE2 => $money));
		$apply_tray->addElement($radio_type, true);
		$sform->addElement($apply_tray);

		$radio_on = new XoopsFormRadio(_BOOKSHOP_DISCOUNT3, 'disc_on_what', $item->getVar('disc_on_what','e'));
		$radio_on->addOptionArray(array(DISCOUNT_ON1 => _BOOKSHOP_DISCOUNT4.'<br />', DISCOUNT_ON2 => _BOOKSHOP_DISCOUNT5.'<br />', DISCOUNT_ON3 => _BOOKSHOP_DISCOUNT6.'<br />', DISCOUNT_ON4 => _BOOKSHOP_DISCOUNT7.'<br />', DISCOUNT_ON5 => _BOOKSHOP_DISCOUNT71.'<br />'));
		$sform->addElement($radio_on, true);


		$when_tray = new XoopsFormElementTray(_BOOKSHOP_DISCOUNT14 ,'');
		$radio_when = new XoopsFormRadio('', 'disc_when', $item->getVar('disc_when','e'));
		$radio_when->addOptionArray(array(DISCOUNT_WHEN1 => _BOOKSHOP_DISCOUNT8.'<br />', DISCOUNT_WHEN2 => _BOOKSHOP_DISCOUNT9.'<br />', DISCOUNT_WHEN3 => _BOOKSHOP_DISCOUNT10.'<br />', DISCOUNT_WHEN4 => _BOOKSHOP_DISCOUNT15));
		$when_tray->addElement($radio_when, true);

		$qty_when_select = new XoopsFormSelect('', 'disc_qty_criteria', $item->getVar('disc_qty_criteria'));
		$qty_when_select->addOptionArray(array('=','>','>=','<','<='));
		$when_tray->addElement($qty_when_select, false);

		$disc_qty_value = new XoopsFormText('','disc_qty_value',10,10, $item->getVar('disc_qty_value','e'));
		$when_tray->addElement($disc_qty_value, false);
		$sform->addElement($when_tray, true);

		$shipping_tray = new XoopsFormElementTray(_BOOKSHOP_DISCOUNT7 ,'');
		$radio_shipping = new XoopsFormRadio('', 'disc_shipping', $item->getVar('disc_shipping','e'));
		$radio_shipping->addOptionArray(array(DISCOUNT_SHIPPING1 => _BOOKSHOP_DISCOUNT11.'<br />', DISCOUNT_SHIPPING2 => _BOOKSHOP_DISCOUNT12.'<br />', DISCOUNT_SHIPPING3 => _BOOKSHOP_DISCOUNT121));
		$shipping_tray->addElement($radio_shipping);
		$shipping_tray->addElement(new XoopsFormText('','disc_shipping_amount',10,10, $item->getVar('disc_shipping_amount','e')), false);
		$shipping_tray->addElement(new XoopsFormText(sprintf(_BOOKSHOP_DISCOUNT1212, bookshop_getmoduleoption('money_full')),'disc_shipping_amount_next',10,10, $item->getVar('disc_shipping_amount_next','e')), false);
		$shipping_tray->addElement(new XoopsFormLabel(sprintf('<br />'._BOOKSHOP_DISCOUNT1213, bookshop_getmoduleoption('money_full')), ''), false);

		$sform->addElement($shipping_tray, true);

		$libelle = sprintf(_BOOKSHOP_DISCOUNT131, $money);
		$sform->addElement(new XoopsFormText($libelle,'disc_if_amount',10,15, $item->getVar('disc_if_amount','e')), false);

		$editor = bookshop_getWysiwygForm(_AM_BOOKSHOP_DISCOUNT_DESCR,'disc_description', $item->getVar('disc_description','e'), 15, 60, 'description_hidden');
		if($editor) {
			$sform->addElement($editor, false);
		}

		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', $label_submit, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray);
		$sform = bookshop_formMarkRequiredFields($sform);
		$sform->display();

		show_footer();
		break;

	// ****************************************************************************************************************
	case 'saveeditdiscount':
	// ****************************************************************************************************************
		xoops_cp_header();
		$id = isset($_POST['disc_id']) ? intval($_POST['disc_id']) : 0;
		if(!empty($id)) {
			$edit = true;
			$item = $h_bookshop_discounts->get($id);
			if(!is_object($item)) {
				bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl, 5);
			}
			$item->unsetNew();
		} else {
			$item= $h_bookshop_discounts->create(true);
		}
		$item->setVars($_POST);
		$res = $h_bookshop_discounts->insert($item);
		if($res) {
			bookshop_updateCache();
			bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=discount', 2);
		} else {
			bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=discount',5);
		}
		break;

	// ****************************************************************************************************************
	case 'deletediscount':
	// ****************************************************************************************************************
        xoops_cp_header();
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(empty($id)) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
		}
		$item = $h_bookshop_discounts->get($id);
		if(is_object(($item))) {
			$res = $h_bookshop_discounts->delete($item, true);
			if($res) {
				bookshop_updateCache();
				bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=discount',2);
			} else {
				bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=discount',5);
			}
		} else {
			bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl.'?op=discount',5);
		}
		break;

	// ****************************************************************************************************************
	case 'deletecommand':	// Suppression d'une commande
	// ****************************************************************************************************************
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(empty($id)) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
		}
		$item = $h_bookshop_commands->get($id);
		if(is_object(($item))) {
			$res = $h_bookshop_commands->delete($item, true);
			if($res) {
				// Suppression des caddy associés
				$criteria = new Criteria('caddy_cmd_id', $id, '=');
				$h_bookshop_caddy->deleteAll($criteria);
				bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=commands',2);
			} else {
				bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=commands',5);
			}
		} else {
			bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl.'?op=commands',5);
		}
		break;


	// ****************************************************************************************************************
	case 'validatecmd':	// Validation d'une commande
	// ****************************************************************************************************************
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(empty($id)) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
		}
		$item = $h_bookshop_commands->get($id);
		if(is_object(($item))) {
			$res = $h_bookshop_commands->validateCommand($id);
			if($res) {
				bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=commands',2);
			} else {
				bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=commands',5);
			}
		} else {
			bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl.'?op=commands',5);
		}
		break;


	// ****************************************************************************************************************
	case 'commands':	// Gestion des commandes
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(6);
		bookshop_htitle(_MI_BOOKSHOP_ADMENU5, 4);

		$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$filter3 = 0;
		if(isset($_POST['filter3'])) {
			$filter3 = intval($_POST['filter3']);
		} elseif(isset($_SESSION['filter3'])) {
			$filter3 = intval($_SESSION['filter3']);
		} else {
			$filter3 = 1;
		}
		$_SESSION['filter3'] = $filter3;
		$selected = array('','','','','','');
		$tblConditions = array(COMMAND_STATE_NOINFORMATION, COMMAND_STATE_VALIDATED, COMMAND_STATE_PENDING, COMMAND_STATE_FAILED, COMMAND_STATE_CANCELED, COMMAND_STATE_FRAUD);
		$selected[$filter3] = " selected='selected'";

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('cmd_id', 0, '<>'));
		$criteria->add(new Criteria('cmd_state', $tblConditions[$filter3], '='));
		$commandsCount = $h_bookshop_commands->getCount($criteria);	// Recherche du nombre total d'auteurs
		$pagenav = new XoopsPageNav( $commandsCount, $limit, $start, 'start', 'op=commands');
		$criteria->setSort('cmd_date');
		$criteria->setOrder('DESC');
		$criteria->setLimit($limit);
		$criteria->setStart($start);
		$tblCommands = $h_bookshop_commands->getObjects($criteria);
		$class='';
		echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
		$form ="<form method='post' name='frmfilter' id='frmfilter' action='$baseurl'>". _AM_BOOKSHOP_LIMIT_TO." <select name='filter3' id='filter3'><option value='0'".$selected[0].">"._BOOKSHOP_CMD_STATE1."</option><option value='1'".$selected[1].">"._BOOKSHOP_CMD_STATE2."</option><option value='2'".$selected[2].">"._BOOKSHOP_CMD_STATE3."</option><option value='3'".$selected[3].">"._BOOKSHOP_CMD_STATE4."</option><option value='4'".$selected[4].">"._BOOKSHOP_CMD_STATE5."</option><option value='5'".$selected[5].">"._BOOKSHOP_CMD_STATE6."</option></select> <input type='hidden' name='op' id='op' value='commands' /><input type='submit' name='btnfilter' id='btnfilter' value='"._AM_BOOKSHOP_FILTER."' /></form>";
		$confValidateCommand = bookshop_JavascriptLinkConfirm(_AM_BOOKSHOP_CONF_VALIDATE);
		echo "<tr><td colspan='2' align='left'>".$pagenav->renderNav()."</td><td><a href='$baseurl?op=csv&cmdtype=".$filter3."'>"._AM_BOOKSHOP_CSV_EXPORT."</a></td><td align='right' colspan='2'>".$form."</td></tr>\n";
		echo "<tr><th align='center'>"._AM_BOOKSHOP_ID."</th><th align='center'>"._AM_BOOKSHOP_DATE."</th><th align='center'>"._AM_BOOKSHOP_CLIENT."</th><th align='center'>"._AM_BOOKSHOP_TOTAL_SHIPP."</th><th align='center'>"._AM_BOOKSHOP_ACTION."</th></tr>";
		foreach ($tblCommands as $item) {
			$class = ($class == 'even') ? 'odd' : 'even';
			$date = formatTimestamp(strtotime($item->getVar('cmd_date')), 's');
			$action_edit = "<a target='_blank' href='detailscmd.php?id=".$item->getVar('cmd_id')."' title='"._BOOKSHOP_DETAILS."'>".$icones['details'].'</a>';
			$action_delete = "<a href='$baseurl?op=deletecommand&id=".$item->getVar('cmd_id')."' title='"._BOOKSHOP_DELETE."'".$conf_msg.">".$icones['delete'].'</a>';
			$action_vaidate = "<a target='_blank' href='$baseurl?op=validatecmd&id=".$item->getVar('cmd_id')."' ".$confValidateCommand." title='"._BOOKSHOP_VALIDATE_COMMAND."'>".$icones['ok'].'</a>';
			echo "<tr class='".$class."'>\n";
			echo "<td align='right'>".$item->getVar('cmd_id')."</td><td align='center'>".$date."</td><td align='center'>".$item->getVar('cmd_lastname').' '.$item->getVar('cmd_firstname')."</td><td align='center'>".$item->getVar('cmd_total').' '.bookshop_getmoduleoption('money_short').' / '.$item->getVar('cmd_shipping').' '.bookshop_getmoduleoption('money_short')."</td><td align='center'>".$action_vaidate.' '.$action_edit.' '.$action_delete."</td>\n";
			echo "<tr>\n";
		}
		echo '</table>';
		echo "<div align='right'>".$pagenav->renderNav()."</div>";
        show_footer();
		break;

	// ****************************************************************************************************************
	case 'csv':	// Export des commandes au format CSV
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(6);
		bookshop_htitle(_MI_BOOKSHOP_ADMENU5, 4);
		$cmd_type = intval($_GET['cmdtype']);
		$fp = fopen(XOOPS_UPLOAD_PATH.'/bookshop.csv','w');
		if($fp) {
			// Création de l'entête du fichier
			$entete1 = $entete2 = array();
			$s = '|';
			$cmd = new bookshop_commands();
			foreach($cmd->getVars() as $fieldName => $properties) {
				$entete1[] = $fieldName;
			}
			// Ajout des infos de caddy
			$cart= new bookshop_caddy();
			foreach($cart->getVars() as $fieldName => $properties) {
				$entete2[] = $fieldName;
			}
			fwrite($fp, implode($s, array_merge($entete1, $entete2))."\n");

			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('cmd_id', 0, '<>'));
			$criteria->add(new Criteria('cmd_state', $cmd_type, '='));
			$criteria->setSort('cmd_date');
			$criteria->setOrder('DESC');
			$tblCommands = $h_bookshop_commands->getObjects($criteria);
			foreach($tblCommands as $commande) {
				$tblTmp = array();
				$tblTmp = $h_bookshop_caddy->getObjects(new Criteria('caddy_cmd_id', $commande->getVar('cmd_id'), '='));
				$ligne = array();
				foreach($tblTmp as $caddy) {
					foreach($entete1 as $commandField) {
						$ligne[] = $commande->getVar($commandField);
					}
					foreach($entete2 as $caddyField) {
						$ligne[] = $caddy->getVar($caddyField);
					}
				}
				fwrite($fp, implode($s, $ligne)."\n");
			}
			fclose($fp);
			echo "<a target='_blank' href='".XOOPS_UPLOAD_URL."/bookshop.csv'>"._AM_BOOKSHOP_CSV_READY."</a>";
		} else {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_7);
		}
        show_footer();
		break;


	// ****************************************************************************************************************
	case 'newsletter':	// Création de la newsletter
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(8);
		bookshop_htitle(_MI_BOOKSHOP_ADMENU7, 4);
		include_once BOOKSHOP_PATH.'class/tree.php';
		$sform = new XoopsThemeForm(_MI_BOOKSHOP_ADMENU7, 'frmnewsletter', $baseurl);
		$dates_tray = new XoopsFormElementTray(_AM_BOOKSHOP_NEWSLETTER_BETWEEN);
		$minDate = $maxDate = 0;
		$h_bookshop_books->getMinMaxPublishedDate($minDate, $maxDate);
		$date1 = new XoopsFormTextDateSelect('', 'date1',15,$minDate);
		$date2 = new XoopsFormTextDateSelect(_AM_BOOKSHOP_EXPORT_AND, 'date2',15,$maxDate);
		$dates_tray->addElement($date1);
		$dates_tray->addElement($date2);
		$sform->addElement($dates_tray);
		$tbl_categories = $h_bookshop_cat->GetAllCategories();
		$mytree = new Bookshop_XoopsObjectTree($tbl_categories, 'cat_cid', 'cat_pid');
		$htmlSelect = $mytree->makeSelBox('cat_cid', 'cat_title', '-', 0, _AM_BOOKSHOP_ALL);
		$sform->addElement(new XoopsFormLabel(_AM_BOOKSHOP_IN_CATEGORY, $htmlSelect), true);
		$sform->addElement(new XoopsFormHidden('op', 'launchnewsletter'), false);
		$sform->addElement(new XoopsFormRadioYN(_AM_BOOKSHOP_REMOVE_BR, 'removebr',1),false);
		$sform->addElement(new XoopsFormRadioYN(_AM_BOOKSHOP_NEWSLETTER_HTML_TAGS, 'removehtml',0),false);
		$sform->addElement(new XoopsFormTextArea(_AM_BOOKSHOP_NEWSLETTER_HEADER, 'header', '', 4, 70), false);
		$sform->addElement(new XoopsFormTextArea(_AM_BOOKSHOP_NEWSLETTER_FOOTER, 'footer', '', 4, 70), false);
		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', _SUBMIT, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray);
		$sform = bookshop_formMarkRequiredFields($sform);
		$sform->display();
        show_footer();
		break;

	// ****************************************************************************************************************
	case 'launchnewsletter':	// Création effective de la newsletter
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(8);
		bookshop_htitle(_MI_BOOKSHOP_ADMENU7, 4);

		$newslettertemplate = '';
		if (file_exists(BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/newsletter.php')) {
			include_once BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/newsletter.php';
		} else {
			include_once BOOKSHOP_PATH.'language/english/newsletter.php';
		}
		echo '<br />';
		$removebr = $removehtml = false;
		$removebr = isset($_POST['removebr']) ? intval($_POST['removebr']) : 0;
		$removehtml = isset($_POST['removehtml']) ? intval($_POST['removehtml']) : 0;
		$header = isset($_POST['header']) ? $_POST['header'] : '';
		$footer = isset($_POST['footer']) ? $_POST['footer'] : '';
		$date1 = strtotime($_POST['date1']);
		$date2 = strtotime($_POST['date2']);
		$cat_id = intval($_POST['cat_cid']);
		$tblBooks = $tblCategories = array();
		$tblBooks = $h_bookshop_books->getBooksForNewsletter($date1, $date2, $cat_id);
		$newsfile = XOOPS_ROOT_PATH.'/uploads/bookshop_newsletter.txt';
		$tblCategories = $h_bookshop_cat->GetAllCategories(0, 0, 'cat_title', 'ASC', true);
		$tblVat = $h_bookshop_vat->GetAllVats();

		$fp = fopen($newsfile,'w');
		if(!$fp) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_7, $baseurl.'?op=newsletter', 5);
		}
		if(xoops_trim($header) != '') {
			fwrite($fp, $header);
		}
		foreach($tblBooks as $item) {
			$content = $newslettertemplate;
			$tblTmp = $tblTmp2 = array();
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('ba_book_id', $item->getVar('book_id'), '='));
			$criteria->add(new Criteria('ba_type', 1, '='));
			$tblTmp = $h_bookshop_booksauthors->getObjects($criteria);
			foreach($tblTmp as $bookAuthor) {
				$tblTmp2[] = $bookAuthor->getVar('ba_auth_id');
			}
			$tblAuthors = $h_bookshop_authors->getObjects(new Criteria('auth_id', '('.implode(',', $tblTmp2).')', 'IN'), true);
			$tblTmp = array();
			foreach($tblAuthors as $auteur) {
				$tblTmp[] = $auteur->getVar('auth_firstname').' '.$auteur->getVar('auth_name');
			}

			$search_pattern = array('%title%','%category%','%author%','%published%','%price%','%money%','%hometext%','%fulltext%','%discountprice%','%link%','%book_number%','%book_tome%','%book_format%','%book_date%','%book_shipping_price%','%book_stock%','%book_isbn%','%book_ean%', '%book_pages%','%book_pages_collection%', '%book_volumes_count%');
			$replace_pattern = array($item->getVar('book_title'),$tblCategories[$item->getVar('book_cid')]->getVar('cat_title'),implode(', ', $tblTmp),formatTimestamp($item->getVar('book_submitted'),'s'),bookshop_getTTC($item->getVar('book_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate')),bookshop_getmoduleoption('money_full'),$item->getVar('book_summary'),$item->getVar('book_description'),bookshop_getTTC($item->getVar('book_discount_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate')),$h_bookshop_books->GetBookLink($item->getVar('book_id'), $item->getVar('book_title')),$item->getVar('book_number'),$item->getVar('book_tome'),$item->getVar('book_format'),$item->getVar('book_date'),$item->getVar('book_shipping_price'),$item->getVar('book_stock'),$item->getVar('book_isbn'),$item->getVar('book_ean'),$item->getVar('book_pages'),$item->getVar('book_pages_collection'),$item->getVar('book_volumes_count'));
			$content = str_replace($search_pattern, $replace_pattern, $content);
			if($removebr) {
				$content = str_replace('<br />',"\r\n",$content);
			}
			if($removehtml) {
				$content = strip_tags($content);
			}
			fwrite($fp,$content);
		}
		if(xoops_trim($footer) != '') {
			fwrite($fp, $footer);
		}
		fclose($fp);
		$newsfile = XOOPS_URL.'/uploads/bookshop_newsletter.txt';
		echo "<a href='$newsfile' target='_blank'>"._AM_BOOKSHOP_NEWSLETTER_READY."</a>";
        show_footer();
		break;


	// ****************************************************************************************************************
	case 'lowstock':	// Stock bas
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(10);
		bookshop_htitle(_MI_BOOKSHOP_ADMENU9, 4);
		$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$criteria = new CriteriaCompo();
		// Recherche des livres dont la quantité en stock est inférieure ou égale à la quantité d'alerte et ou la quantité d'alerte est supérieure à 0
		$books_count = $h_bookshop_books->getLowStocksCount();
		$pagenav = new XoopsPageNav( $books_count, $limit, $start, 'start', 'op=lowstock');
		$tbl_books = $h_bookshop_books->getLowStocks($start, $limit);
		$class = $name = '';
		$names = array();
		echo "<form name='frmupdatequant' id='frmupdatequant' method='post' action='$baseurl'><input type='hidden' name='op' id='op' value='updatequantities' />";
		echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
		echo "<tr><th align='center'>"._BOOKSHOP_TITLE."</th><th align='center'>"._BOOKSHOP_STOCK_QUANTITY."</th><th align='center'>"._BOOKSHOP_STOCK_ALERT."</th><th align='center'>"._AM_BOOKSHOP_NEW_QUANTITY."</th></tr>";
		foreach ($tbl_books as $item) {
			$class = ($class == 'even') ? 'odd' : 'even';
			$link = "<a href='".BOOKSHOP_URL."book.php?book_id=".$item->getVar('book_id')."'>".$item->getVar('book_title').'</a>';
			echo "<tr class='".$class."'>\n";
			$name = 'qty_'.$item->getVar('book_id');
			$names[] = $item->getVar('book_id');
			echo "<td>".$link."</td><td align='right'>".$item->getVar('book_stock')."</td><td align='right'>".$item->getVar('book_alert_stock')."</td><td align='center'><input type='text' name='$name' id='$name' size='3' maxlength='5' value='' /></td>\n";
			echo "<tr>\n";
		}
		$class = ($class == 'even') ? 'odd' : 'even';
		if( count($names) > 0 ) {
			echo "<tr class='$class'><td colspan='3' align='center'>&nbsp;</td><td align='center'><input type='hidden' name='names' id='names' value='".implode('|',$names)."' /><input type='submit' name='btngo' id='btngo' value='"._AM_BOOKSHOP_UPDATE_QUANTITIES."' /></td></tr>";
		}
		echo '</table></form>';
		echo "<div align='right'>".$pagenav->renderNav()."</div>";
        show_footer();
		break;

	// ****************************************************************************************************************
	case 'updatequantities':	// Mise à jour des quantités des livres
	// ****************************************************************************************************************
		$names = array();
		if(isset($_POST['names'])) {
			$names = explode('|',$_POST['names']);
			foreach($names as $item) {
				$name = 'qty_'.$item;
				if(isset($_POST[$name]) && xoops_trim($_POST[$name]) != '') {
					$quantity = intval($_POST[$name]);
					$book_id = intval($item);
					$book = null;
					$book = $h_bookshop_books->get($book_id);
					if(is_object($book)) {
						$h_bookshop_books->updateAll('book_stock', $quantity, new Criteria('book_id', $book_id, '='), true);
					}
				}
			}
		}
		bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=lowstock', 2);
		break;

	// ****************************************************************************************************************
	case 'deleterating':	// Delete a rating
	// ****************************************************************************************************************
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(empty($id)) {
			bookshop_redirect(_AM_BOOKSHOP_ERROR_1, $baseurl, 5);
		}
		$item = $h_bookshop_votedata->get($id);
		if(is_object(($item))) {
			$res = $h_bookshop_votedata->delete($item, true);
			if($res) {
				$book_id = $item->getVar('vote_book_id');
				$book = null;
				$book = $h_bookshop_books->get($book_id);
				if(is_object($book)) {	// Update Book's rating
					$totalVotes = $sumRating = $ret = $finalrating = 0;
					$ret = $h_bookshop_votedata->getCountRecordSumRating($book->getVar('book_id'), $totalVotes, $sumRating);
					if($totalVotes > 0 ) {
						$finalrating = $sumRating / $totalVotes;
						$finalrating = number_format($finalrating, 4);
					}
					$h_bookshop_books->updateRating($book_id, $finalrating, $totalVotes);
				}
				bookshop_redirect(_AM_BOOKSHOP_SAVE_OK, $baseurl.'?op=dashboard',2);
			} else {
				bookshop_redirect(_AM_BOOKSHOP_SAVE_PB, $baseurl.'?op=dashboard',5);
			}
		} else {
			bookshop_redirect(_AM_BOOKSHOP_NOT_FOUND, $baseurl.'?op=dashboard',5);
		}
		break;

	// ****************************************************************************************************************
	case 'email':	// Envoyer un email aux clients
	// ****************************************************************************************************************
		break;

	// ****************************************************************************************************************
	case 'default':
	case 'dashboard':
	// ****************************************************************************************************************
        xoops_cp_header();
        bookshop_adminMenu(0);
		bookshop_htitle(_MI_BOOKSHOP_ADMENU10, 4);
		$itemsCount = 5;	// Nombre d'éléments à afficher
		echo "<table border='0' width='100%' cellpadding='2' cellspacing='2'>";
		echo "<tr>\n";
		// Dernières commandes ************************************************
		echo "<td valign='top' width='50%'><b>"._AM_BOOKSHOP_LAST_ORDERS."</b>";
		$tblTmp = array();
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('cmd_id', 0, '<>'));
		$criteria->setSort('cmd_date');
		$criteria->setOrder('DESC');
		$criteria->setLimit($itemsCount);
		$criteria->setStart(0);
		$tblTmp = $h_bookshop_commands->getObjects($criteria);
		echo "<table border='0' cellpadding='2' cellspacing='2' width='100%'>";
		echo "<tr><th align='center'>"._AM_BOOKSHOP_DATE."</th><th align='center'>"._AM_BOOKSHOP_ID."</th><th align='center'>"._BOOKSHOP_TOTAL."</th></tr>\n";
		foreach($tblTmp as $item) {
			$date = formatTimestamp(strtotime($item->getVar('cmd_date')), 's');
			echo "<tr><td align='center'>".$date."</td><td align='center'>".$item->getVar('cmd_id')."</td><td align='right'>".$item->getVar('cmd_total').' '.bookshop_getmoduleoption('money_short')."</td></tr>";
		}
		echo "</table>";

		// Stocks bas *********************************************************
		echo "</td><td valign='top' width='50%'><b>"._MI_BOOKSHOP_ADMENU9."</b>";
		$tblTmp = array();
		$tblTmp = $h_bookshop_books->getLowStocks(0, $itemsCount);
		echo "<table border='0' cellpadding='2' cellspacing='2' width='100%'>";
		echo "<tr><th align='center'>"._BOOKSHOP_TITLE."</th><th align='center'>"._BOOKSHOP_STOCK_QUANTITY."</th></tr>\n";
		foreach($tblTmp as $item) {
			$link = "<a href='".BOOKSHOP_URL."book.php?book_id=".$item->getVar('book_id')."'>".$item->getVar('book_title').'</a>';
			echo "<tr><td>".$link."</td><td align='right'>".$item->getVar('book_stock')."</td></tr>";
		}
		echo "</table>";
		echo "</td></tr>";

		echo "<tr><td colspan='2'>&nbsp;</td></tr>";

		// Livres les plus vendus *********************************************
		echo "<td valign='top' width='50%'><b>"._MI_BOOKSHOP_BNAME4."</b>";
		$tblTmp = $tblTmp2 = array();
		$tblTmp2 = $h_bookshop_caddy->getMostSoldBooksInCategory(0, 0, $itemsCount, true);
		$tblTmp = $h_bookshop_books->getObjects(new Criteria('book_id', '('.implode(',', array_keys($tblTmp2)).')', 'IN'), true);
		echo "<table border='0' cellpadding='2' cellspacing='2' width='100%'>";
		echo "<tr><th align='center'>"._BOOKSHOP_TITLE."</th><th align='center'>"._BOOKSHOP_QUANTITY."</th></tr>\n";
		foreach($tblTmp2 as $key => $value) {
			$item = $tblTmp[$key];
			$link = "<a href='".BOOKSHOP_URL."book.php?book_id=".$item->getVar('book_id')."'>".$item->getVar('book_title').'</a>';
			echo "<tr><td>".$link."</td><td align='right'>".$value."</td></tr>";
		}
		echo "</table>";
		// Livres les plus vus ************************************************
		$tblTmp = array();
		$tblTmp = $h_bookshop_books->getMostViewedBooks(0, $itemsCount);
		echo "</td><td valign='top' width='50%'><b>"._MI_BOOKSHOP_BNAME2."</b>";
		echo "<table border='0' cellpadding='2' cellspacing='2' width='100%'>";
		echo "<tr><th align='center'>"._BOOKSHOP_TITLE."</th><th align='center'>"._BOOKSHOP_HITS."</th></tr>\n";
		foreach($tblTmp as $item) {
			$link = "<a href='".BOOKSHOP_URL."book.php?book_id=".$item->getVar('book_id')."'>".$item->getVar('book_title').'</a>';
			echo "<tr><td>".$link."</td><td align='right'>".$item->getVar('book_hits')."</td></tr>";
		}
		echo "</table>";
		echo "</td></tr>";

		echo "<tr><td colspan='2'>&nbsp;</td></tr>";

		// Derniers votes *****************************************************
		echo "</td><td colspan='2' valign='top'><b>"._AM_BOOKSHOP_LAST_VOTES."</b>";
		$tblTmp = $tblTmp2 = $tblTmp3 = array();
		$tblTmp3 = $h_bookshop_votedata->getLastVotes(0, $itemsCount);
		foreach($tblTmp3 as $item) {
			$tblTmp2[] = $item->getVar('vote_book_id');
		}
		$tblTmp = $h_bookshop_books->getObjects(new Criteria('book_id', '('.implode(',', $tblTmp2).')', 'IN'), true);
		echo "<table border='0' cellpadding='2' cellspacing='2' width='100%'>";
		echo "<tr><th align='center'>"._BOOKSHOP_TITLE."</th><th align='center'>"._AM_BOOKSHOP_DATE."</th><th colspan='2' align='center'>"._AM_BOOKSHOP_NOTE."</th></tr>";
		foreach($tblTmp3 as $vote) {
			$item = $tblTmp[$vote->getVar('vote_book_id')];
			$link = "<a href='".BOOKSHOP_URL."book.php?book_id=".$item->getVar('book_id')."'>".$item->getVar('book_title').'</a>';
			$action_delete = "<a href='$baseurl?op=deleterating&id=".$vote->getVar('vote_ratingid')."' title='"._BOOKSHOP_DELETE."'".$conf_msg.">".$icones['delete'].'</a>';
			echo "<tr><td>".$link."</td><td align='right'>".formatTimestamp($vote->getVar('vote_ratingtimestamp'), 's')."</td><td align='right'>".$vote->getVar('vote_rating')."</td><td>".$action_delete."</td></tr>";
		}
		echo "</table>";
		echo "</td></tr>";

		echo "</table>";
		show_footer();
		break;

}
xoops_cp_footer();
?>