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
 * Liste des auteurs
 */
include 'header.php';
$GLOBALS['current_category'] = -1;
$xoopsOption['template_main'] = 'bookshop_whoswho.html';
include_once XOOPS_ROOT_PATH.'/header.php';

$tblAll = $tblAnnuaire = array();
$xoopsTpl->assign('alphabet', $h_bookshop_authors->getAlphabet());
$xoopsTpl->assign('mod_pref', $mod_pref);	// Préférences du module
$tblType = array(1 => 'Author', 2 => 'Translator');

$criteria = new Criteria('auth_id', 0, '<>');
$criteria->setSort('auth_type, auth_name, auth_firstname');
$tblAll = $h_bookshop_authors->getObjects($criteria);
foreach($tblAll as $item) {
	$tblTmp = array();
	$tblTmp = $item->toArray();
	$tblTmp['author_url_rewrited'] = "<a href='".$h_bookshop_authors->GetAuthorLink($item->getVar('auth_id'), $item->getVar('auth_name'), $item->getVar('auth_firstname'))."' title='".bookshop_makeHrefTitle($item->getVar('auth_firstname').' '.$item->getVar('auth_name'))."'>".$item->getVar('auth_firstname').' '.$item->getVar('auth_name')."</a>";
	$initiale = strtoupper(substr($item->getVar('auth_name'), 0, 1));
	$auteurTraducteur = $tblType[$item->getVar('auth_type')];
	$tblAnnuaire[$initiale][$auteurTraducteur][] = $tblTmp;
}
$xoopsTpl->assign('authors', $tblAnnuaire);

bookshop_setCSS();
if (file_exists( BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/modinfo.php')) {
	include_once  BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/modinfo.php';
} else {
	include_once  BOOKSHOP_PATH.'language/english/modinfo.php';
}

$title = _MI_BOOKSHOP_SMNAME5.' - '.bookshop_get_module_name();
bookshop_set_metas($title, $title);
include_once XOOPS_ROOT_PATH.'/footer.php';
?>