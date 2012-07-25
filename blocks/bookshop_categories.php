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
 * Affiche le bloc des catégories en fonction de la catégorie en cours (fonctionne de paire avec les pages du module)
 */
function b_bookshop_category_show($options)
{
	global $xoopsTpl;
	include XOOPS_ROOT_PATH.'/modules/bookshop/include/common.php';
	$block = array();
	$url = BOOKSHOP_URL.'include/bookshop.css';
	$xoopsTpl->assign("xoops_module_header", "<link rel=\"stylesheet\" type=\"text/css\" href=\"$url\" />");
	$block['nostock_msg'] = bookshop_getmoduleoption('nostock_msg');

	if(intval($options[0]) == 0) {
		$block['block_option'] = 0;
		if(!isset($GLOBALS['current_category']) || $GLOBALS['current_category'] == -1) {
			return false;
		}
		$cat_cid = intval($GLOBALS['current_category']);
		include XOOPS_ROOT_PATH.'/modules/bookshop/include/common.php';

		if($cat_cid > 0 ) {
			include_once XOOPS_ROOT_PATH.'/class/tree.php';
			$tbl_categories = $tblChilds = $tbl_tmp = array();
			$tbl_categories = $h_bookshop_cat->GetAllCategories();
			$mytree = new XoopsObjectTree($tbl_categories, 'cat_cid', 'cat_pid');
			$tblChilds = $mytree->getAllChild($cat_cid);
			//$tblChilds = array_reverse($tblChilds);
			foreach($tblChilds as $item) {
				$tbl_tmp[] = "<a href='".$h_bookshop_cat->GetCategoryLink($item->getVar('cat_cid'), $item->getVar('cat_title'))."' title='".bookshop_makeHrefTitle($item->getVar('cat_title'))."'>".$item->getVar('cat_title')."</a>";
			}
			$block['block_categories'] = $tbl_tmp;

			$category = null;
			if($cat_cid > 0) {
				$category = $h_bookshop_cat->get($cat_cid);
				if(is_object($category)) {
					$block['block_current_category'] = $category->toArray();
				}
			}
		} else {	// On est à la racine, on n'affiche donc que les catégories mères
			$tbl_categories = array();
			$criteria = new Criteria('cat_pid', 0, '=');
			$criteria->setSort('cat_title');
			$tbl_categories = $h_bookshop_cat->getObjects($criteria, true);
			foreach($tbl_categories as $item) {
				$tbl_tmp[] = "<a href='".$h_bookshop_cat->GetCategoryLink($item->getVar('cat_cid'), $item->getVar('cat_title'))."' title='".bookshop_makeHrefTitle($item->getVar('cat_title'))."'>".$item->getVar('cat_title')."</a>";
			}
			$block['block_categories'] = $tbl_tmp;
		}
	} else {	// Affichage classique
		$block['block_option'] = 1;
		include XOOPS_ROOT_PATH.'/modules/bookshop/include/common.php';
		include_once BOOKSHOP_PATH.'class/tree.php';
		$tbl_categories = $h_bookshop_cat->GetAllCategories();
		$mytree = new Bookshop_XoopsObjectTree($tbl_categories, 'cat_cid', 'cat_pid');
		$jump = BOOKSHOP_URL."category.php?cat_cid=";
		$additional = "onchange='location=\"".$jump."\"+this.options[this.selectedIndex].value'";
		if(isset($GLOBALS['current_category']) && $GLOBALS['current_category'] != -1) {
			$cat_cid = intval($GLOBALS['current_category']);
		} else {
			$cat_cid = 0;
		}
		$htmlSelect = $mytree->makeSelBox('cat_cid', 'cat_title', '-', $cat_cid, false, 0, $additional);
		$block['htmlSelect'] = $htmlSelect;
	}
	return $block;
}

function b_bookshop_category_edit($options)
{
	global $xoopsConfig;
	include XOOPS_ROOT_PATH.'/modules/bookshop/include/common.php';

	$checkeds = array('','');
	$checkeds[$options[0]] = 'checked';
	$form = '';
	$form .= '<b>'._MB_BOOKSHOP_TYPE_BLOCK."</b><br /><input type='radio' name='options[]' id='options[]' value='0' ".$checkeds[0]." />"._MB_BOOKSHOP_TYPE_BLOCK2."<br /><input type='radio' name='options[]' id='options[]' value='1' ".$checkeds[1]." />"._MB_BOOKSHOP_TYPE_BLOCK1.'</td></tr>';
	return $form;
}

/**
 * Bloc à la volée
 */
function b_bookshop_category_duplicatable($options)
{
	$options = explode('|',$options);
	$block = & b_bookshop_category($options);

	$tpl = new XoopsTpl();
	$tpl->assign('block', $block);
	$tpl->display('db:bookshop_block_categories.html');
}

?>