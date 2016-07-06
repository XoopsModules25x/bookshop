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
 * Affichage d'un livre
 */
include __DIR__ . '/header.php';

$book_id = 0;
// Les tests **************************************************************************************
// Peut on voter ?
if (bookshop_getmoduleoption('ratebooks') == 0) {
    bookshop_redirect(_BOOKSHOP_NORATE, 'index.php', 5);
}
// Find the book #
if (isset($_GET['book_id'])) {
    $book_id = (int)$_GET['book_id'];
} elseif (isset($_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];
} else {
    bookshop_redirect(_BOOKSHOP_ERROR1, 'index.php', 5);
}
// The book exists?
$book = null;
$book = $h_bookshop_books->get($book_id);
if (!is_object($book)) {
    bookshop_redirect(_BOOKSHOP_ERROR1, 'index.php', 5);
}

//  The book is it online?
if ($book->getVar('book_online') == 0) {
    bookshop_redirect(_BOOKSHOP_ERROR2, 'index.php', 5);
}

// The book is published?
if (bookshop_getmoduleoption('show_unpublished') == 0 && $book->getVar('book_submitted') > time()) {
    bookshop_redirect(_BOOKSHOP_ERROR3, 'index.php', 5);
}

// Should we view the books even when not in stock?
if (bookshop_getmoduleoption('nostock_display') == 0 && $book->getVar('book_stock') == 0) {
    if (xoops_trim(bookshop_getmoduleoption('nostock_display')) != '') {
        bookshop_redirect(bookshop_getmoduleoption('nostock_display'), 'index.php', 5);
    }
}

//  End of the tests, if it is still there is that everything is good **************************************
if (!empty($_POST['btnsubmit'])) {            // The form was submited
    $GLOBALS['current_category'] = -1;
    if (!is_object($xoopsUser)) {
        $ratinguser = 0;
    } else {
        $ratinguser = $xoopsUser->getVar('uid');
    }
    $ip      = bookshop_IP();
    $canRate = true;
    if ($ratinguser != 0) {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('vote_book_id', $book->getVar('book_id'), '='));
        $criteria->add(new Criteria('vote_uid', $ratinguser, '='));
        $cnt = 0;
        $cnt = $h_bookshop_votedata->getCount($criteria);
        if ($cnt > 0) {
            $canRate = false;
        }
    } else {
        $anonwaitdays = 1;
        $yesterday    = (time() - (86400 * $anonwaitdays));
        $criteria     = new CriteriaCompo();
        $criteria->add(new Criteria('vote_book_id', $book->getVar('book_id'), '='));
        $criteria->add(new Criteria('vote_uid', 0, '='));
        $criteria->add(new Criteria('vote_ratinghostname', $ip, '='));
        $criteria->add(new Criteria('vote_ratingtimestamp', $yesterday, '>'));
        $cnt = $h_bookshop_votedata->getCount($criteria);
        if ($cnt > 0) {
            $canRate = false;
        }
    }
    if ($canRate) {
        if ($_POST['rating'] == '--') {
            bookshop_redirect(_BOOKSHOP_NORATING, BOOKSHOP_URL . 'book.php?book_id=' . $book->getVar('book_id'), 4);
        }
        $book_vote_data = $h_bookshop_votedata->create(true);
        $book_vote_data->setVar('vote_book_id', $book->getVar('book_id'));
        $book_vote_data->setVar('vote_uid', $ratinguser);
        $book_vote_data->setVar('vote_rating', (int)$_POST['rating']);
        $book_vote_data->setVar('vote_ratinghostname', $ip);
        $book_vote_data->setVar('vote_ratingtimestamp', time());
        $h_bookshop_votedata->insert($book_vote_data);
        // Calcul du nombre de votes et du total des votes pour mettre � jour les informations du livre
        $totalVotes = 0;
        $sumRating  = 0;
        $ret        = 0;
        $ret        = $h_bookshop_votedata->getCountRecordSumRating($book->getVar('book_id'), $totalVotes, $sumRating);

        $finalrating = $sumRating / $totalVotes;
        $finalrating = number_format($finalrating, 4);
        $h_bookshop_books->updateRating($book_id, $finalrating, $totalVotes);
        $ratemessage = _BOOKSHOP_VOTEAPPRE . '<br>' . sprintf(_BOOKSHOP_THANKYOU, $xoopsConfig['sitename']);
        bookshop_redirect($ratemessage, BOOKSHOP_URL . 'book.php?book_id=' . $book->getVar('book_id'), 2);
    } else {
        bookshop_redirect(_BOOKSHOP_VOTEONCE, BOOKSHOP_URL . 'book.php?book_id=' . $book->getVar('book_id'), 5);
    }
} else {    // Affichage du formulaire
    $GLOBALS['current_category']  = $book->getVar('book_cid');
    $xoopsOption['template_main'] = 'bookshop_rate_book.tpl';
    include_once XOOPS_ROOT_PATH . '/header.php';
    $xoopsTpl->assign('mod_pref', $mod_pref);    // Module Preferences
    $xoopsTpl->assign('book', $book->toArray());
    $title = _BOOKSHOP_RATETHISBOOK . ' : ' . strip_tags($book->getVar('book_title')) . ' - ' . bookshop_get_module_name();
    bookshop_set_metas($title, $title);
    bookshop_setCSS();
}

include_once XOOPS_ROOT_PATH . '/footer.php';
