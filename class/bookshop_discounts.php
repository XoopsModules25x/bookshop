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
	exit('XOOPS root path not defined');
}

include_once XOOPS_ROOT_PATH.'/class/xoopsobject.php';
if (!class_exists('Bookshop_XoopsPersistableObjectHandler')) {
	include_once XOOPS_ROOT_PATH.'/modules/bookshop/class/PersistableObjectHandler.php';
}

define('DISCOUNT_TYPE1', 0);		// Pourcent
define('DISCOUNT_TYPE2', 1);		// Euros

define('DISCOUNT_ON1', 0);			// Le montant global de la commande
define('DISCOUNT_ON2', 1);			// Tous les livres
define('DISCOUNT_ON3', 2);			// Chaque livre
define('DISCOUNT_ON4', 3);			// Les frais de ports de tous les livres
define('DISCOUNT_ON5', 4);			// Les frais de ports de tous les livres

define('DISCOUNT_WHEN1', 0);			// Dans tous les cas
define('DISCOUNT_WHEN2', 1);			// Si c'est le premier achat de l'utilisateur sur le site
define('DISCOUNT_WHEN3', 2);			// Si le livre n'a jamais été acheté
define('DISCOUNT_WHEN4', 3);			// Sur la quantité

define('DISCOUNT_SHIPPING1', 0);			// Frais de port, A payer dans leur intégralité
define('DISCOUNT_SHIPPING2', 1);			// Frais de port, Totalement gratuit
define('DISCOUNT_SHIPPING3', 2);			// Sont de x euros pour le premier article puis de x euros par article

