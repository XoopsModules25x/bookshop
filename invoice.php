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
 * Visualization of an invoice on the screen
 */
include __DIR__ . '/header.php';
$GLOBALS['current_category']  = -1;
$xoopsOption['template_main'] = 'bookshop_bill.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

if (isset($_GET['command'])) {
    $cmd_id = (int)$_GET['command'];
} else {
    bookshop_redirect(_BOOKSHOP_ERROR11, 'index.php', 6);
}
if (isset($_GET['pass'])) {
    $pass = $_GET['pass'];
} else {
    bookshop_redirect(_BOOKSHOP_ERROR11, 'index.php', 6);
}
$commande = null;
$commande = $h_bookshop_commands->get($cmd_id);
if (!is_object($commande)) {
    bookshop_redirect(_BOOKSHOP_ERROR11, 'index.php', 6);
}
// Check password
if ($pass != $commande->getVar('cmd_password')) {
    bookshop_redirect(_BOOKSHOP_ERROR11, 'index.php', 6);
}

if ($commande->getVar('cmd_state') != COMMAND_STATE_VALIDATED) {    // Commande non valid�e
    bookshop_redirect(_BOOKSHOP_ERROR12, 'index.php', 6);
}

$dec      = bookshop_getmoduleoption('decimals_count');
$tblCaddy = $tblTmp = $tblBooks = $tblVat = $tblAuthors = $tblTmp2 = $tbl_country = array();

$tbl_country = XoopsLists::getCountryList();

// Retrieve the VAT *********************************************************************************************
$tblVat = $h_bookshop_vat->GetAllVats();
// Retrieve the associated caddy  ************************************************************************************
$tblCaddy = $h_bookshop_caddy->getObjects(new Criteria('caddy_cmd_id', $cmd_id, '='), true);
if (count($tblCaddy) == 0) {
    bookshop_redirect(_BOOKSHOP_ERROR11, 'index.php', 6);
}
foreach ($tblCaddy as $item) {
    $tblTmp[] = $item->getVar('caddy_book_id');
}
// Search books ***********************************************************************************************
$tblBooks = $h_bookshop_books->getObjects(new Criteria('book_id', '(' . implode(',', $tblTmp) . ')', 'IN'), true);

// Search Authors  **********************************************************************************************
// We did not include translators for this part
$tblAuthors = $tblTmpAuth = array();
$criteria   = new CriteriaCompo();
$criteria->add(new Criteria('ba_book_id', '(' . implode(',', $tblTmp) . ')', 'IN'));
$criteria->add(new Criteria('ba_type', 1, '='));
$tblTmp2 = $h_bookshop_booksauthors->getObjects($criteria, true);
$tblTmp  = array();
foreach ($tblTmp2 as $item) {
    $tblTmp[]                                  = $item->getVar('ba_auth_id');
    $tblTmpAuth[$item->getVar('ba_book_id')][] = $item;
}
$tblAuthors = $h_bookshop_authors->getObjects(new Criteria('auth_id', '(' . implode(',', $tblTmp) . ')', 'IN'), true);

// Ordering information ***************************************************************************************
$tblTmp                       = $commande->toArray();
$tblTmp['country_label']      = $tbl_country[$commande->getVar('cmd_country')];
$tblTmp['cmd_date_timestamp'] = strtotime($commande->getVar('cmd_date'));
$xoopsTpl->assign('commande', $tblTmp);

// Loop through the caddy  ************************************************************************************************
foreach ($tblCaddy as $itemCaddy) {
    $tblTmp  = array();
    $item    = $tblBooks[$itemCaddy->getVar('caddy_book_id')];
    $tblTmp  = $item->toArray();
    $tblTmp2 = $tblTmpAuth[$itemCaddy->getVar('caddy_book_id')];
    $tblJoin = array();
    foreach ($tblTmp2 as $item2) {
        $auteur    = $tblAuthors[$item2->getVar('ba_auth_id')];
        $tblJoin[] = $auteur->getVar('auth_firstname') . ' ' . $auteur->getVar('auth_name');
    }
    if (count($tblJoin) > 0) {
        $tblTmp['book_joined_authors'] = implode(', ', $tblJoin);
    }
    $tblTmp['book_price_ttc']       = sprintf('%0.' . bookshop_getmoduleoption('decimals_count') . 'f', $itemCaddy->getVar('caddy_price'));
    $tblTmp['book_shipping_amount'] = sprintf('%0.' . bookshop_getmoduleoption('decimals_count') . 'f', $itemCaddy->getVar('caddy_shipping'));
    $tblTmp['book_qty']             = $itemCaddy->getVar('caddy_qte');
    $xoopsTpl->append('books', $tblTmp);
}

$xoopsTpl->assign('commandAmountTTC', sprintf('%0.' . $dec . 'f', $commande->getVar('cmd_total')));        // Tax amount of the order
$xoopsTpl->assign('shippingAmount', sprintf('%0.' . $dec . 'f', $commande->getVar('cmd_shipping')));    // VAT amount of shipping cost
$xoopsTpl->assign('discountsDescription', nl2br($commande->getVar('cmd_text')));                    // List of discounts given

bookshop_setCSS();
$title = _BOOKSHOP_BILL . ' - ' . bookshop_get_module_name();
bookshop_set_metas($title, $title);
include_once XOOPS_ROOT_PATH . '/footer.php';
