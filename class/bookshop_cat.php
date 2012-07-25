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

class bookshop_cat extends Bookshop_Object
{
	function bookshop_cat()
	{
		$this->initVar('cat_cid',XOBJ_DTYPE_INT,null,false);
		$this->initVar('cat_pid',XOBJ_DTYPE_INT,null,false);
		$this->initVar('cat_title',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('cat_imgurl',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('cat_description',XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar('cat_advertisement',XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar('cat_metakeywords',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('cat_metadescription',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('cat_metatitle',XOBJ_DTYPE_TXTBOX,null,false);
		// Pour autoriser le html
		$this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
	}
}


class BookshopBookshop_catHandler extends Bookshop_XoopsPersistableObjectHandler
{
	function BookshopBookshop_catHandler($db)
	{	//												Table				Classe		 Id
		$this->BookXoopsPersistableObjectHandler($db, 'bookshop_cat', 'bookshop_cat', 'cat_cid');
	}

	/**
	 * Renvoie (sous forme d'objet) la liste de toutes les catégories
	 *
	 * @param integer $start Indice de début de recherche
	 * @param integer $limit Nombre maximum d'enregsitrements à renvoyer
	 * @param string $sort Champ à utiliser pour le tri
	 * @param string $order Ordre du tire (asc ou desc)
	 * @param boolean $idaskey Indique s'il faut renvoyer un tableau dont la clé est l'identifiant de l'enregistrement
	 * @return array Taleau d'objets (catégories)
	 */
	function GetAllCategories($start = 0, $limit = 0, $sort = 'cat_title', $order='ASC', $idaskey = true)
	{
		$critere = new Criteria('cat_cid', 0 ,'<>');
		$critere->setLimit($limit);
		$critere->setStart($start);
		$critere->setSort($sort);
		$critere->setOrder($order);
		$tbl_categs = array();
		$tbl_categs = $this->getObjects($critere, $idaskey);
		return $tbl_categs;
	}

	/**
	 * Renvoie le lien à utiliser pour aller vers une catégorie
	 *
	 * @param integer $cat_id Identifiant de la catégorie
	 * @param string $cat_title Titre de la catégorie
	 */
	function GetCategoryLink($cat_cid, $cat_title)
	{
		$url = '';
		if(bookshop_getmoduleoption('urlrewriting') == 1) {	// On utilise l'url rewriting
			$url = BOOKSHOP_URL.'category-'.intval($cat_cid).bookshop_makeSEOurl($cat_title).'.html';
		} else {	// Pas d'utilisation de l'url rewriting
			$url = BOOKSHOP_URL.'category.php?cat_cid='.intval($cat_cid);
		}
		return $url;
	}
}
?>