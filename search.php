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
 * Recherche dans les livres
 */
include __DIR__ . '/header.php';
include_once BOOKSHOP_PATH . 'class/tree.php';
$GLOBALS['current_category']  = -1;        // Pour le bloc des cat�gories
$xoopsOption['template_main'] = 'bookshop_search.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$limit         = 0;
$tblCategories = $tblAuthors = $tblTranslators = $tblLang = array();
$limit         = bookshop_getmoduleoption('newbooks');                // Nombre maximum d'�l�ments � afficher dans l'admin
$baseurl       = BOOKSHOP_URL . basename(__FILE__);                // URL de ce script (sans son nom)

$xoopsTpl->assign('mod_pref', $mod_pref);                // Module Preferences

$tblCategories = $h_bookshop_cat->GetAllCategories();
$tblLang       = $h_bookshop_lang->GetAllLang();
// Auteurs
$criteria = new Criteria('auth_type', 1, '=');
$criteria->setSort('auth_name');
$tblAuthors = $h_bookshop_authors->getObjects($criteria);
// Traducteurs
$criteria = new Criteria('auth_type', 2, '=');
$criteria->setSort('auth_name');
$tblTranslators = $h_bookshop_authors->getObjects($criteria);

if ((isset($_POST['op']) && $_POST['op'] == 'go') || isset($_GET['start'])) {    // Recherche des r�sultats
    $xoopsTpl->assign('search_results', true);
    bookshop_set_metas(bookshop_get_module_name() . ' - ' . _BOOKSHOP_SEARCHRESULTS, bookshop_get_module_name() . ' - ' . _BOOKSHOP_SEARCHRESULTS);

    $sql = 'SELECT b.book_id, b.book_title, b.book_submitted, b.book_submitter FROM ' . $xoopsDB->prefix('bookshop_books') . ' b, ' . $xoopsDB->prefix('bookshop_booksauthors') . ' a WHERE (b.book_id = a.ba_book_id AND  b.book_online = 1 ';
    if (bookshop_getmoduleoption('show_unpublished') == 0) {    // Ne pas afficher les livres qui ne sont pas publi�s
        $sql .= ' AND b.book_submitted <= ' . time();
    }
    if (bookshop_getmoduleoption('nostock_display') == 0) {    // Se limiter aux seuls livres encore en stock
        $sql .= ' AND b.book_stock > 0';
    }
    $sql .= ') ';
    // Recherche sur une cat�gorie
    if (isset($_POST['book_category'])) {
        $cat_cid = (int)$_POST['book_category'];
        if ($cat_cid > 0) {
            $sql .= 'AND b.book_cid = ' . $cat_cid;
        }
    }

    // Recherche sur du texte
    if (xoops_trim($_POST['book_text']) != '') {
        $temp_queries = $queries = array();
        $temp_queries = preg_split('/[\s,]+/', $_POST['book_text']);
        foreach ($temp_queries as $q) {
            $q         = trim($q);
            $queries[] = $myts->addSlashes($q);
        }
        if (count($queries) > 0) {
            $tmpObject = new bookshop_books();
            $datas     =& $tmpObject->getVars();
            $tblFields = array();
            $cnt       = 0;
            foreach ($datas as $key => $value) {
                if ($value['data_type'] == XOBJ_DTYPE_TXTBOX || $value['data_type'] == XOBJ_DTYPE_TXTAREA) {
                    if ($cnt == 0) {
                        $tblFields[] = 'b.' . $key;
                    } else {
                        $tblFields[] = ' OR b.' . $key;
                    }
                    ++$cnt;
                }
            }
            $count = count($queries);
            $cnt   = 0;
            $sql .= ' AND ';
            $searchType = (int)$_POST['search_type'];
            $andor      = ' OR ';
            foreach ($queries as $oneQuery) {
                $sql .= '(';
                switch ($searchType) {
                    case 0:        // Commence par
                        $cond = " LIKE '" . $oneQuery . "%' ";
                        break;
                    case 1:        // Finit par
                        $cond = " LIKE '%" . $oneQuery . "' ";
                        break;
                    case 2:        // Correspond �
                        $cond = " = '" . $oneQuery . "' ";
                        break;
                    case 3:        // Contient
                        $cond = " LIKE '%" . $oneQuery . "%' ";
                        break;
                }
                $sql .= implode($cond, $tblFields) . $cond . ')';
                ++$cnt;
                if ($cnt != $count) {
                    $sql .= ' ' . $andor . ' ';
                }
            }
            //$sql .= ')';
        }
    } else {
        $sql .= ' AND ';
    }

    $reqSupp = 0;
    $sql2    = '';
    // Recherche sur les auteurs
    if (isset($_POST['book_authors'])) {
        $searchAuthors = true;
        $auteurs       = null;
        $auteurs       = $_POST['book_authors'];
        if (is_array($auteurs) && (int)$auteurs[0] == 0) {
            $auteurs = array_shift($auteurs);
        }
        if (is_array($auteurs) && count($auteurs) > 0) {
            array_walk($auteurs, 'intval');
            $sql2 .= ' (a.ba_type = 1 AND a.ba_auth_id IN ( ' . implode(',', $auteurs) . '))';
            ++$reqSupp;
        } else {
            $auteur = (int)$auteurs;
            if ($auteur > 0) {
                $sql2 .= ' AND (a.ba_type = 1 AND  a.ba_auth_id = ' . $auteurs . ')';
                ++$reqSupp;
            }
        }
    } else {
        $searchAuthors = false;
    }

    // Research translators
    if (isset($_POST['book_translators'])) {
        if ($searchAuthors) {
            $andor = ' OR ';
        } else {
            $andor = ' AND ';
        }
        $auteurs = null;
        $auteurs = $_POST['book_translators'];
        if (is_array($auteurs) && (int)$auteurs[0] == 0) {
            $auteurs = array_shift($auteurs);
        }
        if (is_array($auteurs) && count($auteurs) > 0) {
            array_walk($auteurs, 'intval');
            $sql2 .= $andor . ' (a.ba_type = 2 AND a.ba_auth_id IN ( ' . implode(',', $auteurs) . '))';
            ++$reqSupp;
        } else {
            $auteur = (int)$auteurs;
            if ($auteur > 0) {
                $sql2 .= $andor . ' (a.ba_type = 2 AND  a.ba_auth_id = ' . $auteurs . ')';
                ++$reqSupp;
            }
        }
    }
    switch ($reqSupp) {
        case 1:
            $sql .= $sql2;
            break;

        case 2:
            $sql .= ' AND (' . $sql2 . ')';
            break;
    }

    // Research on Language
    if (isset($_POST['book_language'])) {
        $language = (int)$_POST['book_language'];
        if ($language > 0) {
            $sql .= ' AND book_lang_id = ' . $language;
        }
    }
    $sql .= ' GROUP BY b.book_id ORDER BY book_submitted DESC';
    $result = $xoopsDB->query($sql);
    $ret    = array();
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $ret               = array();
        $ret['link']       = 'book.php?book_id=' . $myrow['book_id'];
        $ret['title']      = $myts->htmlSpecialChars($myrow['book_title']);
        $ret['href_title'] = bookshop_makeHrefTitle($myts->htmlSpecialChars($myrow['book_title']));
        $ret['time']       = $myrow['book_submitted'];
        $ret['uid']        = $myrow['book_submitter'];
        $xoopsTpl->append('books', $ret);
    }
} else {
    $xoopsTpl->assign('search_results', false);
    bookshop_set_metas(bookshop_get_module_name() . ' - ' . _BOOKSHOP_SEARCHFOR, bookshop_get_module_name() . ' - ' . _BOOKSHOP_SEARCHFOR);
}

include_once BOOKSHOP_PATH . 'include/book_search_form.php';
$sform = bookshop_formMarkRequiredFields($sform);
$xoopsTpl->assign('search_form', $sform->render());

bookshop_setCSS();

include_once XOOPS_ROOT_PATH . '/footer.php';