class bookshop_discounts extends Bookshop_Object
{
	function bookshop_discounts()
	{
		$this->initVar('disc_id',XOBJ_DTYPE_INT,null,false);
		$this->initVar('disc_group',XOBJ_DTYPE_INT,null,false);
		$this->initVar('disc_amount',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('disc_percent_monney',XOBJ_DTYPE_INT,null,false);
		$this->initVar('disc_on_what',XOBJ_DTYPE_INT,null,false);
		$this->initVar('disc_when',XOBJ_DTYPE_INT,null,false);
		$this->initVar('disc_shipping',XOBJ_DTYPE_INT,null,false);		
		$this->initVar('disc_shipping_amount',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('disc_shipping_amount_next',XOBJ_DTYPE_TXTBOX,null,false);		
		$this->initVar('disc_if_amount',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('disc_description',XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar('disc_qty_criteria',XOBJ_DTYPE_INT,null,false);
		$this->initVar('disc_qty_value',XOBJ_DTYPE_INT,null,false);
		// Pour autoriser le html
		$this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
	}
}


class BookshopBookshop_discountsHandler extends Bookshop_XoopsPersistableObjectHandler
{
	function BookshopBookshop_discountsHandler($db)
	{	//												Table					Classe	 			Id
		$this->BookXoopsPersistableObjectHandler($db, 'bookshop_discounts', 'bookshop_discounts', 'disc_id');
	}

	/**
	 * Renvoie la liste des groupes de l'utlisateur courant
	 */
	function getCurrentMemberGroups()
	{
		global $xoopsUser;
		static $tblBuffer = array();

		if(is_array($tblBuffer) && count($tblBuffer) > 0 ) {

		} else {
			if(is_object($xoopsUser)) {
				$uid = $xoopsUser->getVar('uid');
			} else {
				$uid = 0;
			}
			if($uid > 0) {
				$member_handler =& xoops_gethandler('member');
				$tblBuffer = $member_handler->getGroupsByUser($uid, false);	// Renvoie un tableau d'ID (de groupes)
			} else {
				$tblBuffer = array(XOOPS_GROUP_ANONYMOUS);
			}
		}
		return $tblBuffer;
	}

	/**
	 * Renvoie la liste des règles à appliquer sur chaque livre (avec gestion de cache) pour l'utilisateur courant
	 *
	 * @return array Tableau d'objets de type Discounts
	 */
	function getRulesOnEachBook()
	{
		static $tblBuffer = array();
		if(is_array($tblBuffer) && count($tblBuffer) > 0) {

		} else {
			$critere = new CriteriaCompo();
			$critere->add(new Criteria('disc_on_what', DISCOUNT_ON3, '='));
			$tblGroups = $this->getCurrentMemberGroups();
			$critere->add(new Criteria('disc_group', '('.implode(',', $tblGroups).')', 'IN'));
			$tblBuffer = $this->getObjects($critere);
		}
		return $tblBuffer;
	}


	/**
	 * Renvoie la liste des règles à appliquer sur tous les livres (avec gestion de cache) pour l'utilisateur courant
	 *
	 * @return array Tableau d'objets de type Discounts
	 */
	function getRulesOnAllBooks()
	{
		static $tblBuffer = array();
		if(is_array($tblBuffer) && count($tblBuffer) > 0) {

		} else {
			$critere = new CriteriaCompo();
			$critere->add(new Criteria('disc_on_what', DISCOUNT_ON2, '='));
			$tblGroups = $this->getCurrentMemberGroups();
			$critere->add(new Criteria('disc_group', '('.implode(',', $tblGroups).')', 'IN'));
			$tblBuffer = $this->getObjects($critere);
		}
		return $tblBuffer;
	}


	/**
	 * Renvoie la liste des règles à appliquer sur les frais de ports (avec gestion de cache) pour l'utilisateur courant
	 *
	 * @return array Tableau d'objets de type Discounts
	 */
	function getRulesOnShipping()
	{
		static $tblBuffer = array();
		if(is_array($tblBuffer) && count($tblBuffer) > 0) {

		} else {
			$critere = new CriteriaCompo();
			$critere->add(new Criteria('disc_on_what', DISCOUNT_ON4, '='));
			$tblGroups = $this->getCurrentMemberGroups();
			$critere->add(new Criteria('disc_group', '('.implode(',', $tblGroups).')', 'IN'));
			$tblBuffer = $this->getObjects($critere);
		}
		return $tblBuffer;
	}


	/**
	 * Retourne la liste des règles à appliquer sur les frais de ports (avec gestion de cache) pour l'utilisateur courant
	 *
	 * @return array Tableau d'objets de type Discounts
	 */
	function getRulesOnShipping2()
	{
		static $tblBuffer = array();
		if(is_array($tblBuffer) && count($tblBuffer) > 0) {
			return $tblBuffer;
		} else {
			$critere = new CriteriaCompo();
			//$critere->add(new Criteria('disc_on_what', DISCOUNT_ON5, '='));
			$critere2 = new CriteriaCompo();
				$critere2->add(new Criteria('disc_shipping', DISCOUNT_SHIPPING2, '='));
				$critere2->add(new Criteria('disc_shipping', DISCOUNT_SHIPPING3, '='), 'OR');
			$critere->add($critere2);
			$tblGroups = $this->getCurrentMemberGroups();
			$critere->add(new Criteria('disc_group', '('.implode(',', $tblGroups).')', 'IN'));
			$tblBuffer = $this->getObjects($critere);
			return $tblBuffer;
		}		
	}


	/**
	 * Renvoie la liste des règles à appliquer sur l'intégralité de la commande (avec gestion de cache) pour l'utilisateur courant
	 *
	 * @return array Tableau d'objets de type Discounts
	 */
	function getRulesOnCommand()
	{
		static $tblBuffer = array();
		if(is_array($tblBuffer) && count($tblBuffer) > 0) {

		} else {
			$critere = new CriteriaCompo();
			$critere->add(new Criteria('disc_on_what', DISCOUNT_ON1, '='));
			$tblGroups = $this->getCurrentMemberGroups();
			$critere->add(new Criteria('disc_group', '('.implode(',', $tblGroups).')', 'IN'));
			$tblBuffer = $this->getObjects($critere);
		}
		return $tblBuffer;
	}


	/**
	 * Deuxième lot de réductions, à appliquer sur les frais de port
	 *
	 * @param float $montant Montant des frais de port
	 * @param array $discountsDescription Descriptions des réductions appliquées
	 * @param integer	$totalBooksQuantity	Le nombre total de livres
	 */
	function applyDiscountOnShipping2(&$montantShipping, &$commandAmount, &$discountsDescription, $totalBooksQuantity)
	{
		$tblRules = array();
		$tblRules = $this->getRulesOnShipping2();	// Renvoie des objets Discounts
		if( count($tblRules) > 0 ) {
			foreach($tblRules as $rule) {
				switch($rule->getVar('disc_shipping')) {
					case DISCOUNT_SHIPPING2:	// Frais de ports totalement gratuits
						if($commandAmount > floatval($rule->getVar('disc_if_amount'))) {
							$discountsDescription[] = $rule->getVar('disc_description');
							$montantShipping = 0;
						}
						break;
					case DISCOUNT_SHIPPING3:	// Les frais de ports sont de X euros pour le premier article puis de x euros pour les autres
						if($totalBooksQuantity > 1) {	// La règle n'est applicable que s'il y a plus d'un article
							$discountsDescription[] = $rule->getVar('disc_description');
							$montantShipping = floatval($rule->getVar('disc_shipping_amount')) + (floatval($rule->getVar('disc_shipping_amount_next')) * ($totalBooksQuantity-1));														 
						}
						break;	
				}
			}
		}
	}


	/**
	 * Réductions à appliquer sur le montant global de la commande
	 *
	 * @param float $montantHT Montant HT des livres
	 * @param array $discountsDescription Descriptions des réductions appliquées
	 */
	function applyDiscountOnCommand(&$montantHT, &$discountsDescription)
	{
		global $xoopsUser, $h_bookshop_commands;
		$tblRules = array();
		$tblRules = $this->getRulesOnCommand();	// Renvoie des objets Discounts
		if( count($tblRules) > 0 ) {
			$uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
			foreach($tblRules as $rule) {
				switch($rule->getVar('disc_when')) {
					case DISCOUNT_WHEN1:	// Dans tous les cas
						if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
							$montantHT = bookshop_getDiscountedPrice($montantHT, $rule->getVar('disc_amount'));
							if($montantHT < 0) {
								$montantHT = 0;
							}
						} else {	// Réduction de x euros
							$montantHT -= $rule->getVar('disc_amount');
							if($montantHT < 0 ) {
								$montantHT = 0;
							}
						}
						$discountsDescription[] = $rule->getVar('disc_description');
						break;

					case DISCOUNT_WHEN2:	// Si c'est le premier achat de l'utilisateur sur le site
						if($h_bookshop_commands->isFirstCommand($uid)) {
							if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
								$montantHT = bookshop_getDiscountedPrice($montantHT, $rule->getVar('disc_amount'));
								if($montantHT < 0) {
									$montantHT = 0;
								}
							} else {	// Réduction de x euros
								$montantHT -= $rule->getVar('disc_amount');
								if($montantHT < 0) {
									$montantHT = 0;
								}
							}
							$discountsDescription[] = $rule->getVar('disc_description');
						}
						break;
				}
			}
		}
	}

	/**
	 * Réductions à appliquer sur les frais de port
	 *
	 * @param float $montantHT Montant HT des livres
	 * @param array $discountsDescription Descriptions des réductions appliquées
 	 * @param integer $bookQty Quantité commandée du livre
	 */
	function applyDiscountOnShipping(&$montantHT, &$discountsDescription, $bookQty)
	{
		global $xoopsUser, $h_bookshop_commands;
		$tblRules = array();
		$tblRules = $this->getRulesOnShipping();	// Renvoie des objets Discounts
		if( count($tblRules) > 0 ) {
			$uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
			foreach($tblRules as $rule) {
				switch($rule->getVar('disc_when')) {
					case DISCOUNT_WHEN1:	// Dans tous les cas
						if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
							$montantHT = bookshop_getDiscountedPrice($montantHT, $rule->getVar('disc_amount'));
							if($montantHT < 0) {
								$montantHT = 0;
							}
						} else {	// Réduction de x euros
							$montantHT -= $rule->getVar('disc_amount');
							if($montantHT < 0) {
								$montantHT = 0;
							}
						}
						$discountsDescription[] = $rule->getVar('disc_description');
						break;

					case DISCOUNT_WHEN2:	// Si c'est le premier achat de l'utilisateur sur le site
						if($h_bookshop_commands->isFirstCommand($uid)) {
							if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
								$montantHT = bookshop_getDiscountedPrice($montantHT, $rule->getVar('disc_amount'));
								if($montantHT < 0) {
									$montantHT = 0;
								}
							} else {	// Réduction de x euros
								$montantHT -= $rule->getVar('disc_amount');
								if($montantHT < 0) {
									$montantHT = 0;
								}
							}
							$discountsDescription[] = $rule->getVar('disc_description');
						}
						break;

					case DISCOUNT_WHEN4:	// Si la quantité est =, >, >=, <, <= à ...
						$qtyDiscount = false;
						switch($rule->getVar('disc_qty_criteria')) {
							case 0:	// =
								if($bookQty == $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 1:	// >
								if($bookQty > $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 2:	// >=
								if($bookQty >= $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 3:	// <
								if($bookQty < $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 4:	// <=
								if($bookQty <= $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

						}
						if($qtyDiscount) {
							if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
								$prixHT = bookshop_getDiscountedPrice($prixHT, $rule->getVar('disc_amount'));
								if($prixHT < 0) {
									$prixHT = 0;
								}
							} else {	// Réduction de x euros
								$prixHT -= $rule->getVar('disc_amount');
								if($prixHT < 0) {
									$prixHT = 0;
								}
							}
							$discountsDescription[] = $rule->getVar('disc_description');
						}
						break;
				}
			}
		}
	}


	/**
	 * Réductions à appliquer sur le montant HT de TOUS les livres
	 *
	 * @param float $montantHT Montant HT des livres
	 * @param array $discountsDescription Descriptions des réductions appliquées
	 * @param integer $bookQty Quantité commandée du livre
	 */
	function applyDiscountOnAllBooks(&$montantHT, &$discountsDescription, $bookQty)
	{
		global $xoopsUser, $h_bookshop_commands;
		$tblRules = array();
		$tblRules = $this->getRulesOnAllBooks();	// Renvoie des objets Discounts
		if( count($tblRules) > 0 ) {
			$uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
			foreach($tblRules as $rule) {
				switch($rule->getVar('disc_when')) {
					case DISCOUNT_WHEN1:	// Dans tous les cas
						if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
							$montantHT = bookshop_getDiscountedPrice($montantHT, $rule->getVar('disc_amount'));
							if($montantHT < 0) {
								$montantHT = 0;
							}
						} else {	// Réduction de x euros
							$montantHT -= $rule->getVar('disc_amount');
							if($montantHT < 0) {
								$montantHT = 0;
							}
						}
						$discountsDescription[] = $rule->getVar('disc_description');
						break;

					case DISCOUNT_WHEN2:	// Si c'est le premier achat de l'utilisateur sur le site
						if($h_bookshop_commands->isFirstCommand($uid)) {
							if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
								$montantHT = bookshop_getDiscountedPrice($montantHT, $rule->getVar('disc_amount'));
								if($montantHT < 0) {
									$montantHT = 0;
								}
							} else {	// Réduction de x euros
								$montantHT -= $rule->getVar('disc_amount');
								if($montantHT < 0) {
									$montantHT = 0;
								}
							}
							$discountsDescription[] = $rule->getVar('disc_description');
						}
						break;

					case DISCOUNT_WHEN4:	// Si la quantité est =, >, >=, <, <= à ...
						$qtyDiscount = false;
						switch($rule->getVar('disc_qty_criteria')) {
							case 0:	// =
								if($bookQty == $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 1:	// >
								if($bookQty > $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 2:	// >=
								if($bookQty >= $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 3:	// <
								if($bookQty < $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 4:	// <=
								if($bookQty <= $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

						}
						if($qtyDiscount) {
							if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
								$prixHT = bookshop_getDiscountedPrice($prixHT, $rule->getVar('disc_amount'));
								if($prixHT < 0) {
									$prixHT = 0;
								}
							} else {	// Réduction de x euros
								$prixHT -= $rule->getVar('disc_amount');
								if($prixHT < 0) {
									$prixHT = 0;
								}
							}
							$discountsDescription[] = $rule->getVar('disc_description');
						}
						break;
				}
			}
		}
	}


	/**
	 * Recalcul du prix HT du livre en appliquant les réductions, s'il y a lieu
	 *
	 * @param integer $bookId Identifiant du livre
	 * @param float $prixHT Prix HT du livre
	 * @param array $discountsDescription Descriptions des réductions appliquées
	 * @param integer $bookQty Quantité commandée du livre
	 */
	function applyDiscountOnEachBook($bookId, &$prixHT, &$discountsDescription, $bookQty)
	{
		global $xoopsUser, $h_bookshop_commands;
		$tblRules = array();
		$tblRules = $this->getRulesOnEachBook();	// Renvoie des objets Discounts
		if( count($tblRules) > 0 ) {
			$uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
			foreach($tblRules as $rule) {
				switch($rule->getVar('disc_when')) {
					case DISCOUNT_WHEN1:	// Dans tous les cas
						if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
							$prixHT = bookshop_getDiscountedPrice($prixHT, $rule->getVar('disc_amount'));
						} else {	// Réduction de x euros
							$prixHT -= $rule->getVar('disc_amount');
						}
						if($prixHT < 0) {
							$prixHT = 0;
						}						
						$discountsDescription[] = $rule->getVar('disc_description');
						break;

					case DISCOUNT_WHEN2:	// Si c'est le premier achat de l'utilisateur sur le site
						if($h_bookshop_commands->isFirstCommand($uid)) {
							if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
								$prixHT = bookshop_getDiscountedPrice($prixHT, $rule->getVar('disc_amount'));
							} else {	// Réduction de x euros
								$prixHT -= $rule->getVar('disc_amount');
							}
							if($prixHT < 0) {
								$prixHT = 0;
							}							
							$discountsDescription[] = $rule->getVar('disc_description');
						}
						break;

					case DISCOUNT_WHEN3:	// Si le livre n'a jamais été acheté
						if(!$h_bookshop_commands->BookAlreadyBought($uid, $bookId)) {
							if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
								$prixHT = bookshop_getDiscountedPrice($prixHT, $rule->getVar('disc_amount'));
							} else {	// Réduction de x euros
								$prixHT -= $rule->getVar('disc_amount');
							}
							if($prixHT < 0) {
								$prixHT = 0;
							}							
							$discountsDescription[] = $rule->getVar('disc_description');
						}
						break;

						
					case DISCOUNT_WHEN4:	// Si la quantité est =, >, >=, <, <= à ...
						$qtyDiscount = false;
						switch($rule->getVar('disc_qty_criteria')) {
							case 0:	// =
								if($bookQty == $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 1:	// >
								if($bookQty > $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 2:	// >=
								if($bookQty >= $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 3:	// <
								if($bookQty < $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

							case 4:	// <=
								if($bookQty <= $rule->getVar('disc_qty_value')) {
									$qtyDiscount = true;
								}
								break;

						}
						if($qtyDiscount) {
							if($rule->getVar('disc_percent_monney') == DISCOUNT_TYPE1) {	// Réduction de x pourcent
								$prixHT = bookshop_getDiscountedPrice($prixHT, $rule->getVar('disc_amount'));
								if($prixHT < 0) {
									$prixHT = 0;
								}
							} else {	// Réduction de x euros
								$prixHT -= $rule->getVar('disc_amount');
								if($prixHT < 0) {
									$prixHT = 0;
								}
							}
							$discountsDescription[] = $rule->getVar('disc_description');
						}
						break;
				}
			}
		}
	}
}
?>