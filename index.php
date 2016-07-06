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
 * Page d'index, liste des derniers livres parus
 */
include __DIR__ . '/header.php';
$GLOBALS['current_category']  = -1;
$xoopsOption['template_main'] = 'bookshop_index.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
include_once BOOKSHOP_PATH . 'class/registryfile.php';

// Initialisations
$tbl_books         = $tbl_categories = $tbl_lang = $tbl_users = $tbl_tmp_user = $tbl_tmp_categ = $tbl_tmp_lang = $tbl_tmp_vat = $tbl_vat = array();
$tbl_books_id      = $tbl_auteurs = $tbl_infos_auteurs = $tbl_tmp_auteurs = array();
$tbl_tmp_related   = $tbl_related = $tbl_info_related_books = array();
$tbl_related_books = array();
$start             = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$limit             = bookshop_getmoduleoption('newbooks');                // Maximum number of items to display in the admin
$baseurl           = BOOKSHOP_URL . basename(__FILE__);                // URL de ce script (sans son nom)

$registry = new bookshop_registryfile();

// Some options for template
$xoopsTpl->assign('nostock_msg', bookshop_getmoduleoption('nostock_msg'));
$xoopsTpl->assign('mod_pref', $mod_pref);    // Module Preferences
$xoopsTpl->assign('welcome_msg', nl2br($registry->getfile('bookshop_index.txt')));

// / Get the total number of books published in the database
$xoopsTpl->assign('total_books_count', sprintf(_BOOKSHOP_THEREARE, $h_bookshop_books->getTotalPublishedBooksCount()));

