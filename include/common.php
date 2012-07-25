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
if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

if( !defined("BOOKSHOP_DIRNAME") ) {
	define("BOOKSHOP_DIRNAME", 'bookshop');
	define("BOOKSHOP_URL", XOOPS_URL.'/modules/'.BOOKSHOP_DIRNAME.'/');
	define("BOOKSHOP_PATH", XOOPS_ROOT_PATH.'/modules/'.BOOKSHOP_DIRNAME.'/');
	define("BOOKSHOP_IMAGES_URL", BOOKSHOP_URL.'images/');
}
// changed include_once en include
include_once BOOKSHOP_PATH.'include/functions.php';

$myts = &MyTextSanitizer::getInstance();
$module_name =  bookshop_get_module_name();	// Nom donné au module dans le gestionnaire de modules

// Chargement des handler
$h_bookshop_authors = xoops_getmodulehandler('bookshop_authors', BOOKSHOP_DIRNAME);
$h_bookshop_books = xoops_getmodulehandler('bookshop_books', BOOKSHOP_DIRNAME);
$h_bookshop_booksauthors = xoops_getmodulehandler('bookshop_booksauthors', BOOKSHOP_DIRNAME);
$h_bookshop_caddy = xoops_getmodulehandler('bookshop_caddy', BOOKSHOP_DIRNAME);
$h_bookshop_cat = xoops_getmodulehandler('bookshop_cat', BOOKSHOP_DIRNAME);
$h_bookshop_commands = xoops_getmodulehandler('bookshop_commands', BOOKSHOP_DIRNAME);
$h_bookshop_related = xoops_getmodulehandler('bookshop_related', BOOKSHOP_DIRNAME);
$h_bookshop_vat = xoops_getmodulehandler('bookshop_vat', BOOKSHOP_DIRNAME);
$h_bookshop_votedata = xoops_getmodulehandler('bookshop_votedata', BOOKSHOP_DIRNAME);
$h_bookshop_discounts =  xoops_getmodulehandler('bookshop_discounts', BOOKSHOP_DIRNAME);
$h_bookshop_lang =  xoops_getmodulehandler('bookshop_lang', BOOKSHOP_DIRNAME);

// Définition des images
if( !defined("_BOOKSHOP_EDIT")) {
	global $xoopsConfig;
	if (file_exists(BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/main.php')) {
		include BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/main.php';
	} else {
		include BOOKSHOP_PATH.'language/english/main.php';
	}
}
$icones = array(
	'edit' => "<img src='". BOOKSHOP_IMAGES_URL ."edit.png' alt='" . _BOOKSHOP_EDIT . "' align='middle' />",
	'delete' => "<img src='". BOOKSHOP_IMAGES_URL ."delete.png' alt='" . _BOOKSHOP_DELETE . "' align='middle' />",
	'online' => "<img src='". BOOKSHOP_IMAGES_URL ."online.gif' alt='" . _BOOKSHOP_ONLINE . "' align='middle' />",
	'offline' => "<img src='". BOOKSHOP_IMAGES_URL ."offline.gif' alt='" . _BOOKSHOP_OFFLINE . "' align='middle' />",
	'ok' => "<img src='". BOOKSHOP_IMAGES_URL ."ok.png' alt='" . _BOOKSHOP_VALIDATE_COMMAND . "' align='middle' />",
	'copy' => "<img src='". BOOKSHOP_IMAGES_URL ."duplicate.png' alt='" . _BOOKSHOP_DUPLICATE_BOOK . "' align='middle' />",
	'details' => "<img src='". BOOKSHOP_IMAGES_URL ."details.png' alt='"._BOOKSHOP_DETAILS."' align='middle' />"
);

// Chargement de quelques préférences
$mod_pref = array(
	'money_short' => bookshop_getmoduleoption('money_short'),
	'money_full' => bookshop_getmoduleoption('money_full'),
	'url_rewriting' => bookshop_getmoduleoption('urlrewriting'),
	'tooltip' => bookshop_getmoduleoption('infotips'),
	'advertisement' => bookshop_getmoduleoption('advertisement'),
	'rss' => bookshop_getmoduleoption('use_rss'),
	'nostock_msg' => bookshop_getmoduleoption('nostock_msg'),
	'isAdmin' => bookshop_isAdmin()
);
?>