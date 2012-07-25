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

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH.'/class/xoopsobject.php';
if (!class_exists('Bookshop_XoopsPersistableObjectHandler')) {
	include_once XOOPS_ROOT_PATH.'/modules/bookshop/class/PersistableObjectHandler.php';
}

class bookshop_booksauthors extends Bookshop_Object
{
	function bookshop_booksauthors()
	{
		$this->initVar('ba_id',XOBJ_DTYPE_INT,null,false);
		$this->initVar('ba_book_id',XOBJ_DTYPE_INT,null,false);
		$this->initVar('ba_auth_id',XOBJ_DTYPE_INT,null,false);
		$this->initVar('ba_type',XOBJ_DTYPE_INT,null,false);
		// Pour autoriser le html
		$this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
	}
}


class BookshopBookshop_booksauthorsHandler extends Bookshop_XoopsPersistableObjectHandler
{
	function BookshopBookshop_booksauthorsHandler($db)
	{	//													Table				Classe				 		Id
		$this->BookXoopsPersistableObjectHandler($db, 'bookshop_booksauthors', 'bookshop_booksauthors', 'ba_id');
	}
}
?>