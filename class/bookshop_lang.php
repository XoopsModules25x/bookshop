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

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

include_once XOOPS_ROOT_PATH . '/kernel/object.php';
if (!class_exists('Bookshop_XoopsPersistableObjectHandler')) {
    include_once XOOPS_ROOT_PATH . '/modules/bookshop/class/PersistableObjectHandler.php';
}

/**
 * Class bookshop_lang
 */
class bookshop_lang extends Bookshop_Object
{
    public function __construct()
    {
        $this->initVar('lang_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('lang_lang', XOBJ_DTYPE_TXTBOX, null, false);
    }
}

/**
 * Class BookshopBookshop_langHandler
 */
class BookshopBookshop_langHandler extends Bookshop_XoopsPersistableObjectHandler
{
    /**
     * @param $db
     */
    public function __construct($db)
    {    //                                             Table               Classe       Id
        parent::__construct($db, 'bookshop_lang', 'bookshop_lang', 'lang_id');
    }

    /**
     * Renvoie la liste de toutes les langues du module
     *
	 * @param integer $start Position de départ
	 * @param integer $limit Nombre total d'enregistrements à renvoyer
	 * @param string $order Champ sur lequel faire le tri
     * @param string  $order   Ordre du tri
	 * @param boolean $idaskey Indique si le tableau renvoyé doit avoir pour clé l'identifiant unique de l'enregistrement
     *
     * @return array tableau d'objets de type langues
     */
    public function GetAllLang($start = 0, $limit = 0, $sort = 'lang_lang', $order = 'ASC', $idaskey = true)
    {
        $critere = new Criteria('lang_id', 0, '<>');
        $critere->setLimit($limit);
        $critere->setStart($start);
        $critere->setSort($sort);
        $critere->setOrder($order);
        $tbl_categs = array();
        $tbl_categs = $this->getObjects($critere, $idaskey);

        return $tbl_categs;
    }
}
