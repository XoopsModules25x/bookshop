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
 * Affiche x livre(s) au hasard
 */
function b_bookshop_random_show($options)
{
	// '10|0';	// Voir 10 livres, pour toutes les catégories ou une catégorie particulière
	global $xoopsConfig, $xoopsTpl;
	include XOOPS_ROOT_PATH.'/modules/bookshop/include/common.php';
	$tblLivres = $tblCategories = $tblTmp = $tbl_tmp_vat = $tbl_vat = $tbl_tmp_lang = $block = $tbl_books_id = array();
	$tblLivres = $h_bookshop_books->getRandomBooks(0, $options[0], $options[1]);
	if(count($tblLivres) > 0) {
		$url = BOOKSHOP_URL.'include/bookshop.css';
		$block['nostock_msg'] = bookshop_getmoduleoption('nostock_msg');
		$xoopsTpl->assign("xoops_module_header", "<link rel=\"stylesheet\" type=\"text/css\" href=\"$url\" />");
		$tblTmp = array();
		foreach($tblLivres as $item) {
			$tblTmp[] = $item->getVar('book_cid');
			$tbl_tmp_vat[] = $item->getVar('book_vat_id');
			$tbl_tmp_lang[] = $item->getVar('book_lang_id');
			$tbl_books_id[] = $item->getVar('book_id');
		}
		$tblTmp = array_unique($tblTmp);
		$tbl_tmp_vat = array_unique($tbl_tmp_vat);
		$tbl_tmp_lang = array_unique($tbl_tmp_lang);

		sort($tbl_tmp_lang);
		sort($tblTmp);
		sort($tbl_tmp_vat);
		sort($tbl_books_id);

		// Récupération des langues *******************************************
		if(count($tbl_tmp_lang) > 0 ) {
			$tbl_lang = $h_bookshop_lang->getObjects(new Criteria('lang_id', '('.implode(',', $tbl_tmp_lang).')', 'IN'), true);
		}

		// Récupération de la liste des catégories ****************************
		$tblCategories = $h_bookshop_cat->getObjects(new Criteria('cat_cid', '('.implode(',', $tblTmp).')', 'IN'), true);

		// Récupération des TVA ***********************************************
		if(count($tbl_tmp_vat) > 0 ) {
			$tbl_vat = $h_bookshop_vat->getObjects(new Criteria('vat_id', '('.implode(',', $tbl_tmp_vat).')', 'IN'), true);
		}

		// Récupération des auteurs, deuxième partie **************************
		$tbl_books_auteurs = array();
		$tbl_auteurs = $h_bookshop_booksauthors->getObjects(new Criteria('ba_book_id', '('.implode(',', $tbl_books_id).')', 'IN'), true);
		if(count($tbl_auteurs) > 0 ) {
			foreach($tbl_auteurs as $item) {
				$tbl_tmp_auteurs[]  = $item->getVar('ba_auth_id');
				// Regroupement des données par livre
				$tbl_books_auteurs[$item->getVar('ba_book_id')][] = $item;
			}
			$tbl_tmp_auteurs = array_unique($tbl_tmp_auteurs);
			sort($tbl_tmp_auteurs);
			// Puis on récupère les informations de ces auteurs/traducteurs
			$tbl_infos_auteurs = $h_bookshop_authors->getObjects(new Criteria('auth_id', '('.implode(',', $tbl_tmp_auteurs).')', 'IN'), true);
		}
		foreach($tblLivres as $item) {
			$tbl_tmp = array();
			$tbl_tmp = $item->toArray();
			$tbl_tmp['book_category'] = $tblCategories[$item->getVar('book_cid')];
			$tbl_tmp['book_language'] = $tbl_lang[$item->getVar('book_lang_id')];
			$tbl_tmp['book_vat_rate'] = $tbl_vat[$item->getVar('book_vat_id')];
			$tbl_tmp['book_price_ttc'] = bookshop_getTTC($item->getVar('book_price'), $tbl_vat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
			$tbl_tmp['book_discount_price_ttc'] = bookshop_getTTC($item->getVar('book_discount_price'), $tbl_vat[$item->getVar('book_vat_id')]->getVar('vat_rate'));

			// Recherche des auteurs & des traducteurs
			$tbl_join1 = $tbl_join2 = array();
			$tbl_tmp2 = $tbl_books_auteurs[$item->getVar('book_id')];	// Renvoie la liste de tous les auteurs/traducteurs d'un livre
			$tbl_livre_auteurs = $tbl_livre_traducteurs = array();
			foreach($tbl_tmp2 as $oneauthor) {
				$auteur = $tbl_infos_auteurs[$oneauthor->getVar('ba_auth_id')];
				if($oneauthor->getVar('ba_type') == 1) {
					$tbl_livre_auteurs[] = $auteur->toArray();
					$tbl_join1[] = $auteur->getVar('auth_firstname').' '.$auteur->getVar('auth_name');
				} else {
					$tbl_livre_traducteurs[] = $auteur->toArray();
					$tbl_join2[] = $auteur->getVar('auth_firstname').' '.$auteur->getVar('auth_name');
				}
			}
			if(count($tbl_join1) > 0) {
				$tbl_tmp['book_joined_authors'] = implode(', ', $tbl_join1);
			}
			if(count($tbl_join2) > 0) {
				$tbl_tmp['book_joined_translators'] = implode(',', $tbl_join2);
			}
			$tbl_tmp['book_authors'] = $tbl_livre_auteurs;
			$tbl_tmp['book_translators'] = $tbl_livre_traducteurs;
			// Et on place le tout dans le template
			$block['block_books'][] = $tbl_tmp;
		}
		return $block;
	} else {	// La liste des livres est introuvable (on ne trouve pas les livres vendus dans le stock des livres)
		return false;
	}
}

/**
 * Paramètres du bloc
 */
function b_bookshop_random_edit($options)
{
	// '10|0';	// Voir 10 livres, pour toutes les catégories
	global $xoopsConfig;
	include XOOPS_ROOT_PATH.'/modules/bookshop/include/common.php';
	include_once BOOKSHOP_PATH.'class/tree.php';
	$tblCategories = array();
	$tblCategories = $h_bookshop_cat->GetAllCategories();
	$mytree = new Bookshop_XoopsObjectTree($tblCategories, 'cat_cid', 'cat_pid');
	$form = '';
	$checkeds = array('','');
	$checkeds[$options[1]] = 'checked';
	$form .= "<table border='0'>";
	$form .= '<tr><td>'._MB_BOOKSHOP_BOOKS_CNT . "</td><td><input type='text' name='options[]' id='options' value='".$options[0]."' /></td></tr>";
	//$form .= '<tr><td>'._MB_BOOKSHOP_SORT_ORDER."</td><td><input type='radio' name='options[]' id='options[]' value='0' ".$checkeds[0]." />"._MB_BOOKSHOP_SORT_1." <input type='radio' name='options[]' id='options[]' value='1' ".$checkeds[1]." />"._MB_BOOKSHOP_SORT_2.'</td></tr>';
	$select = $mytree->makeSelBox('options[]', 'cat_title', '-', $options[1], _MB_BOOKSHOP_ALL_CATEGORIES);
	$form .= '<tr><td>'._MB_BOOKSHOP_CATEGORY.'</td><td>'.$select.'</td></tr>';
	$form .= '</table>';
	return $form;
}

/**
 * Bloc à la volée
 */
function b_bookshop_random_show_duplicatable($options)
{
	$options = explode('|',$options);
	$block = & b_bookshop_random_show($options);

	$tpl = new XoopsTpl();
	$tpl->assign('block', $block);
	$tpl->display('db:bookshop_block_random.html');
}
?>