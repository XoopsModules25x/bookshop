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
 * Page appelée par Paypal dans le cas de l'utilisation de l'IPN
 */
@error_reporting(0);
include 'header.php';
include_once BOOKSHOP_PATH.'class/bookshop_paypal.php';

$log = '';
$req = 'cmd=_notify-validate';
$slashes = get_magic_quotes_gpc();
foreach ($_POST as $key => $value) {
	if($slashes) {
		$log .= "$key=".stripslashes($value)."\n";
		$value = urlencode(stripslashes($value));
	} else {
		$log .= "$key=".$value."\n";
		$value = urlencode($value);
	}
	$req .= "&$key=$value";
}

$paypal = new bookshop_paypal(bookshop_getmoduleoption('paypal_test'), bookshop_getmoduleoption('paypal_email'), bookshop_getmoduleoption('paypal_money'), true);
$url = $paypal->getURL(true);
$header = '';
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: ". strlen($req)."\r\n\r\n";
$errno = 0;
$errstr = '';
$fp = fsockopen ($url, 80, $errno, $errstr, 30);
if ($fp) {
	fputs ($fp, "$header$req");
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		if (strcmp($res, "VERIFIED") == 0) {
			$log .= "VERIFIED\t";
			$paypalok = true;
			if (strtoupper($_POST['payment_status']) != 'COMPLETED') $paypalok = false;
			if (strtoupper($_POST['receiver_email']) != strtoupper(bookshop_getmoduleoption('paypal_email'))) $paypalok = false;
			if (strtoupper($_POST['mc_currency']) != strtoupper(bookshop_getmoduleoption('paypal_money'))) $paypalok = false;
			if (!$_POST['custom']) $paypalok = false;
			$montant = $_POST['mc_gross'];
			if ($paypalok) {
				$ref = intval($_POST['custom']);	// Numéro de la commande
				$commande = null;
				$commande = $h_bookshop_commands->get($ref);
				if(is_object($commande)) {
					$msg['NUM_COMMANDE'] = $ref;
					if($montant == $commande->getVar('cmd_total')) {	// Commande vérifiée
						$h_bookshop_commands->validateCommand($ref);	// Validation de la commande et mise à jour des stocks
						bookshop_send_email_from_tpl('command_shop_verified.tpl', bookshop_getEmailsFromGroup(bookshop_getmoduleoption('grp_sold')), _BOOKSHOP_PAYPAL_VALIDATED, $msg);
						bookshop_send_email_from_tpl('command_client_verified.tpl', $commande->getVar('cmd_email'), sprintf(_BOOKSHOP_PAYPAL_VALIDATED, $xoopsConfig['sitename']), $msg);
					} else {
						$commande->setVar('cmd_state', COMMAND_STATE_FRAUD);
						$h_bookshop_commands->insert($commande, true);
						bookshop_send_email_from_tpl('command_shop_fraud.tpl', bookshop_getEmailsFromGroup(bookshop_getmoduleoption('grp_sold')), _BOOKSHOP_PAYPAL_FRAUD, $msg);
					}
				}
        	} else {
				if(isset($_POST['custom'])) {
					$ref = intval($_POST['custom']);
					$msg['NUM_COMMANDE'] = $ref;
					$commande = null;
					$commande = $h_bookshop_commands->get($ref);
					if(is_object($commande)) {
						switch(strtoupper($_POST['payment_status'])) {
							case 'PENDING':
								$commande->setVar('cmd_state', COMMAND_STATE_PENDING);	// En attente
								$h_bookshop_commands->insert($commande, true);
								bookshop_send_email_from_tpl('command_shop_pending.tpl', bookshop_getEmailsFromGroup(bookshop_getmoduleoption('grp_sold')), _BOOKSHOP_PAYPAL_PENDING, $msg);
								break;
							case 'FAILED':
								$commande->setVar('cmd_state', COMMAND_STATE_FAILED);	// Echec
								$h_bookshop_commands->insert($commande, true);
								bookshop_send_email_from_tpl('command_shop_failed.tpl', bookshop_getEmailsFromGroup(bookshop_getmoduleoption('grp_sold')), _BOOKSHOP_PAYPAL_FAILED, $msg);
								break;
						}
					}
				}
        	}
 		} else {
			$log .= "$res\n";
		}
	}
	fclose ($fp);
} else {
	$log .= "Error with the fsockopen function, unable to open communication ' : ($errno) $errstr\n";
}
$fp = fopen(XOOPS_UPLOAD_PATH.'/logpaypal_bookshop.txt', 'a');
if($fp) {
	fwrite($fp, str_repeat('-',120)."\n");
	fwrite($fp, date('d/m/Y H:i:s')."\n");
	if(isset($_POST['txn_id'])) {
		fwrite($fp, "Transaction : ".$_POST['txn_id']."\n");
	}
	fwrite($fp, "Result : ".$log."\n");
	fclose($fp);
}
?>
