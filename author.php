<?php
//  ------------------------------------------------------------------------ //
//                      BOOKSHOP - MODULE FOR XOOPS 2                        //
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
 * Page d'informations sur un auteur (ou un traducteur)
 */
include __DIR__ . '/header.php';
$GLOBALS['current_category']  = -1;
$xoopsOption['template_main'] = 'bookshop_author.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
//The tests  **************************************************************************************
// Find the No copyrightr
if (isset($_GET['auth_id'])) {
    $auth_id = (int)$_GET['auth_id'];
} else {
    bookshop_redirect(_BOOKSHOP_ERROR7, 'index.php', 5);
}
// The author is?
$author = null;
$author = $h_bookshop_authors->get($auth_id);
if (!is_object($author)) {
    bookshop_redirect(_BOOKSHOP_ERROR7, 'index.php', 5);
}

if ($author->getVar('auth_type') == 1) {
    $auth_type = _BOOKSHOP_AUTHOR;
} else {
    $auth_type = _BOOKSHOP_TRANSLATOR;
}
$xoopsTpl->assign('mod_pref', $mod_pref);    // Module Preferences
$tbl_tmp                          = array();
$tbl_tmp                          = $author->toArray();
$tbl_tmp['auth_type_description'] = $auth_type;
//"<a href='".$h_bookshop_authors->GetAuthorLink($item->getVar('auth_id'), $item->getVar('auth_name'), $item->getVar('auth_firstname'))."' title='".bookshop_makeHrefTitle($item->getVar('auth_firstname').' '.$item->getVar('auth_name'))."'>".$item->getVar('auth_firstname').' '.$item->getVar('auth_name')."</a>";
$xoopsTpl->assign('author', $tbl_tmp);

// Find books by this author / translator
$criteria    = new Criteria('ba_auth_id', $auth_id, '=');
$tblBooksIds = $tblTmp2 = $tblBooksAuthor = array();
$tblBooksIds = $h_bookshop_booksauthors->getObjects($criteria);
foreach ($tblBooksIds as $item) {
    $tblTmp2[] = $item->getVar('ba_book_id');
}
if (count($tblTmp2) > 0) {
    $critere = new Criteria('book_id', '(' . implode(',', $tblTmp2) . ')', 'IN');
    $critere->setLimit(bookshop_getmoduleoption('perpage'));
    $tblBooksAuthor = $h_bookshop_books->getObjects($critere, true);
    $tblVAT         = array();
    $tblVAT         = $h_bookshop_vat->GetAllVats(0, 0, 'vat_id', 'ASC', true);
    $cpt            = 1;
    $tbl_categories = $h_bookshop_cat->GetAllCategories();
    foreach ($tblBooksAuthor as $item) {
        $tbl_book                            = array();
        $tbl_book                            = $item->toArray();
        $tbl_book['count']                   = $cpt;
        $tbl_book['book_category']           = isset($tbl_categories[$item->getVar('book_cid')]) ? $tbl_categories[$item->getVar('book_cid')]->toArray() : null;
        $vatRate                             = $tblVAT[$item->getVar('book_vat_id')]->getVar('vat_rate');
        $tbl_book['book_price_ttc']          = bookshop_getTTC($item->getVar('book_price'), $vatRate);
        $tbl_book['book_discount_price_ttc'] = bookshop_getTTC($item->getVar('book_discount_price'), $vatRate);
        $xoopsTpl->append('books', $tbl_book);
        ++$cpt;
    }
}

bookshop_setCSS();
if (file_exists(BOOKSHOP_PATH . 'language/' . $xoopsConfig['language'] . '/modinfo.php')) {
    include_once BOOKSHOP_PATH . 'language/' . $xoopsConfig['language'] . '/modinfo.php';
} else {
    include_once BOOKSHOP_PATH . 'language/english/modinfo.php';
}

$title = $auth_type . ' : ' . $author->getVar('auth_name') . ' ' . $author->getVar('auth_firstname') . ' - ' . bookshop_get_module_name();
bookshop_set_metas($title, $title, bookshop_createmeta_keywords($author->getVar('auth_name') . ' ' . $author->getVar('auth_firstname') . ' ' . $author->getVar('auth_bio')));
include_once XOOPS_ROOT_PATH . '/footer.php';
