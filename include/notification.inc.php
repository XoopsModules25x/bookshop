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

function bookshop_notify_iteminfo($category, $item_id)
{
	global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;

	if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != 'bookshop') {
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname('bookshop');
		$config_handler =& xoops_gethandler('config');
		$config =& $config_handler->getConfigsByCat(0,$module->getVar('mid'));
	} else {
		$module =& $xoopsModule;
		$config =& $xoopsModuleConfig;
	}

	if ($category == 'global') {
		$item['name'] = '';
		$item['url'] = '';
		return $item;
	}

	if ($category == 'new_category') {
		include BOOKSHOP_PATH.'include/common.php';
		$category = null;
		$category = $h_bookshop_cat->get($item_id);
		if(is_object($category)) {
			$item['name'] = $category->getVar('cat_title');
			$item['url'] = BOOKSHOP_URL.'category.php?cat_cid=' . $item_id;
		}
		return $item;
	}

	if ($category == 'new_book') {
		include BOOKSHOP_PATH.'include/common.php';
		$book = null;
		$book = $h_bookshop_books->get($item_id);
		if(is_object($book)) {
			$item['name'] = $book->getVar('book_title');
			$item['url'] = BOOKSHOP_URL.'book.php?book_id=' . $item_id;
		}
		return $item;
	}
}
?>