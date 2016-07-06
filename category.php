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
 * Page Categories
 * Principe :
* When you are on a parent class (no parent) or if you did not specify a category,
 * Is displayed (if requested), 4 blocks, otherwise it displays the books category
 */
include __DIR__ . '/header.php';
$cat_cid                     = isset($_GET['cat_cid']) ? (int)$_GET['cat_cid'] : 0;
$GLOBALS['current_category'] = $cat_cid;
$start                       = isset($_GET['start']) ? (int)$_GET['start'] : 0;

$category = null;
if ($cat_cid > 0) {
    $category = $h_bookshop_cat->get($cat_cid);
    if (!is_object($category)) {
        bookshop_redirect(_BOOKSHOP_ERROR8, 'index.php', 5);
    }
}
// We can view the blocks *********************************************************************
$xoopsOption['template_main'] = 'bookshop_category.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
$tblVat = $tbl_categories = array();
$limit  = bookshop_getmoduleoption('perpage');

// Read the VAT ********************************************************************************
$tblVat = $h_bookshop_vat->GetAllVats();

//  Read categories  *************************************************************************
$tbl_categories = $h_bookshop_cat->GetAllCategories();

// Options for the template ***********************************************************************
$xoopsTpl->assign('mod_pref', $mod_pref);    // Module Preferences

include_once XOOPS_ROOT_PATH . '/class/tree.php';
$tbl_tmp = array();
$mytree  = new XoopsObjectTree($tbl_categories, 'cat_cid', 'cat_pid');

