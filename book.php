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
 * Affichage d'un livre
 */
include 'header.php';
include_once XOOPS_ROOT_PATH.'/class/tree.php';

$book_id = 0;
// Les tests **************************************************************************************
// Recherche du n° de livre
if(isset($_GET['book_id'])) {
	$book_id = intval($_GET['book_id']);
} else {
	bookshop_redirect(_BOOKSHOP_ERROR1, 'index.php', 5);
}
// Le livre existe ?
$book = null;
$book = $h_bookshop_books->get($book_id);
if(!is_object($book)) {
	bookshop_redirect(_BOOKSHOP_ERROR1, 'index.php', 5);
}

// Le livre est en ligne ?
if($book->getVar('book_online') == 0) {
	bookshop_redirect(_BOOKSHOP_ERROR2, 'index.php', 5);
}

// Le livre est publié ?
if(bookshop_getmoduleoption('show_unpublished') == 0 && $book->getVar('book_submitted') > time()) {
	bookshop_redirect(_BOOKSHOP_ERROR3, 'index.php', 5);
}

// Faut il afficher les livres même lorsqu'ils ne sont plus en stock ?
if(bookshop_getmoduleoption('nostock_display') == 0 && $book->getVar('book_stock') == 0) {
	if(xoops_trim(bookshop_getmoduleoption('nostock_display')) != '') {
		bookshop_redirect(bookshop_getmoduleoption('nostock_display'), 'index.php', 5);
	}
}
// Fin des tests, si on est encore là c'est que tout est bon **************************************
$title = strip_tags($book->getVar('book_title')).' - '.bookshop_get_module_name();

if(!isset($_GET['op'])) {
	$xoopsOption['template_main'] = 'bookshop_book.html';
	$GLOBALS['current_category'] = $book->getVar('book_cid');
	include_once XOOPS_ROOT_PATH.'/header.php';
} elseif(isset($_GET['op']) && $_GET['op'] == 'print') {	// Version imprimable de la page
	$GLOBALS['current_category'] = 0;
	$xoopsConfig['sitename'] = $title;
	xoops_header(false);
	// Inclusion de la feuille de style du module
	$url = BOOKSHOP_URL.'include/bookshop.css';
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$url\" />";
	echo "</head><body>";
	if(!isset($xoopsTpl)) {
		include_once XOOPS_ROOT_PATH.'/class/template.php';
		$xoopsTpl = new XoopsTpl();
	}
}

if(isset($_GET['stock']) && $_GET['stock'] == 'add' && bookshop_isMemberOfGroup(bookshop_getmoduleoption('grp_qty'))) {
	$h_bookshop_books->increaseStock($book);
}

if(isset($_GET['stock']) && $_GET['stock'] == 'substract' && bookshop_isMemberOfGroup(bookshop_getmoduleoption('grp_qty'))) {
	$h_bookshop_books->decreaseStock($book);
	$h_bookshop_books->verifyLowStock($book);
}


if(!is_object($xoopsUser)){
	$currentUser = 0;
}else{
	$currentUser = $xoopsUser->getVar('uid');
}

$baseurl = BOOKSHOP_URL.basename(__FILE__).'?book_id='.$book->getVar('book_id');

