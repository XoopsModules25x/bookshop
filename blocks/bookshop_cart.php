<?php
/**
 * ****************************************************************************
 * bookshop - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * Created on 9 nov. 07 at 18:27:02
 * ****************************************************************************
 */

 /**
  * block to display items in cart
  *
  * @param integer $options[0] Count of items to show (0 = no limit)
  * @return array Block's content
  */
function b_bookshop_cart_show($options)
{
	global $mod_pref;
	include XOOPS_ROOT_PATH.'/modules/bookshop/include/common.php';
	$booksCount = intval($options[0]);

	$cartForTemplate = $block = array();
	$emptyCart = false;
	$shippingAmount = 0;
	$commandAmount = 0;
	$vatAmount = 0;
	$goOn = '';
	$commandAmountTTC = 0;
	$discountsDescription = array();
	// Calcul du montant total du caddy
	$h_bookshop_caddy->computeCart($cartForTemplate, $emptyCart, $shippingAmount, $commandAmount, $vatAmount, $goOn, $commandAmountTTC, $discountsDescription);
	$dec = bookshop_getmoduleoption('decimals_count');
	if($emptyCart) {
		return '';
	}
	$block['block_money_full'] = bookshop_getmoduleoption('money_full');
	$block['block_money_short'] = bookshop_getmoduleoption('money_short');
	$block['block_shippingAmount'] = sprintf("%0.".$dec.'f', $shippingAmount);		// Montant des frais de port
	$block['block_commandAmount'] = sprintf("%0.".$dec.'f', $commandAmount);		// Montant HT de la commande
	$block['block_vatAmount'] = sprintf("%0.".$dec.'f', $vatAmount);				// Montant de la TVA
	$block['block_commandAmountTTC'] = sprintf("%0.".$dec.'f', $commandAmountTTC);	// Montant TTC de la commande
	$block['block_discountsDescription'] = $discountsDescription;					// Liste des réductions accordées
	if( ($booksCount > 0) && (count($cartForTemplate) > $booksCount)) {
		array_slice($cartForTemplate, 0, $booksCount-1);
	}
	$block['block_caddieProducts'] = $cartForTemplate;								// Produits dans le caddy
	return $block;
}

function b_bookshop_cart_edit($options)
{
	// '4';	// Voir 4 livres du caddy.
	global $xoopsConfig;
	include XOOPS_ROOT_PATH.'/modules/bookshop/include/common.php';
	$form = '';
	$form .= "<table border='0'>";
	$form .= '<tr><td>'._MB_BOOKSHOP_MAX_ITEMS . "</td><td><input type='text' name='options[]' id='options' value='".$options[0]."' /></td></tr>";
	$form .= '</table>';
	return $form;
}
?>