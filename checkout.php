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
 * Entering Customer Data + display information entered for validation with redirection to Paypal
 */
include __DIR__ . '/header.php';
$GLOBALS['current_category']  = -1;
$xoopsOption['template_main'] = 'bookshop_command.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once BOOKSHOP_PATH . 'class/bookshop_paypal.php';

$op = 'default';
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

$xoopsTpl->assign('op', $op);
$cartForTemplate      = array();
$emptyCart            = false;
$shippingAmount       = $commandAmount = $vatAmount = $commandAmountTTC = 0;
$goOn                 = '';
$discountsDescription = array();

function listCart()
{
    global $h_bookshop_caddy, $cartForTemplate, $emptyCart, $shippingAmount, $commandAmount, $vatAmount, $goOn, $commandAmountTTC, $discountsDescription;
    $h_bookshop_caddy->computeCart($cartForTemplate, $emptyCart, $shippingAmount, $commandAmount, $vatAmount, $goOn, $commandAmountTTC, $discountsDescription);
    $dec              = bookshop_getmoduleoption('decimals_count');
    $shippingAmount   = sprintf('%0.' . $dec . 'f', $shippingAmount);        // Amount of postage
    $commandAmount    = sprintf('%0.' . $dec . 'f', $commandAmount);        // Amount HT command
    $vatAmount        = sprintf('%0.' . $dec . 'f', $vatAmount);                // Amount of VAT
    $commandAmountTTC = sprintf('%0.' . $dec . 'f', $commandAmountTTC);    // Amount TTC of the order (shipping included)
}

$tbl_country = XoopsLists::getCountryList();
if (is_object($xoopsUser)) {
    $uid = $xoopsUser->getVar('uid');
} else {
    $uid = 0;
}

