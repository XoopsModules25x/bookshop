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
 * Classe responsable de la gestion de tout ce qui est relatif à Paypal
 */

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

class bookshop_paypal
{
	var $testMode;
	var $email;
	var $moneyCode;
	var $useIpn;
	var $passwordCancel;

	function bookshop_paypal($testMode, $emailPaypal, $moneyCode, $ipn=false, $passwordCancel='')
	{
		$this->testMode = $testMode;
		$this->email = $emailPaypal;
		$this->moneyCode = $moneyCode;
		$this->useIpn = $ipn;
		$this->passwordCancel = $passwordCancel;
	}

	/**
	 * Renvoie l'url à utiliser en tenant compte du fait qu'on est en mode test ou pas
	 */
	 function getURL($securized=false)
	 {
	 	if(!$securized) {
	 		if($this->testMode == 1 ) {
   				return 'https://www.sandbox.paypal.com/cgi-bin/webscr';
			} else {
				return 'https://www.paypal.com/cgi-bin/webscr';
	 		}
	 	} else {
	 		if($this->testMode == 1 ) {
   				return 'www.sandbox.paypal.com';
			} else {
				return 'www.paypal.com';
	 		}
	 	}
	 }

	/**
	 * Renvoie les éléments à ajouter au formulaire en tant que zones cachées
	 *
	 * @param integer $commmandId Numéro de la commande
	 * @param float $ttc TTC à facturer
	 */
	function getFormContent($commandId, $ttc, $emailClient)
	{
		global $xoopsConfig;
		$ret = array();
		$ret['cmd'] = '_xclick';
		$ret['upload'] = '1';
		$ret['currency_code'] = $this->moneyCode;
		$ret['business'] = $this->email;
		$ret['return'] = BOOKSHOP_URL.'thankyou.php';			// Page (générique) de remerciement après paiement
		$ret['image_url'] = XOOPS_URL.'/images/logo.gif';
		$ret['cpp_header_image'] = XOOPS_URL.'/images/logo.gif';
		$ret['invoice'] = $commandId;
		$ret['item_name'] = _BOOKSHOP_COMMAND.$commandId.' - '.$xoopsConfig['sitename'];
		$ret['item_number'] =  $commandId;
		$ret['tax'] = 0;	// ajout 25/03/2008
		$ret['amount'] = $ttc;
		$ret['custom'] = $commandId;
		//$ret['rm'] = 2;	// Renvoyer les données par POST (normalement)
		$ret['email'] = $emailClient;
		// paypal_pdt
		if(xoops_trim($this->passwordCancel) != '') {	// URL à laquelle le navigateur du client est ramené si le paiement est annulé
			$ret['cancel_return'] = BOOKSHOP_URL.'cancel-payment.php?id='.$this->passwordCancel;
		}
		if($this->useIpn == 1) {
			$ret['notify_url'] = BOOKSHOP_URL.'paypal-notify.php';
		}
		return $ret;
	}
}
?>