// Quelques options pour le template
$xoopsTpl->assign('baseurl', $baseurl);
$xoopsTpl->assign('nostock_msg', bookshop_getmoduleoption('nostock_msg'));
$xoopsTpl->assign('mod_pref', $mod_pref);	// Préférences du module
$xoopsTpl->assign('icones', $icones);
$xoopsTpl->assign('canRateBooks', bookshop_getmoduleoption('ratebooks'));	// Préférences du module
$xoopsTpl->assign('mail_link', 'mailto:?subject='.sprintf(_BOOKSHOP_INTARTICLE,$xoopsConfig['sitename']).'&amp;body='.sprintf(_BOOKSHOP_INTARTFOUND, $xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/bookshop/book.php?book_id='.$book_id);
$xoopsTpl->assign('canChangeQuantity', bookshop_isMemberOfGroup(bookshop_getmoduleoption('grp_qty')));	// Groupe autorisé à modifier les quantités depuis la page
$xoopsTpl->assign('BookStockQuantity', sprintf(_BOOKSHOP_QUANTITY_STOCK,$book->getVar('book_stock')));

// Recherche de la catégorie du livre
$tbl_tmp = $tbl_categories = $tbl_ancestors = array();
$tbl_categories = $h_bookshop_cat->GetAllCategories();
$book_category = null;
$book_category = isset($tbl_categories[$book->getVar('book_cid')]) ? $tbl_categories[$book->getVar('book_cid')] : null;
if(!is_object($book_category)) {
	bookshop_redirect(_BOOKSHOP_ERROR4, 'index.php', 5);
}

// Recherche de sa langue
$book_langue = null;
$book_langue = $h_bookshop_lang->get($book->getVar('book_lang_id'));
if(!is_object($book_langue)) {
	bookshop_redirect(_BOOKSHOP_ERROR5, 'index.php', 5);
}

// Chargement de toutes les TVA
$tblVat = array();
$tblVat = $h_bookshop_vat->GetAllVats();

// Recherche de sa TVA
$book_vat = null;
if(isset($tblVat[$book->getVar('book_vat_id')])) {
	$book_vat = $tblVat[$book->getVar('book_vat_id')];
}
if(!is_object($book_vat)) {
	bookshop_redirect(_BOOKSHOP_ERROR6, 'index.php', 5);
}

// Recherche de l'utilisateur qui a soumit ce livre
$book_user = null;
$user_handler = $member_handler =& xoops_gethandler('user');
$book_user = $user_handler->get($book->getVar('book_submitter'), true);
$xoopsTpl->assign('book_submitter', $book_user);

// Recherche des traducteurs et traducteurs du livre **********************************************
$tbl_auteurs = $tbl_translators = $tbl_tmp = $tbl_tmp2 = $tbl_join1 = $tbl_join2 = array();
$criteria = new Criteria('ba_book_id', $book->getVar('book_id'), '=');
$tbl_tmp = $h_bookshop_booksauthors->getObjects($criteria, true);
foreach($tbl_tmp as $id => $item) {
	$tbl_tmp2[] = $item->getVar('ba_auth_id');
}
if(count($tbl_tmp2) > 0 ) {
	$tbl_books_auteurs = array();
	$tbl_auteurs = $h_bookshop_authors->getObjects(new Criteria('auth_id', '('.implode(',', $tbl_tmp2).')', 'IN'), true);
	foreach($tbl_auteurs as $item) {
		if($item->getVar('auth_type') == 1 ) {	// Auteur
			$xoopsTpl->append('book_authors', $item->toArray());
			$tbl_join1[] = "<a href='".$h_bookshop_authors->GetAuthorLink($item->getVar('auth_id'), $item->getVar('auth_name'), $item->getVar('auth_firstname'))."' title='".bookshop_makeHrefTitle($item->getVar('auth_firstname').' '.$item->getVar('auth_name'))."'>".$item->getVar('auth_firstname').' '.$item->getVar('auth_name')."</a>";
		} else {		// Traducteur
			$xoopsTpl->append('book_translators', $item->toArray());
			$tbl_join2[] = "<a href='".$h_bookshop_authors->GetAuthorLink($item->getVar('auth_id'), $item->getVar('auth_name'), $item->getVar('auth_firstname'))."' title='".bookshop_makeHrefTitle($item->getVar('auth_firstname').' '.$item->getVar('auth_name'))."'>".$item->getVar('auth_firstname').' '.$item->getVar('auth_name')."</a>";
		}
	}
}
if(count($tbl_join1) > 0) {
	$xoopsTpl->assign('book_joined_authors', implode(', ', $tbl_join1));
}
if(count($tbl_join2) > 0) {
	$xoopsTpl->assign('book_joined_translators', implode(', ', $tbl_join2));
}

// Recherche des livres relatifs ******************************************************************
$tbl_tmp = $tbl_tmp2 = array();
$criteria = new Criteria('related_book_id', $book->getVar('book_id'), '=');
$tbl_tmp = $h_bookshop_related->getObjects($criteria);
if(count($tbl_tmp) > 0 ) {
	foreach($tbl_tmp as $item) {
		$tbl_tmp2[] = $item->getVar('related_book_related');
	}
	$tbl_related_books = array();
	$tbl_related_books = $h_bookshop_books->getObjects(new Criteria('book_id', '('.implode(',', $tbl_tmp2).')', 'IN'), true);
	if(count($tbl_related_books) > 0) {
		$cpt = 1;
		foreach($tbl_related_books as $item) {
			$tbl_tmp = $item->toArray();
			$tbl_tmp['count'] = $cpt;
			$tbl_tmp['book_category'] = isset($tbl_categories[$item->getVar('book_cid')]) ? $tbl_categories[$item->getVar('book_cid')]->toArray() : null;
			$tbl_tmp['book_price_ttc'] = bookshop_getTTC($item->getVar('book_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
			$tbl_tmp['book_discount_price_ttc'] = bookshop_getTTC($item->getVar('book_discount_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate') );
			$xoopsTpl->append('book_related_books', $tbl_tmp);
			$cpt++;
		}
	}
}

// Informations du livre **************************************************************************
$tbl_tmp = array();
$tbl_tmp = $book->toArray();
$tbl_tmp['book_category'] = $book_category->toArray();
$tbl_tmp['book_language'] = $book_langue->toArray();
if(xoops_trim($book_user->getVar('name')) != '') {
	$name = $book_user->getVar('name');
} else {
	$name = $book_user->getVar('uname');
}
$tbl_tmp['book_submiter_name'] = $name;
$linkeduser = '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$book_user->getVar('uid').'">'. $name.'</a>';
$tbl_tmp['book_submiter_link'] = $name;
$tbl_tmp['book_vat_rate'] = $book_vat->toArray();
$tbl_tmp['book_price_ttc'] = bookshop_getTTC($book->getVar('book_price'), $book_vat->getVar('vat_rate'));
$tbl_tmp['book_discount_price_ttc'] = bookshop_getTTC($book->getVar('book_discount_price'), $book_vat->getVar('vat_rate'));

$tbl_tmp['book_rating_formated'] = number_format($book->getVar('book_rating'), 2);
if ($book->getVar('book_votes') == 1) {
	$tbl_tmp['book_votes_count'] = _BOOKSHOP_ONEVOTE;
} else {
	$tbl_tmp['book_votes_count'] = sprintf(_BOOKSHOP_NUMVOTES,$book->getVar('book_votes'));
}
// Assignation du livre
$xoopsTpl->assign('book', $tbl_tmp);

// Breadcrumb *************************************************************************************
$tbl_tmp = array();
$mytree = new XoopsObjectTree($tbl_categories, 'cat_cid', 'cat_pid');
$tbl_ancestors = $mytree->getAllParent($book->getVar('book_cid'));
$tbl_ancestors = array_reverse($tbl_ancestors);
$tbl_tmp[] = "<a href='".BOOKSHOP_URL."index.php' title='".bookshop_makeHrefTitle(bookshop_get_module_name())."'>".bookshop_get_module_name()."</a>";
foreach($tbl_ancestors as $item) {
	$tbl_tmp[] = "<a href='".$h_bookshop_cat->GetCategoryLink($item->getVar('cat_cid'), $item->getVar('cat_title'))."' title='".bookshop_makeHrefTitle($item->getVar('cat_title'))."'>".$item->getVar('cat_title')."</a>";

}
// Ajout de la catégorie courante
$tbl_tmp[] = "<a href='".$h_bookshop_cat->GetCategoryLink($book_category->getVar('cat_cid'), $book_category->getVar('cat_title'))."' title='".bookshop_makeHrefTitle($book_category->getVar('cat_title'))."'>".$book_category->getVar('cat_title')."</a>";
$tbl_tmp[] = $book->getVar('book_title');
$breadcrumb = implode(' &raquo; ', $tbl_tmp);
$xoopsTpl->assign('breadcrumb', $breadcrumb);


// Maj compteur de lectures ***********************************************************************
if($book->getVar('book_submitter') != $currentUser) {
	$h_bookshop_books->addCounter($book_id);
}


// Livres précédents et suivants ******************************************************************
if(bookshop_getmoduleoption('showprevnextlink') == 1) {
	$xoopsTpl->assign('showprevnextlink', true);
	// Recherche du livre suivant le livre en cours.
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('book_online', 1, '='));
	if(bookshop_getmoduleoption('show_unpublished') == 0) {	// Ne pas afficher les livres qui ne sont pas publiés
		$criteria->add(new Criteria('book_submitted', time(), '<='));
	}
	if(bookshop_getmoduleoption('nostock_display') == 0) {	// Se limiter aux seuls livres encore en stock
		$criteria->add(new Criteria('book_stock', 0, '>'));
	}
	$criteria->add(new Criteria('book_id', $book->getVar('book_id'),'>'));
	$criteria->setOrder('DESC');
	$criteria->setSort('book_submitted');
	$criteria->setLimit(1);
	$tbl = array();
	$tbl = $h_bookshop_books->getObjects($criteria);
	if(count($tbl) == 1 ) {	// Trouvé
		$tmpBook = null;
		$tmpBook = $tbl[0];
	   	$xoopsTpl->assign('next_book_id',$tmpBook->getVar('book_id'));
   		$xoopsTpl->assign('next_book_title',$tmpBook->getVar('book_title'));
		$xoopsTpl->assign('next_book_url_rewrited', $h_bookshop_books->GetBookLink($tmpBook->getVar('book_id'), $tmpBook->getVar('book_title')));
		$xoopsTpl->assign('next_book_href_title', bookshop_makeHrefTitle($tmpBook->getVar('book_title')));
	} else {
		$xoopsTpl->assign('next_book_id', 0);
	}

	// Recherche du livre précédant le livre en cours.
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('book_online', 1, '='));
	if(bookshop_getmoduleoption('show_unpublished') == 0) {	// Ne pas afficher les livres qui ne sont pas publiés
		$criteria->add(new Criteria('book_submitted', time(), '<='));
	}
	if(bookshop_getmoduleoption('nostock_display') == 0) {	// Se limiter aux seuls livres encore en stock
		$criteria->add(new Criteria('book_stock', 0, '>'));
	}
	$criteria->add(new Criteria('book_id', $book->getVar('book_id'),'<'));
	$criteria->setOrder('DESC');
	$criteria->setSort('book_submitted');
	$criteria->setLimit(1);
	$tbl = array();
	$tbl = $h_bookshop_books->getObjects($criteria);
	if(count($tbl) == 1 ) {	// Trouvé
		$tmpBook = null;
		$tmpBook = $tbl[0];
	   	$xoopsTpl->assign('previous_book_id',$tmpBook->getVar('book_id'));
   		$xoopsTpl->assign('previous_book_title',$tmpBook->getVar('book_title'));
		$xoopsTpl->assign('previous_book_url_rewrited', $h_bookshop_books->GetBookLink($tmpBook->getVar('book_id'), $tmpBook->getVar('book_title')));
		$xoopsTpl->assign('previous_book_href_title', bookshop_makeHrefTitle($tmpBook->getVar('book_title')));
	} else {
		$xoopsTpl->assign('previous_book_id', 0);
	}
} else {
	$xoopsTpl->assign('showprevnextlink', false);
}
// x derniers livres toutes catégories confondues *************************************************
$count = bookshop_getmoduleoption('summarylast');
$xoopsTpl->assign('summarylast', $count);
if($count > 0) {
	$tblTmp = array();
	$tblTmp = $h_bookshop_books->getRecentBooks(0 , $count);
	foreach($tblTmp as $item) {
		$datas = array('last_categs_book_title' => $item->getVar('book_title'),
						'last_categs_book_url_rewrited' => $h_bookshop_books->GetBookLink($item->getVar('book_id'), $item->getVar('book_title')),
						'last_categs_book_href_title' => bookshop_makeHrefTitle($item->getVar('book_title')));
		$xoopsTpl->append('book_all_categs', $datas);
	}
	unset($tblTmp);
}

// x derniers livres dans cette catégorie *********************************************************
$count = bookshop_getmoduleoption('summarycategory');
$xoopsTpl->assign('summarycategory', $count);
if($count > 0) {
	$tblTmp = array();
	$tblTmp = $h_bookshop_books->getRecentBooks(0 , $count, $book->getVar('book_cid'));
	foreach($tblTmp as $item) {
		$datas = array('last_categ_book_title' => $item->getVar('book_title'),
						'last_categ_book_url_rewrited' => $h_bookshop_books->GetBookLink($item->getVar('book_id'), $item->getVar('book_title')),
						'last_categ_book_href_title' => bookshop_makeHrefTitle($item->getVar('book_title')));
		$xoopsTpl->append('book_current_categ', $datas);
	}
	unset($tblTmp);
}

// Deux c'est mieux *******************************************************************************
$count = bookshop_getmoduleoption('better_together');
$xoopsTpl->assign('better_together', $count);
if($count > 0) {
	$bookWith = 0;
	// On recherche le livre qui s'est le plus vendu avec ce livre
	$bookWith = $h_bookshop_caddy->getBestWith($book->getVar('book_id'));
	if($bookWith > 0) {
		$tmpBook = null;
		$tmpBook = $h_bookshop_books->get($bookWith);
		if(is_object($tmpBook)) {
			$tmp = array();
			$tmp = $tmpBook->toArray();
			$tmp['book_price_ttc'] = bookshop_getTTC($tmpBook->getVar('book_price'), $tblVat[$tmpBook->getVar('book_vat_id')]->getVar('vat_rate') );
			$tmp['book_discount_price_ttc'] = bookshop_getTTC($tmpBook->getVar('book_discount_price'), $tblVat[$tmpBook->getVar('book_vat_id')]->getVar('vat_rate') );
			$xoopsTpl->assign('bestwith', $tmp);
		}
	}
}

// Notation livre *********************************************************************************
if(bookshop_getmoduleoption('ratebooks') == 1 ) {
	$ip = bookshop_IP();
	$canRate = true;
	if ($currentUser != 0) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('vote_book_id', $book->getVar('book_id'), '='));
		$criteria->add(new Criteria('vote_uid', $currentUser, '='));
		$cnt = 0;
		$cnt = $h_bookshop_votedata->getCount($criteria);
		if($cnt > 0 ) {
			$canRate = false;
		}
	} else {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('vote_book_id', $book->getVar('book_id'), '='));
		$criteria->add(new Criteria('vote_uid', 0, '='));
		$criteria->add(new Criteria('vote_ratinghostname', $ip, '='));
		$criteria->add(new Criteria('vote_ratingtimestamp', $yesterday, '>'));
		$cnt = $h_bookshop_votedata->getCount($criteria);
		if($cnt > 0 ) {
			$canRate = false;
		}
	}
	$xoopsTpl->assign('userCanRate', $canRate);
}

