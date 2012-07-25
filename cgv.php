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
 * Affichage des conditions générales de vente
 */
include 'header.php';
$GLOBALS['current_category'] = -1;
$xoopsOption['template_main'] = 'bookshop_cgv.html';
include_once XOOPS_ROOT_PATH.'/header.php';
include_once BOOKSHOP_PATH.'class/registryfile.php';

$registry = new bookshop_registryfile();

$xoopsTpl->assign('nostock_msg', bookshop_getmoduleoption('nostock_msg'));
$xoopsTpl->assign('mod_pref', $mod_pref);	// Préférences du module
$xoopsTpl->assign('cgv_msg', $registry->getfile('bookshop_cgv.txt'));

bookshop_setCSS();
bookshop_set_metas(_BOOKSHOP_CGV.' '.bookshop_get_module_name(), _BOOKSHOP_CGV.' '.bookshop_get_module_name());
include_once(XOOPS_ROOT_PATH.'/footer.php');
?>
