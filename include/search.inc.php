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
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 *
 * @return array
 */
function bookshop_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;
    include XOOPS_ROOT_PATH . '/modules/bookshop/include/common.php';
    include_once XOOPS_ROOT_PATH . '/modules/bookshop/class/bookshop_books.php';

    // Recherche dans les livres
    $sql = 'SELECT book_id, book_title, book_submitted, book_submitter FROM ' . $xoopsDB->prefix('bookshop_books') . ' WHERE (book_online = 1';
    if (bookshop_getmoduleoption('show_unpublished') == 0) {    // Ne pas afficher les livres qui ne sont pas publi�s
        $sql .= ' AND book_submitted <= ' . time();
    }
    if (bookshop_getmoduleoption('nostock_display') == 0) {    // Se limiter aux seuls livres encore en stock
        $sql .= ' AND book_stock > 0';
    }
    if ($userid != 0) {
        $sql .= '  AND book_submitter = ' . $userid;
    }
    $sql .= ') ';

    $tmpObject = new bookshop_books();
    $datas     =& $tmpObject->getVars();
    $tblFields = array();
    $cnt       = 0;
    foreach ($datas as $key => $value) {
        if ($value['data_type'] == XOBJ_DTYPE_TXTBOX || $value['data_type'] == XOBJ_DTYPE_TXTAREA) {
            if ($cnt == 0) {
                $tblFields[] = $key;
            } else {
                $tblFields[] = ' OR ' . $key;
            }
            ++$cnt;
        }
    }

    $count = count($queryarray);
    if (is_array($queryarray) && $count > 0) {
        $cnt = 0;
        $sql .= ' AND (';
        foreach ($queryarray as $oneQuery) {
            $sql .= '(';
            $cond = " LIKE '%" . $oneQuery . "%' ";
            $sql .= implode($cond, $tblFields) . $cond . ')';
            ++$cnt;
            if ($cnt != $count) {
                $sql .= ' ' . $andor . ' ';
            }
        }
        $sql .= ') ';
    }
    $sql .= ' ORDER BY book_submitted DESC';
    $i      = 0;
    $ret    = array();
    $myts   = MyTextSanitizer::getInstance();
    $result = $xoopsDB->query($sql, $limit, $offset);
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $ret[$i]['image'] = 'assets/images/book.png';
        $ret[$i]['link']  = 'book.php?book_id=' . $myrow['book_id'];
        $ret[$i]['title'] = $myts->htmlSpecialChars($myrow['book_title']);
        $ret[$i]['time']  = $myrow['book_submitted'];
        $ret[$i]['uid']   = $myrow['book_submitter'];
        ++$i;
    }

    return $ret;
}
