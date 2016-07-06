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
 *  List of all the books of the catalog (depending on the module parameters)
 */
include __DIR__ . '/header.php';
$GLOBALS['current_category']  = -1;
$xoopsOption['template_main'] = 'bookshop_allbooks.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$tblCategories = $tblVat = array();

// Read the VAT
$tblVat = $h_bookshop_vat->GetAllVats();
// Read Categories
$tblCategories = $h_bookshop_cat->GetAllCategories();
// Module Preferences
$xoopsTpl->assign('mod_pref', $mod_pref);

// Read books
$tblBooks = array();
$tblBooks = $h_bookshop_books->getRecentBooks(0, 0, 0, 'book_title');
foreach ($tblBooks as $item) {
    $tbl_tmp                  = array();
    $tbl_tmp                  = $item->toArray();
    $tbl_tmp['book_category'] = isset($tblCategories[$item->getVar('book_cid')]) ? $tblCategories[$item->getVar('book_cid')]->toArray() : null;
    if (isset($tblVat[$item->getVar('book_vat_id')])) {
        $tbl_tmp['book_price_ttc']          = bookshop_getTTC($item->getVar('book_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
        $tbl_tmp['book_discount_price_ttc'] = bookshop_getTTC($item->getVar('book_discount_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
    } else {
        $tbl_tmp['book_price_ttc']          = 0;
        $tbl_tmp['book_discount_price_ttc'] = 0;
    }
    $xoopsTpl->append('books', $tbl_tmp);
}

$xoopsTpl->assign('pdf_catalog', bookshop_getmoduleoption('pdf_catalog'));

bookshop_setCSS();
if (file_exists(BOOKSHOP_PATH . 'language/' . $xoopsConfig['language'] . '/modinfo.php')) {
    include_once BOOKSHOP_PATH . 'language/' . $xoopsConfig['language'] . '/modinfo.php';
} else {
    include_once BOOKSHOP_PATH . 'language/english/modinfo.php';
}

$title = _MI_BOOKSHOP_SMNAME6 . ' - ' . bookshop_get_module_name();
bookshop_set_metas($title, $title);
include_once(XOOPS_ROOT_PATH . '/footer.php');