switch ($op) {
    // ****************************************************************************************************************
    case 'default': // Submitting Forms
        // ****************************************************************************************************************
        if ($h_bookshop_caddy->isCartEmpty()) {
            bookshop_redirect(_BOOKSHOP_CART_IS_EMPTY, BOOKSHOP_URL, 4);
        }
        listCart();
        $notFound = true;

        if ($uid > 0) {    // Si c'est un utlisateur enregistr�, on recherche dans les anciennes commandes pour pr� remplir les champs
            $tblCommand  = array();
            $critereUser = new Criteria('cmd_uid', $uid, '=');
            $critereUser->setSort('cmd_date');
            $critereUser->setOrder('DESC');
            $critereUser->setLimit(1);
            $tblCommand = $h_bookshop_commands->getObjects($critereUser, false);
            if (count($tblCommand) > 0) {
                $notFound = false;
                $commande = $tblCommand[0];
            }
        }

        if ($notFound) {
            $commande = $h_bookshop_commands->create(true);
        }

        $sform = new XoopsThemeForm(_BOOKSHOP_PLEASE_ENTER, 'informationfrm', BOOKSHOP_URL . 'checkout.php', 'post');
        $sform->addElement(new XoopsFormHidden('op', 'paypal'));
        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_TOTAL, $commandAmountTTC . ' ' . bookshop_getmoduleoption('money_full')));
        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_SHIPPING_PRICE, $shippingAmount . ' ' . bookshop_getmoduleoption('money_full')));
        $sform->addElement(new XoopsFormText(_BOOKSHOP_LASTNAME, 'cmd_lastname', 50, 255, $commande->getVar('cmd_lastname')), true);
        $sform->addElement(new XoopsFormText(_BOOKSHOP_FIRSTNAME, 'cmd_firstname', 50, 255, $commande->getVar('cmd_firstname')), false);
        $sform->addElement(new XoopsFormTextArea(_BOOKSHOP_STREET, 'cmd_adress', $commande->getVar('cmd_adress'), 3, 50), true);
        $sform->addElement(new XoopsFormText(_BOOKSHOP_CP, 'cmd_zip', 5, 30, $commande->getVar('cmd_zip')), true);
        $sform->addElement(new XoopsFormText(_BOOKSHOP_CITY, 'cmd_town', 40, 255, $commande->getVar('cmd_town')), true);
        $sform->addElement(new XoopsFormSelectCountry(_BOOKSHOP_COUNTRY, 'cmd_country', $commande->getVar('cmd_country')), true);
        $sform->addElement(new XoopsFormText(_BOOKSHOP_PHONE, 'cmd_telephone', 15, 50, $commande->getVar('cmd_telephone')), false);
        if ($uid > 0) {
            $sform->addElement(new XoopsFormText(_BOOKSHOP_EMAIL, 'cmd_email', 50, 255, $xoopsUser->getVar('email')), true);
        } else {
            $sform->addElement(new XoopsFormText(_BOOKSHOP_EMAIL, 'cmd_email', 50, 255, ''), true);
        }
        $sform->addElement(new XoopsFormRadioYN(_BOOKSHOP_INVOICE, 'cmd_bill', 0), true);

        $button_tray = new XoopsFormElementTray('', '');
        $submit_btn  = new XoopsFormButton('', 'post', _BOOKSHOP_SAVE, 'submit');
        $button_tray->addElement($submit_btn);
        $sform->addElement($button_tray);

        $sform = bookshop_formMarkRequiredFields($sform);
        $xoopsTpl->assign('form', $sform->render());
        break;

    // ****************************************************************************************************************
    case 'paypal':    // Validation finale avant envoi sur Paypal
        // ****************************************************************************************************************
        if ($h_bookshop_caddy->isCartEmpty()) {
            bookshop_redirect(_BOOKSHOP_CART_IS_EMPTY, BOOKSHOP_URL, 4);
        }
        listCart();

        $password       = md5(xoops_makepass());
        $passwordCancel = md5(xoops_makepass());
        $paypal         = new bookshop_paypal(bookshop_getmoduleoption('paypal_test'), bookshop_getmoduleoption('paypal_email'), bookshop_getmoduleoption('paypal_money'), true, $passwordCancel);

        $commande = $h_bookshop_commands->create(true);
        $commande->setVars($_POST);
        $commande->setVar('cmd_uid', $uid);
        $commande->setVar('cmd_date', date('Y-m-d'));
        $commande->setVar('cmd_state', COMMAND_STATE_NOINFORMATION);
        $commande->setVar('cmd_ip', bookshop_IP());
        $commande->setVar('cmd_articles_count', count($cartForTemplate));
        $commande->setVar('cmd_total', $commandAmountTTC);
        $commande->setVar('cmd_shipping', $shippingAmount);
        $commande->setVar('cmd_password', $password);
        $commande->setVar('cmd_cancel', $passwordCancel);
        $commande->setVar('cmd_text', implode("\n", $discountsDescription));
        $res = $h_bookshop_commands->insert($commande, true);
        if (!$res) {    // Si la sauvegarde n'a pas fonctionn�
            bookshop_redirect(_BOOKSHOP_ERROR10, BOOKSHOP_URL, 6);
        }

        // Enregistrement du panier
        $msgCommande = '';
        foreach ($cartForTemplate as $line) {
            $panier = $h_bookshop_caddy->create(true);
            $panier->setVar('caddy_book_id', $line['book_id']);
            $panier->setVar('caddy_qte', $line['book_qty']);
            $panier->setVar('caddy_price', $line['book_price_ttc']);    // Attention, prix TTC
            $panier->setVar('caddy_cmd_id', $commande->getVar('cmd_id'));
            $panier->setVar('caddy_shipping', $line['book_shipping_amount']);
            $msgCommande .= str_pad(wordwrap($line['book_title'], 60), 60, ' ') . ' ' . str_pad($line['book_qty'] . ' ' . _BOOKSHOP_COPY_COUNT, 8, ' ', STR_PAD_LEFT) . ' ' . str_pad($line['book_price_ttc'] . ' ' . bookshop_getmoduleoption('money_short'), 10, ' ', STR_PAD_LEFT) . ' '
                            . str_pad($line['book_shipping_amount'] . ' ' . bookshop_getmoduleoption('money_short'), 10, ' ', STR_PAD_LEFT) . "\n";
            $res = $h_bookshop_caddy->insert($panier, true);
        }
        $msgCommande .= "\n\n" . _BOOKSHOP_SHIPPING_PRICE . ' ' . $shippingAmount . ' ' . bookshop_getmoduleoption('money_full') . "\n";
        $msgCommande .= _BOOKSHOP_TOTAL . ' ' . $commandAmountTTC . ' ' . bookshop_getmoduleoption('money_full') . "\n";
        if (count($discountsDescription) > 0) {
            $msgCommande .= "\n\n" . _BOOKSHOP_CART4 . "\n";
            $msgCommande .= implode("\n", $discountsDescription);
            $msgCommande .= "\n";
        }
        $msg                 = array();
        $msg['COMMANDE']     = $msgCommande;
        $msg['NUM_COMMANDE'] = $commande->getVar('cmd_id');
        $msg['NOM']          = $commande->getVar('cmd_lastname');
        $msg['PRENOM']       = $commande->getVar('cmd_firstname');
        $msg['ADRESSE']      = $commande->getVar('cmd_adress');
        $msg['CP']           = $commande->getVar('cmd_zip');
        $msg['VILLE']        = $commande->getVar('cmd_town');
        $msg['PAYS']         = $tbl_country[$commande->getVar('cmd_country')];
        $msg['TELEPHONE']    = $commande->getVar('cmd_telephone');
        $msg['EMAIL']        = $commande->getVar('cmd_email');
        $msg['URL_BILL']     = BOOKSHOP_URL . 'invoice.php?command=' . $commande->getVar('cmd_id') . '&pass=' . $password;
        $msg['IP']           = bookshop_IP();
        if ($commande->getVar('cmd_bill') == 1) {
            $msg['FACTURE'] = _YES;
        } else {
            $msg['FACTURE'] = _NO;
        }
        // Envoi du mail au client
        bookshop_send_email_from_tpl('command_client.tpl', $commande->getVar('cmd_email'), sprintf(_BOOKSHOP_THANKYOU_CMD, $xoopsConfig['sitename']), $msg);
        // Envoi du mail au groupe de personne devant recevoir le mail
        bookshop_send_email_from_tpl('command_shop.tpl', bookshop_getEmailsFromGroup(bookshop_getmoduleoption('grp_sold')), _BOOKSHOP_NEW_COMMAND, $msg);

        // Presentation of the form to send to Paypal
        $payURL = $paypal->getURL();

        // Final presentation with hidden variables basket  ******************************
        $sform    = new XoopsThemeForm(_BOOKSHOP_PAY_PAYPAL, 'payform', $payURL, 'post');
        $elements = array();
        $elements = $paypal->getFormContent($commande->getVar('cmd_id'), $commandAmountTTC, $commande->getVar('cmd_email'));
        foreach ($elements as $key => $value) {
            $sform->addElement(new XoopsFormHidden($key, $value));
        }

        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_TOTAL, $commandAmountTTC . ' ' . bookshop_getmoduleoption('money_full')));
        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_SHIPPING_PRICE, $shippingAmount . ' ' . bookshop_getmoduleoption('money_full')));
        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_LASTNAME, $commande->getVar('cmd_lastname')));
        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_FIRSTNAME, $commande->getVar('cmd_firstname')));
        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_STREET, $commande->getVar('cmd_adress')));
        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_CP, $commande->getVar('cmd_zip')));
        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_CITY, $commande->getVar('cmd_town')));
        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_COUNTRY, $tbl_country[$commande->getVar('cmd_country')]));
        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_PHONE, $commande->getVar('cmd_telephone')));
        $sform->addElement(new XoopsFormLabel(_BOOKSHOP_EMAIL, $commande->getVar('cmd_email')));

        if ($commande->getVar('cmd_bill') == 0) {
            $sform->addElement(new XoopsFormLabel(_BOOKSHOP_INVOICE, _NO));
        } else {
            $sform->addElement(new XoopsFormLabel(_BOOKSHOP_INVOICE, _YES));
        }
        $button_tray = new XoopsFormElementTray('', '');
        $submit_btn  = new XoopsFormButton('', 'post', _BOOKSHOP_PAY_PAYPAL, 'submit');
        $button_tray->addElement($submit_btn);
        $sform->addElement($button_tray);
        $xoopsTpl->assign('form', $sform->render());
        break;
}
$title = _BOOKSHOP_VALIDATE_CMD . ' - ' . bookshop_get_module_name();
bookshop_set_metas($title, $title);
bookshop_setCSS();
include_once(XOOPS_ROOT_PATH . '/footer.php');
