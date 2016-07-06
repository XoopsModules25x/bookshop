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
/**
 * Page called by Paypal in the case of cancellation of an order
 */
include __DIR__ . '/header.php';
$GLOBALS['current_category']  = -1;
$xoopsOption['template_main'] = 'bookshop_cancelpurchase.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

include_once BOOKSHOP_PATH . 'class/bookshop_paypal.php';
if (isset($_GET['id'])) {
    $critere = new Criteria('cmd_cancel', $myts->addSlashes($_GET['id']), '=');
    $cnt     = 0;
    $tblCmd  = array();
    $cnt     = $h_bookshop_commands->getCount($critere);
    if ($cnt > 0) {
        $tblCmd = $h_bookshop_commands->getObjects($critere);
        if (count($tblCmd) > 0) {
            $commande = null;
            $commande = $tblCmd[0];
            if (is_object($commande)) {
                $commande->setVar('cmd_state', COMMAND_STATE_CANCELED);
                $h_bookshop_commands->insert($commande, true);
                $msg['NUM_COMMANDE'] = $commande->getVar('cmd_id');
                bookshop_send_email_from_tpl('command_shop_cancel.tpl', bookshop_getEmailsFromGroup(bookshop_getmoduleoption('grp_sold')), _BOOKSHOP_ORDER_CANCELED, $msg);
                bookshop_send_email_from_tpl('command_client_cancel.tpl', $commande->getVar('cmd_email'), _BOOKSHOP_ORDER_CANCELED, $msg);
            }
        }
        $h_bookshop_caddy->emptyCart();
    }
}

$title = _BOOKSHOP_VALIDATE_CMD . ' - ' . bookshop_get_module_name();
bookshop_set_metas($title, $title);
bookshop_setCSS();
include_once(XOOPS_ROOT_PATH . '/footer.php');
