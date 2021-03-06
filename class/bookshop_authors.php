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
 * Class bookshop_authors
 */
class bookshop_authors extends Bookshop_Object
{
    public function __construct()
    {
        $this->initVar('auth_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('auth_type', XOBJ_DTYPE_INT, null, false);
        $this->initVar('auth_name', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('auth_firstname', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('auth_email', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('auth_bio', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('auth_url', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('auth_photo1', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('auth_photo2', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('auth_photo3', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('auth_photo4', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('auth_photo5', XOBJ_DTYPE_TXTBOX, null, false);
        // Pour autoriser le html
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
    }
}

/**
 * Class BookshopBookshop_authorsHandler
 */
class BookshopBookshop_authorsHandler extends Bookshop_XoopsPersistableObjectHandler
{
    /**
     * @param $db
     */
    public function __construct($db)
    {    //                                             Table               Classe               Id
        parent::__construct($db, 'bookshop_authors', 'bookshop_authors', 'auth_id');
    }

    /**
	 * Renvoie le lien à utiliser pour aller vers un auteur
     *
     * @param  integer $auth_id        L'identifiant de l'auteur
     * @param  string  $auth_name      Le nom de l'auteur
	 * @param string $auth_firstname Le prénom de l'auteur
	 * @return string L'URL à utiliser en fonction du paramétrage du module
     */
    public function GetAuthorLink($auth_id, $auth_name, $auth_firstname)
    {
        $url = '';
        if (bookshop_getmoduleoption('urlrewriting') == 1) {    // On utilise l'url rewriting
            $url = BOOKSHOP_URL . 'author-' . (int)$auth_id . bookshop_makeSEOurl($auth_firstname . ' ' . $auth_name) . '.html';
        } else {    // Pas d'utilisation de l'url rewriting
            $url = BOOKSHOP_URL . 'author.php?auth_id=' . (int)$auth_id;
        }

        return $url;
    }

    /**
	 * Renvoie l'alphabet à partir de la première lettre du nom des auteurs et traducteurs
     *
	 * @return array l'alphabet des lettres utilisées !
     */
    public function getAlphabet()
    {
        global $myts;
        $ret    = array();
        $sql    = 'SELECT DISTINCT (UPPER(SUBSTRING(auth_name, 1, 1))) as oneletter FROM ' . $this->table;
        $result = $this->db->query($sql);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $ret[] = $myts->htmlSpecialChars($myrow['oneletter']);
        }

        return $ret;
    }
}
