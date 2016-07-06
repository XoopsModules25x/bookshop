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

define('COMMAND_STATE_NOINFORMATION', 0);    // Pas encore d'informations sur la commande
define("COMMAND_STATE_VALIDATED", 1);		// Commande validée par Paypal
define('COMMAND_STATE_PENDING', 2);            // En attente
define('COMMAND_STATE_FAILED', 3);            // Echec
define("COMMAND_STATE_CANCELED", 4);		// Annulée
define('COMMAND_STATE_FRAUD', 5);            // Fraude

include_once XOOPS_ROOT_PATH . '/kernel/object.php';
if (!class_exists('Bookshop_XoopsPersistableObjectHandler')) {
    include_once XOOPS_ROOT_PATH . '/modules/bookshop/class/PersistableObjectHandler.php';
}

/**
 * Class bookshop_commands
 */
class bookshop_commands extends Bookshop_Object
{
    public function __construct()
    {
        $this->initVar('cmd_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cmd_uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cmd_date', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_state', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cmd_ip', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_lastname', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_firstname', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_adress', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('cmd_zip', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_town', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_country', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_telephone', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_email', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_articles_count', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cmd_total', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_shipping', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_bill', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cmd_password', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cmd_text', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('cmd_cancel', XOBJ_DTYPE_TXTBOX, null, false);
    }
}

/**
 * Class BookshopBookshop_commandsHandler
 */
class BookshopBookshop_commandsHandler extends Bookshop_XoopsPersistableObjectHandler
{
    /**
     * @param $db
     */
    public function __construct($db)
    {    //                                             Table                   Classe           Id
        parent::__construct($db, 'bookshop_commands', 'bookshop_commands', 'cmd_id');
    }

    /**
     * Indique si c'est la premi�re commande d'un client
     *
     * @param  integer $uid Identifiant de l'utilisateur
     * @return boolean Indique si c'est le cas ou pas
     */
    public function isFirstCommand($uid)
    {
        $critere = new Criteria('cmd_uid', (int)$uid, '=');
        if ($this->getCount($critere) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
	 * Indique si un livre a déajà été acheté par un utilisateur
     *
     * @param  integer $uid    Identifiant de l'utilisateur
     * @param  integer $bookId Identifiant du livre
     * @return boolean Indique si c'est le cas ou pas
     */
    public function BookAlreadyBought($uid, $bookId)
    {
        $sql    = 'SELECT Count(*) as cpt FROM ' . $this->db->prefix('bookshop_caddy') . ' c, ' . $this->db->prefix('bookshop_commands') . ' f WHERE c.caddy_book_id = ' . (int)$bookId . ' AND c.caddy_cmd_id = f.cmd_id AND f.cmd_uid = ' . (int)$uid;
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
	 * Mise à jour des stocks pour chaque livre composant la commande
     * @param  integer $cmd_id Identifiant de la commande
     * @return void
     */
    public function updateStocks($cmd_id)
    {
        global $h_bookshop_caddy, $h_bookshop_books;
        // Recherche de tous les livres du caddy
        $caddy  = $h_bookshop_caddy->getCaddyFromCommand($cmd_id);
        $tblTmp = $tblBooks = array();
        foreach ($caddy as $item) {
            $tblTmp[] = $item->getVar('caddy_book_id');
        }
        // Chargement de tous les livres
        $critere  = new Criteria('book_id', '(' . implode(',', $tblTmp) . ')', 'IN');
        $tblBooks = $h_bookshop_books->getObjects($critere, true);
		// Boucle sur le caddy pour mettre à jour les quantités
        foreach ($caddy as $item) {
            if (isset($tblBooks[$item->getVar('caddy_book_id')])) {
                $book = $tblBooks[$item->getVar('caddy_book_id')];
                $h_bookshop_books->decreaseStock($book, $item->getVar('caddy_qte'));
				$h_bookshop_books->verifyLowStock($book);	// Vérification du stock d'alerte
            }
        }

        return true;
    }

    /**
	 * Validation d'une commande et mise à jour des stocks
     *
     * @param  integer $cmd_id Identifiant de la commande
     * @return boolean Indique si la validation de la commande s'est bien faite ou pas
     */
    public function validateCommand($cmd_id)
    {
        $retval   = false;
        $commande =& $this->get($cmd_id);
        if (is_object($commande)) {
            $commande->setVar('cmd_state', COMMAND_STATE_VALIDATED);
            $retval = $this->insert($commande, true);
            if ($retval) {
                $this->updateStocks($cmd_id);
            }
        }

        return $retval;
    }
}