// If there is a parent category or if you did not specify a category
if ((is_object($category) && $category->getVar('cat_pid') == 0) || $cat_cid == 0) {    // On affiche les 4 blocs
    $xoopsTpl->assign('case', 1);
    // Meta ***************************************************************************************
    if (is_object($category)) {    // On est sur une cat�gorie particuli�re
        $xoopsTpl->assign('category', $category->toArray());
        $title = _BOOKSHOP_CATEGORYC . ' ' . $category->getVar('cat_title') . ' - ' . bookshop_get_module_name();
        if (!bookshop_getmoduleoption('manual_meta')) {
            bookshop_set_metas($title, $title);
        } else {
            $pageTitle       = xoops_trim($category->getVar('cat_metatitle')) != '' ? xoops_trim($category->getVar('cat_metatitle')) : $title;
            $metaDescription = xoops_trim($category->getVar('cat_metadescription')) != '' ? xoops_trim($category->getVar('cat_metadescription')) : $title;
            $metaKeywords    = xoops_trim($category->getVar('cat_metakeywords'));
            bookshop_set_metas($pageTitle, $metaDescription, $metaKeywords);
        }
    } else {    // homepage categories
        $title = _BOOKSHOP_CATEGORIES . ' - ' . bookshop_get_module_name();
        bookshop_set_metas($title, $title);
    }

    // Setting categories
    $chunk1 = bookshop_getmoduleoption('chunk1');        // Most recent books
    $chunk2 = bookshop_getmoduleoption('chunk2');        // Most purchased books
    $chunk3 = bookshop_getmoduleoption('chunk3');        // Most viewed books
    $chunk4 = bookshop_getmoduleoption('chunk4');        //  Top rated books

    $tblChildsO  = $tblChilds = array();
    $tblChilds[] = $cat_cid;
    if ($cat_cid > 0) {
        $tblChildsO = $mytree->getAllChild($cat_cid);
        foreach ($tblChildsO as $item) {
            $tblChilds[] = $item->getVar('cat_cid');
        }
    }

    if ($chunk1 > 0) {        // most recent books (in this category or all categories)
        $tblBooks = array();
        $tblBooks = $h_bookshop_books->getRecentBooks($start, $limit, $tblChilds);    // Renvoie des objets
        if (count($tblBooks) > 0) {
            $xoopsTpl->assign('chunk' . $chunk1 . 'Title', _BOOKSHOP_MOST_RECENT);
            // Recherche des auteurs
            $tblAuthors = $tbl_tmp = $tblAuthorsPerBook = array();
            $tblAuthors = $h_bookshop_booksauthors->getObjects(new Criteria('ba_book_id', '(' . implode(',', array_keys($tblBooks)) . ')', 'IN'), true);
            foreach ($tblAuthors as $item) {
                $tbl_tmp[]                                        = $item->getVar('ba_auth_id');
                $tblAuthorsPerBook[$item->getVar('ba_book_id')][] = $item;
            }
            $tbl_tmp    = array_unique($tbl_tmp);
            $tblAuthors = $h_bookshop_authors->getObjects(new Criteria('auth_id', '(' . implode(',', $tbl_tmp) . ')', 'IN'), true);
            foreach ($tblBooks as $item) {
                $tbl_tmp                  = array();
                $tbl_tmp                  = $item->toArray();
                $tbl_tmp['book_category'] = isset($tbl_categories[$item->getVar('book_cid')]) ? $tbl_categories[$item->getVar('book_cid')]->toArray() : null;
                if (isset($tblVat[$item->getVar('book_vat_id')])) {
                    $tbl_tmp['book_price_ttc']          = bookshop_getTTC($item->getVar('book_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
                    $tbl_tmp['book_discount_price_ttc'] = bookshop_getTTC($item->getVar('book_discount_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
                } else {
                    $tbl_tmp['book_price_ttc']          = 0;
                    $tbl_tmp['book_discount_price_ttc'] = 0;
                }
                $tbl_join = array();
                if (isset($tblAuthorsPerBook[$item->getVar('book_id')])) {
                    foreach ($tblAuthorsPerBook[$item->getVar('book_id')] as $author) {    // Renvoie des objets de type booksauthors
                        if ($author->getVar('ba_type') == 1) {
                            $auteur     = $tblAuthors[$author->getVar('ba_auth_id')];
                            $tbl_join[] = $auteur->getVar('auth_firstname') . ' ' . $auteur->getVar('auth_name');
                        }
                    }
                    if (count($tbl_join) > 0) {
                        $tbl_tmp['book_joined_authors'] = implode(', ', $tbl_join);
                    }
                } else {
                    $tbl_tmp['book_joined_authors'] = '';
                }
                $xoopsTpl->append('chunk' . $chunk1, $tbl_tmp);
            }
        }
    }

    if ($chunk2 > 0) {        // most achets Books (in this category or all categories)
        $tblBooks = array();
        if ($cat_cid > 0) {    // For a particuliar category
            $tblBooks = $h_bookshop_caddy->getMostSoldBooksInCategory($tblChilds, $start, $limit);    // Returns ID
        } else {    //  Across all categories
            $tblBooks = $h_bookshop_caddy->getMostSoldBooks($start, $limit);    // Renvoie des ID
        }
        if (count($tblBooks) > 0) {
            $xoopsTpl->assign('chunk' . $chunk2 . 'Title', _BOOKSHOP_MOST_SOLD);
            $listeIds = implode(',', array_values($tblBooks));
            $tblBooks = array();
            $tblBooks = $h_bookshop_books->getObjects(new Criteria('book_id', '(' . $listeIds . ')', 'IN'), true);
            // Recherche des auteurs
            $tblAuthors = $tbl_tmp = $tblAuthorsPerBook = array();
            $tblAuthors = $h_bookshop_booksauthors->getObjects(new Criteria('ba_book_id', '(' . implode(',', array_keys($tblBooks)) . ')', 'IN'), true);
            foreach ($tblAuthors as $item) {
                $tbl_tmp[]                                        = $item->getVar('ba_auth_id');
                $tblAuthorsPerBook[$item->getVar('ba_book_id')][] = $item;
            }
            $tbl_tmp    = array_unique($tbl_tmp);
            $tblAuthors = $h_bookshop_authors->getObjects(new Criteria('auth_id', '(' . implode(',', $tbl_tmp) . ')', 'IN'), true);
            foreach ($tblBooks as $item) {
                $tbl_tmp                  = array();
                $tbl_tmp                  = $item->toArray();
                $tbl_tmp['book_category'] = isset($tbl_categories[$item->getVar('book_cid')]) ? $tbl_categories[$item->getVar('book_cid')]->toArray() : null;
                if (isset($tblVat[$item->getVar('book_vat_id')])) {
                    $tbl_tmp['book_price_ttc']          = bookshop_getTTC($item->getVar('book_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
                    $tbl_tmp['book_discount_price_ttc'] = bookshop_getTTC($item->getVar('book_discount_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
                } else {
                    $tbl_tmp['book_price_ttc']          = 0;
                    $tbl_tmp['book_discount_price_ttc'] = 0;
                }
                $tbl_join = array();
                if (isset($tblAuthorsPerBook[$item->getVar('book_id')])) {
                    foreach ($tblAuthorsPerBook[$item->getVar('book_id')] as $author) {    // Renvoie des objets de type booksauthors
                        if ($author->getVar('ba_type') == 1) {
                            $auteur     = $tblAuthors[$author->getVar('ba_auth_id')];
                            $tbl_join[] = $auteur->getVar('auth_firstname') . ' ' . $auteur->getVar('auth_name');
                        }
                    }
                    if (count($tbl_join) > 0) {
                        $tbl_tmp['book_joined_authors'] = implode(', ', $tbl_join);
                    }
                } else {
                    $tbl_tmp['book_joined_authors'] = '';
                }
                $xoopsTpl->append('chunk' . $chunk2, $tbl_tmp);
            }
        }
    }

    if ($chunk3 > 0) {        // Livres les plus vus
        $tblBooks = array();
        $tblBooks = $h_bookshop_books->getMostViewedBooks($start, $limit, $tblChilds);    // Renvoie des objets
        if (count($tblBooks) > 0) {
            $xoopsTpl->assign('chunk' . $chunk3 . 'Title', _BOOKSHOP_MOST_VIEWED);
            // Recherche des auteurs
            $tblAuthors = $tbl_tmp = $tblAuthorsPerBook = array();
            $tblAuthors = $h_bookshop_booksauthors->getObjects(new Criteria('ba_book_id', '(' . implode(',', array_keys($tblBooks)) . ')', 'IN'), true);
            foreach ($tblAuthors as $item) {
                $tbl_tmp[]                                        = $item->getVar('ba_auth_id');
                $tblAuthorsPerBook[$item->getVar('ba_book_id')][] = $item;
            }
            $tbl_tmp    = array_unique($tbl_tmp);
            $tblAuthors = $h_bookshop_authors->getObjects(new Criteria('auth_id', '(' . implode(',', $tbl_tmp) . ')', 'IN'), true);
            foreach ($tblBooks as $item) {
                $tbl_tmp                  = array();
                $tbl_tmp                  = $item->toArray();
                $tbl_tmp['book_category'] = isset($tbl_categories[$item->getVar('book_cid')]) ? $tbl_categories[$item->getVar('book_cid')]->toArray() : null;
                if (isset($tblVat[$item->getVar('book_vat_id')])) {
                    $tbl_tmp['book_price_ttc']          = bookshop_getTTC($item->getVar('book_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
                    $tbl_tmp['book_discount_price_ttc'] = bookshop_getTTC($item->getVar('book_discount_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
                } else {
                    $tbl_tmp['book_price_ttc']          = 0;
                    $tbl_tmp['book_discount_price_ttc'] = 0;
                }
                $tbl_join = array();
                foreach ($tblAuthorsPerBook[$item->getVar('book_id')] as $author) {    // Renvoie des objets de type booksauthors
                    if ($author->getVar('ba_type') == 1) {
                        $auteur     = $tblAuthors[$author->getVar('ba_auth_id')];
                        $tbl_join[] = $auteur->getVar('auth_firstname') . ' ' . $auteur->getVar('auth_name');
                    }
                }
                if (count($tbl_join) > 0) {
                    $tbl_tmp['book_joined_authors'] = implode(', ', $tbl_join);
                }
                $xoopsTpl->append('chunk' . $chunk3, $tbl_tmp);
            }
        }
    }

    if ($chunk4 > 0) {        // Top rated Books
        $xoopsTpl->assign('chunk' . $chunk4 . 'Title', _BOOKSHOP_MOST_RATED);
        $tblBooks = array();
        $tblBooks = $h_bookshop_books->getBestRatedBooks($start, $limit, $tblChilds);    // Renvoie des objets
        if (count($tblBooks) > 0) {
            // Recherche des auteurs
            $tblAuthors = $tbl_tmp = $tblAuthorsPerBook = array();
            $tblAuthors = $h_bookshop_booksauthors->getObjects(new Criteria('ba_book_id', '(' . implode(',', array_keys($tblBooks)) . ')', 'IN'), true);
            foreach ($tblAuthors as $item) {
                $tbl_tmp[]                                        = $item->getVar('ba_auth_id');
                $tblAuthorsPerBook[$item->getVar('ba_book_id')][] = $item;
            }
            $tbl_tmp    = array_unique($tbl_tmp);
            $tblAuthors = $h_bookshop_authors->getObjects(new Criteria('auth_id', '(' . implode(',', $tbl_tmp) . ')', 'IN'), true);
            foreach ($tblBooks as $item) {
                $tbl_tmp                  = array();
                $tbl_tmp                  = $item->toArray();
                $tbl_tmp['book_category'] = isset($tbl_categories[$item->getVar('book_cid')]) ? $tbl_categories[$item->getVar('book_cid')]->toArray() : null;
                if (isset($tblVat[$item->getVar('book_vat_id')])) {
                    $tbl_tmp['book_price_ttc']          = bookshop_getTTC($item->getVar('book_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
                    $tbl_tmp['book_discount_price_ttc'] = bookshop_getTTC($item->getVar('book_discount_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
                } else {
                    $tbl_tmp['book_price_ttc']          = 0;
                    $tbl_tmp['book_discount_price_ttc'] = 0;
                }
                $tbl_join = array();
                foreach ($tblAuthorsPerBook[$item->getVar('book_id')] as $author) {    // Renvoie des objets de type booksauthors
                    if ($author->getVar('ba_type') == 1) {
                        $auteur     = $tblAuthors[$author->getVar('ba_auth_id')];
                        $tbl_join[] = $auteur->getVar('auth_firstname') . ' ' . $auteur->getVar('auth_name');
                    }
                }
                if (count($tbl_join) > 0) {
                    $tbl_tmp['book_joined_authors'] = implode(', ', $tbl_join);
                }
                $xoopsTpl->append('chunk' . $chunk4, $tbl_tmp);
            }
        }
    }
} else {    //  It is a category so we dfinie displays books in this category *****************************************************************
    $xoopsTpl->assign('case', 2);
    $xoopsTpl->assign('category', $category->toArray());
    // Pager ******************************************************************************************
    // Recherche du nombre de livres publi�s dans cette cat�gorie
    $booksCount = $h_bookshop_books->getTotalPublishedBooksCount($cat_cid);
    $limit      = bookshop_getmoduleoption('perpage');
    if ($booksCount > $limit) {
        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $catLink = $h_bookshop_cat->GetCategoryLink($cat_cid, $category->getVar('cat_title'));
        $pagenav = new XoopsPageNav($booksCount, $limit, $start, 'start', 'cat_cid=' . $cat_cid);
        $xoopsTpl->assign('pagenav', $pagenav->renderNav());
    } else {
        $xoopsTpl->assign('pagenav', '');
    }

    // Breadcrumb *********************************************************************************
    $tbl_ancestors = $mytree->getAllParent($cat_cid);
    $tbl_ancestors = array_reverse($tbl_ancestors);
    $tbl_tmp[]     = "<a href='" . BOOKSHOP_URL . "index.php' title='" . bookshop_makeHrefTitle(bookshop_get_module_name()) . "'>" . bookshop_get_module_name() . '</a>';
    foreach ($tbl_ancestors as $item) {
        $tbl_tmp[] = "<a href='" . $h_bookshop_cat->GetCategoryLink($item->getVar('cat_cid'), $item->getVar('cat_title')) . "' title='" . bookshop_makeHrefTitle($item->getVar('cat_title')) . "'>" . $item->getVar('cat_title') . '</a>';
    }
    // Adding to the current category
    $tbl_tmp[]  = "<a href='" . $h_bookshop_cat->GetCategoryLink($cat_cid, $category->getVar('cat_title')) . "' title='" . bookshop_makeHrefTitle($category->getVar('cat_title')) . "'>" . $category->getVar('cat_title') . '</a>';
    $breadcrumb = implode(' &raquo; ', $tbl_tmp);
    $xoopsTpl->assign('breadcrumb', $breadcrumb);

    // Meta ***************************************************************************************
    $title = strip_tags($breadcrumb);
    bookshop_set_metas($title, $title, str_replace('&raquo;', ',', $title));

    // Data books *************************************************************************
    $tblBooks = array();
    $tblBooks = $h_bookshop_books->getRecentBooks($start, $limit, $cat_cid);

    if (count($tblBooks) > 0) {
        $tblAuthors = $tbl_tmp = $tblAuthorsPerBook = array();
        $tblAuthors = $h_bookshop_booksauthors->getObjects(new Criteria('ba_book_id', '(' . implode(',', array_keys($tblBooks)) . ')', 'IN'), true);
        foreach ($tblAuthors as $item) {
            $tbl_tmp[]                                        = $item->getVar('ba_auth_id');
            $tblAuthorsPerBook[$item->getVar('ba_book_id')][] = $item;
        }
        $tbl_tmp    = array_unique($tbl_tmp);
        $tblAuthors = $h_bookshop_authors->getObjects(new Criteria('auth_id', '(' . implode(',', $tbl_tmp) . ')', 'IN'), true);
        foreach ($tblBooks as $item) {
            $tbl_tmp                  = array();
            $tbl_tmp                  = $item->toArray();
            $tbl_tmp['book_category'] = isset($tbl_categories[$item->getVar('book_cid')]) ? $tbl_categories[$item->getVar('book_cid')]->toArray() : null;
            if (isset($tblVat[$item->getVar('book_vat_id')])) {
                $tbl_tmp['book_price_ttc']          = bookshop_getTTC($item->getVar('book_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
                $tbl_tmp['book_discount_price_ttc'] = bookshop_getTTC($item->getVar('book_discount_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
            } else {
                $tbl_tmp['book_price_ttc']          = 0;
                $tbl_tmp['book_discount_price_ttc'] = 0;
            }
            $tbl_join = array();
            foreach ($tblAuthorsPerBook[$item->getVar('book_id')] as $author) {    // Renvoie des objets de type booksauthors
                if ($author->getVar('ba_type') == 1) {
                    $auteur     = $tblAuthors[$author->getVar('ba_auth_id')];
                    $tbl_join[] = $auteur->getVar('auth_firstname') . ' ' . $auteur->getVar('auth_name');
                }
            }
            if (count($tbl_join) > 0) {
                $tbl_tmp['book_joined_authors'] = implode(', ', $tbl_join);
            }
            $xoopsTpl->append('books', $tbl_tmp);
        }
    }
}
bookshop_setCSS();
include_once(XOOPS_ROOT_PATH . '/footer.php');