if ($limit > 0) {
    // Get the list of recent books
    $tbl_books = $h_bookshop_books->getRecentBooks($start, $limit);

    // Get ID only necessary
    foreach ($tbl_books as $item) {
        $tbl_tmp_user[]  = $item->getVar('book_submitter');
        $tbl_tmp_categ[] = $item->getVar('book_cid');
        $tbl_tmp_lang[]  = $item->getVar('book_lang_id');
        $tbl_tmp_vat[]   = $item->getVar('book_vat_id');
        $tbl_books_id[]  = $item->getVar('book_id');
    }
    // Dedupe tables
    $tbl_tmp_user  = array_unique($tbl_tmp_user);
    $tbl_tmp_categ = array_unique($tbl_tmp_categ);
    $tbl_tmp_lang  = array_unique($tbl_tmp_lang);
    $tbl_tmp_vat   = array_unique($tbl_tmp_vat);

    sort($tbl_tmp_user);
    sort($tbl_tmp_categ);
    sort($tbl_tmp_lang);
    sort($tbl_tmp_vat);
    sort($tbl_books_id);

   // Get the list of authors
// We start by searching the list of all authors and translators of all the books
    $tbl_books_auteurs = array();
    $tbl_auteurs       = $h_bookshop_booksauthors->getObjects(new Criteria('ba_book_id', '(' . implode(',', $tbl_books_id) . ')', 'IN'), true);
    if (count($tbl_auteurs) > 0) {
        foreach ($tbl_auteurs as $item) {
            $tbl_tmp_auteurs[] = $item->getVar('ba_auth_id');
            // Grouping data by book
            $tbl_books_auteurs[$item->getVar('ba_book_id')][] = $item;
        }
        $tbl_tmp_auteurs = array_unique($tbl_tmp_auteurs);
        sort($tbl_tmp_auteurs);
        // Then recovered the information from these authors / translators
        $tbl_infos_auteurs = $h_bookshop_authors->getObjects(new Criteria('auth_id', '(' . implode(',', $tbl_tmp_auteurs) . ')', 'IN'), true);
    }

    // Get a list of all related books
    $tbl_related = $h_bookshop_related->getObjects(new Criteria('related_book_id', '(' . implode(',', $tbl_books_id) . ')', 'IN'), true);
    foreach ($tbl_related as $item) {
        $tbl_tmp_related[]                                     = $item->getVar('related_book_related');
        $tbl_related_books[$item->getVar('related_book_id')][] = $item;
    }
    $tbl_tmp_related = array_unique($tbl_tmp_related);
    sort($tbl_tmp_related);

    // Then we grab the title and ID books
    if (count($tbl_tmp_related) > 0) {
        $tbl_info_related_books = $h_bookshop_books->getIdTitle(new Criteria('book_id', '(' . implode(',', $tbl_tmp_related) . ')', 'IN'));
    }

    // Get the list of categories
    if (count($tbl_tmp_categ) > 0) {
        $tbl_categories = $h_bookshop_cat->getObjects(new Criteria('cat_cid', '(' . implode(',', $tbl_tmp_categ) . ')', 'IN'), true);
    }

    // Get the list of languages
    if (count($tbl_tmp_lang) > 0) {
        $tbl_lang = $h_bookshop_lang->getObjects(new Criteria('lang_id', '(' . implode(',', $tbl_tmp_lang) . ')', 'IN'), true);
    }

    // Get the list of VAT
    if (count($tbl_tmp_vat) > 0) {
        $tbl_vat = $h_bookshop_vat->getObjects(new Criteria('vat_id', '(' . implode(',', $tbl_tmp_vat) . ')', 'IN'), true);
    }

    // Get the list of people who have published these recent books
    if (count($tbl_tmp_user) > 0) {
        $user_handler = $member_handler = xoops_getHandler('user');
        $criteria     = new Criteria('uid', '(' . implode(',', $tbl_tmp_user) . ')', 'IN');
        $tbl_users    = $user_handler->getObjects($criteria, true);
    }

    // Process books
    $lastTitle = '';
    foreach ($tbl_books as $item) {
        $tbl_tmp = array();
        $tbl_tmp = $item->toArray();
        if (xoops_trim($lastTitle) == '') {
            $lastTitle = $item->getVar('book_title');
        }
        $tbl_tmp['book_category'] = $tbl_categories[$item->getVar('book_cid')];
        $tbl_tmp['book_language'] = $tbl_lang[$item->getVar('book_lang_id')];
        $thisuser                 = $tbl_users[$item->getVar('book_submitter')];
        if (xoops_trim($thisuser->getVar('name')) != '') {
            $name = $thisuser->getVar('name');
        } else {
            $name = $thisuser->getVar('uname');
        }
        $tbl_tmp['book_submiter_name']      = $name;
        $linkeduser                         = '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $thisuser->getVar('uid') . '">' . $name . '</a>';
        $tbl_tmp['book_submiter_link']      = $name;
        $tbl_tmp['book_vat_rate']           = $tbl_vat[$item->getVar('book_vat_id')];
        $tbl_tmp['book_price_ttc']          = bookshop_getTTC($item->getVar('book_price'), $tbl_vat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
        $tbl_tmp['book_discount_price_ttc'] = bookshop_getTTC($item->getVar('book_discount_price'), $tbl_vat[$item->getVar('book_vat_id')]->getVar('vat_rate'));

        // Search for  authors / translators
        $tbl_join1 = $tbl_join2 = array();
        if (isset($tbl_books_auteurs[$item->getVar('book_id')])) {
            $tbl_tmp2 = $tbl_books_auteurs[$item->getVar('book_id')];    // Returns the list of all authors / translators of a book
        } else {
            $tbl_tmp2 = array();
        }
        $tbl_livre_auteurs = $tbl_livre_traducteurs = array();
        foreach ($tbl_tmp2 as $oneauthor) {
            $auteur = $tbl_infos_auteurs[$oneauthor->getVar('ba_auth_id')];
            if ($oneauthor->getVar('ba_type') == 1) {
                $tbl_livre_auteurs[] = $auteur->toArray();
                $tbl_join1[]         = $auteur->getVar('auth_firstname') . ' ' . $auteur->getVar('auth_name');
            } else {
                $tbl_livre_traducteurs[] = $auteur->toArray();
                $tbl_join2[]             = $auteur->getVar('auth_firstname') . ' ' . $auteur->getVar('auth_name');
            }
        }
        if (count($tbl_join1) > 0) {
            $tbl_tmp['book_joined_authors'] = implode(', ', $tbl_join1);
        }
        if (count($tbl_join2) > 0) {
            $tbl_tmp['book_joined_translators'] = implode(', ', $tbl_join2);
        }
        $tbl_tmp['book_authors']     = $tbl_livre_auteurs;
        $tbl_tmp['book_translators'] = $tbl_livre_traducteurs;

        // Recherche des livres relatifs, s'il y en a !
        $tbl_related = $tbl_tmp2 = array();
        if (isset($tbl_related_books[$item->getVar('book_id')])) {
            $tbl_tmp2 = $tbl_related_books[$item->getVar('book_id')];    // Contains the list of books relating to EC book
            foreach ($tbl_tmp2 as $onerelated) {
                $book_id = $onerelated->getVar('related_book_id');
                if (isset($tbl_info_related_books[$book_id])) {
                    $tbl_related[] = array('related_book_id' => $book_id, 'related_book_title' => $tbl_info_related_books[$book_id]);
                }
            }
        }
        $tbl_tmp['book_related_books'] = $tbl_related;
        // And we place everything into the template
        $xoopsTpl->append('books', $tbl_tmp);
    }
}

// Setup the categories of level 1
$tbl_categories = array();
$criteria       = new Criteria('cat_pid', 0, '=');
$criteria->setSort('cat_title');
$tbl_categories = $h_bookshop_cat->getObjects($criteria, true);
$count          = 1;
foreach ($tbl_categories as $item) {
    $tbl_tmp                     = $item->toArray();
    $tbl_tmp['count']            = $count;
    $tbl_tmp['cat_url_rewrited'] = $h_bookshop_cat->GetCategoryLink($item->getVar('cat_cid'), $item->getVar('cat_title'));
    $tbl_tmp['cat_href_title']   = bookshop_makeHrefTitle($item->getVar('cat_title'));
    $xoopsTpl->append('categories', $tbl_tmp);
    ++$count;
}

bookshop_setCSS();
if (xoops_trim($lastTitle) != '') {
    $lastTitle = strip_tags($lastTitle) . ' - ';
}
bookshop_set_metas($lastTitle . bookshop_get_module_name(), bookshop_get_module_name());
include_once(XOOPS_ROOT_PATH . '/footer.php');