// Meta et CSS ************************************************************************************
bookshop_setCSS();

if(bookshop_getmoduleoption('manual_meta')) {
	$pageTitle = xoops_trim($book->getVar('book_metatitle')) == '' ? $title : $book->getVar('book_metatitle');
	$metaDescription = xoops_trim($book->getVar('book_metadescription')) != '' ? $book->getVar('book_metadescription') : $title;
	$metaKeywords = xoops_trim($book->getVar('book_metakeywords')) != '' ? $book->getVar('book_metakeywords') : bookshop_createmeta_keywords($book->getVar('book_title').' '.$book->getVar('book_summary').' '.$book->getVar('book_description'));
	bookshop_set_metas($pageTitle, $metaDescription, $metaKeywords);
} else {
	bookshop_set_metas($title, $title, bookshop_createmeta_keywords($book->getVar('book_title').' '.$book->getVar('book_summary').' '.$book->getVar('book_description')));
}

if(!isset($_GET['op'])) {
	include_once XOOPS_ROOT_PATH.'/include/comment_view.php';
	include_once XOOPS_ROOT_PATH.'/footer.php';
} elseif(isset($_GET['op']) && $_GET['op'] == 'print') {	// Version imprimable de la page
	$xoopsTpl->display('db:bookshop_book.html');
	xoops_footer();
}
?>