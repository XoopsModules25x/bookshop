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
 * Page appelée par Paypal après le paiement en ligne
 */
include 'header.php';
$GLOBALS['current_category'] = -1;
include_once BOOKSHOP_PATH.'class/bookshop_paypal.php';
//@error_reporting(0);
$success = true;
$datasPaypal = false;
// Implémentation du transfert des données de paiement
/*
if(xoops_trim(bookshop_getmoduleoption('paypal_pdt')) != '' && isset($_GET['tx'])) {	// Paypal PDT
	$paypal = new bookshop_paypal(bookshop_getmoduleoption('paypal_test'), bookshop_getmoduleoption('paypal_email'), bookshop_getmoduleoption('paypal_money'), true);
	$url = $paypal->getURL(true);
	$datas['cmd'] = '_notify-synch';
	$datas['tx'] = $_GET['tx'];
	$datas['at'] =  bookshop_getmoduleoption('paypal_pdt');
	$datas['submit'] = 'PDT';
	$header = bookshop_post_it($datas, $url);
	$errno = 0;
	$errstr = '';
	$log = '';
	$fp = fsockopen ($url, 80, $errno, $errstr, 30);
	if ($fp) {
		fputs ($fp, "$header");
		while (!feof($fp)) {
			$res = fgets ($fp, 1024);
			if (strcmp($res, "SUCCESS") == 0) {
				$success = true;
			}
			$log .= $res ."\n";
		}
		fclose ($fp);
	}

	$fp = fopen(XOOPS_UPLOAD_PATH.'/logpaypal2_bookshop.txt', 'a');
	if($fp) {
		fwrite($fp, str_repeat('-',120)."\n");
		fwrite($fp, date('d/m/Y H:i:s')."\n");
		if(isset($_POST['txn_id'])) {
			fwrite($fp, "Transaction : ".$_POST['txn_id']."\n");
		}
		fwrite($fp, "Result : ".$log."\n");
		fclose($fp);
	}
}
*/
$success = true;

$xoopsOption['template_main'] = 'bookshop_thankyou.html';
include_once XOOPS_ROOT_PATH.'/header.php';
$h_bookshop_caddy->emptyCart();
$xoopsTpl->assign('success', $success);

$title = _BOOKSHOP_PURCHASE_FINSISHED.' - '.bookshop_get_module_name();
bookshop_set_metas($title, $title);
bookshop_setCSS();
include_once(XOOPS_ROOT_PATH.'/footer.php');
?>
