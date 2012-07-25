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
 * Flux RSS pour suivre les derniers livres
 */
include 'header.php';
include_once XOOPS_ROOT_PATH.'/class/template.php';

if(bookshop_getmoduleoption('use_rss') == 0) {
	exit;
}
// Paramètre, soit rien auquel cas on prend tous les livres récents soit cat_cid
$cat_cid = isset($_GET['cat_cid']) ? intval($_GET['cat_cid']) : 0;
if (function_exists('mb_http_output')) {
	mb_http_output('pass');
}
$charset = 'utf-8';
header ('Content-Type:text/xml; charset='.$charset);
$tpl = new XoopsTpl();
$categoryTitle = '';
if(!empty($cat_cid)) {
	$category = null;
	$category = $h_bookshop_cat->get($cat_cid);
	if(is_object($category)) {
		$categoryTitle = $category->getVar('cat_title');
	}
}
$sitename = htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES);
$email = checkEmail($xoopsConfig['adminmail'],true);
$slogan = htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES);
$limit = bookshop_getmoduleoption('perpage');

$tpl->assign('charset',$charset);
$tpl->assign('channel_title', xoops_utf8_encode($sitename));
$tpl->assign('channel_link', XOOPS_URL.'/');
$tpl->assign('channel_desc', xoops_utf8_encode($slogan));
$tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
$tpl->assign('channel_webmaster', xoops_utf8_encode($email));
$tpl->assign('channel_editor', xoops_utf8_encode($email));
$tpl->assign('channel_category', xoops_utf8_encode($categoryTitle));
$tpl->assign('channel_generator', xoops_utf8_encode(bookshop_get_module_name()));
$tpl->assign('channel_language', _LANGCODE);
$tpl->assign('image_url', XOOPS_URL.'/images/logo.gif');
$dimention = getimagesize(XOOPS_ROOT_PATH.'/images/logo.gif');
if (empty($dimention[0])) {
	$width = 88;
} else {
	$width = ($dimention[0] > 144) ? 144 : $dimention[0];
}
if (empty($dimention[1])) {
	$height = 31;
} else {
	$height = ($dimention[1] > 400) ? 400 : $dimention[1];
}
$tpl->assign('image_width', $width);
$tpl->assign('image_height', $height);

$tblBooks = $h_bookshop_books->getRecentBooks(0, $limit, $cat_cid);
foreach($tblBooks as $item) {
	$titre = htmlspecialchars($item->getVar('book_title'), ENT_QUOTES);
	$description = htmlspecialchars(strip_tags($item->getVar('book_summary')), ENT_QUOTES);
	$link = $h_bookshop_books->GetBookLink($item->getVar('book_id'), $item->getVar('book_title'));
          $tpl->append('items', array('title' => xoops_utf8_encode($titre),
          	'link' => $link,
          	'guid' => $link,
          	'pubdate' => formatTimestamp($item->getVar('book_submitted'), 'rss'),
          	'description' => xoops_utf8_encode($description)));
}
$tpl->display('db:bookshop_rss.html');
?>