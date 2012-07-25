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
	die("XOOPS root path not defined");
}

define("CADDY_NAME", 'bookshop_caddie');

include_once XOOPS_ROOT_PATH.'/class/xoopsobject.php';
if (!class_exists('Bookshop_XoopsPersistableObjectHandler')) {
	include_once XOOPS_ROOT_PATH.'/modules/bookshop/class/PersistableObjectHandler.php';
}

class bookshop_caddy extends Bookshop_Object
{
	function bookshop_caddy()
	{
		$this->initVar('caddy_id',XOBJ_DTYPE_INT,null,false);
		$this->initVar('caddy_book_id',XOBJ_DTYPE_INT,null,false);
		$this->initVar('caddy_qte',XOBJ_DTYPE_INT,null,false);
		$this->initVar('caddy_price',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('caddy_cmd_id',XOBJ_DTYPE_INT,null,false);
		$this->initVar('caddy_shipping',XOBJ_DTYPE_TXTBOX,null,false);
	}
}


class BookshopBookshop_caddyHandler extends Bookshop_XoopsPersistableObjectHandler
{
	function BookshopBookshop_caddyHandler($db)
	{	//											Table				Classe		 	Id
		$this->BookXoopsPersistableObjectHandler($db, 'bookshop_caddy', 'bookshop_caddy', 'caddy_id');
	}

	/**
	 * Renvoie, si on en trouve un, un livre qui s'est bien vendu avec un livre particulier
	 *
	 * @param integer $caddy_book_id Identifiant du livre dont on recherche le jumeau
	 * @return integer Le n° du livre le plus vendu avec le livre en question
	 */
	function getBestWith($caddy_book_id)
	{
		$sql = 'SELECT caddy_book_id, sum(caddy_qte) mv FROM '.$this->table.' WHERE caddy_cmd_id IN (SELECT caddy_cmd_id FROM '.$this->table.' WHERE caddy_book_id='.intval($caddy_book_id).') GROUP BY caddy_book_id ORDER BY mv DESC';
		$result = $this->db->query($sql, 1);
        if (!$result) {
            return 0;
        }
        $myrow = $this->db->fetchArray($result);
        return $myrow['caddy_book_id'];
	}

	/**
	 * Renvoie la liste des livres les plus vendus dans une catégorie particulière
	 *
	 * @param integer $book_cid Catégorie des livres
	 * @param integer $start Début de la recherche
	 * @param integer $limit Nombre maximum d'enregistrements à retourner
	 * @param boolean $withQuantity Faut il renvoyer les quantités vendues ?
	 * @return array Les identifiants des X livres les plus vendus dans cette catégorie
	 */
	function getMostSoldBooksInCategory($book_cid, $start=0, $limit=0, $withQuantity=false)
	{
		$ret = array();
		if(!is_array($book_cid)) {
			if( $book_cid > 0 ) {
				$sql = 'SELECT c.caddy_book_id, sum( c.caddy_qte ) AS mv FROM '.$this->table.' c, '.$this->db->prefix('bookshop_books').' b WHERE (c.caddy_book_id = b.book_id) AND b.book_cid = '.intval($book_cid).' GROUP BY c.caddy_book_id ORDER BY mv DESC';
			} else {
				$sql = 'SELECT c.caddy_book_id, sum( c.caddy_qte ) AS mv FROM '.$this->table.' c, '.$this->db->prefix('bookshop_books').' b WHERE (c.caddy_book_id = b.book_id) GROUP BY c.caddy_book_id ORDER BY mv DESC';
			}
		} else {
			$sql = 'SELECT c.caddy_book_id, sum( c.caddy_qte ) AS mv FROM '.$this->table.' c, '.$this->db->prefix('bookshop_books').' b WHERE (c.caddy_book_id = b.book_id) AND b.book_cid IN ('.implode(',', $book_cid).') GROUP BY c.caddy_book_id ORDER BY mv DESC';
		}
        $result = $this->db->query($sql, $limit, $start);
        if ($result) {
			while ($myrow = $this->db->fetchArray($result)) {
				if(!$withQuantity) {
					$ret[] = $myrow['caddy_book_id'];
				} else {
					$ret[$myrow['caddy_book_id']] = $myrow['mv'];
				}
			}
        }
        return $ret;
	}

	/**
	 * Renvoie la liste des livres les plus vendus toutes catégories confondues
	 *
	 * @param integer $start Début de la recherche
	 * @param integer $limit Nombre maximum d'enregistrements à retourner
	 * @return array Les identifiants des X livres les plus vendus dans cette catégorie
	 */
	function getMostSoldBooks($start=0, $limit=0)
	{
		$ret = array();
		$sql = 'SELECT caddy_book_id, sum( caddy_qte ) as mv FROM '.$this->table.' GROUP BY caddy_book_id ORDER BY mv DESC';
        $result = $this->db->query($sql, $limit, $start);
        if ($result) {
			while ($myrow = $this->db->fetchArray($result)) {
				$ret[] = $myrow['caddy_book_id'];
			}
        }
        return $ret;
	}

	/**
	 * Calcul du caddy à partir du tableau en session qui se présente sous la forme :
	 * 	$datas['number'] = indice du produit
	 * 	$datas['id'] = Identifiant du livre
	 * 	$datas['qty'] = Quantité voulue
	 *
	 * @param array $cartForTemplate Contenu du caddy à passer au template (en fait la liste des produits)
	 * @param boolean emptyCart Indique si le panier est vide ou pas
	 * @param float $shippingAmount Montant des frais de port
	 * @param float $commandAmount Montant HT de la commande
	 * @param float $vatAmount Montant de la TVA
	 * @param string $goOn Adresse vers laquelle renvoyer le visiteur après qu'il ait ajouté un produit dans son panier (cela correspond en fait à la catégorie du dernier livre ajouté dans le panier)
	 * @param float $commandAmountTTC Montant TTC de la commande
	 * @param array $discountsDescription Descriptions des remises appliquées
	 */
	function computeCart(&$cartForTemplate, &$emptyCart, &$shippingAmount, &$commandAmount, &$vatAmount, &$goOn, &$commandAmountTTC, &$discountsDescription)
	{
		global $h_bookshop_authors, $h_bookshop_books, $h_bookshop_booksauthors, $h_bookshop_cat, $h_bookshop_vat, $h_bookshop_discounts;
		if($this->isCartEmpty()) {	// Pas de caddie
			$emptyCart = true;
		} else {
			$emptyCart = false;
			$tblCaddie = array();
			$tblCaddie = isset($_SESSION[CADDY_NAME]) ? $_SESSION[CADDY_NAME] : array();
			$caddyCount = count($tblCaddie);
			if( $caddyCount > 0 ) {
				$cpt = 0;
				$fraisPort = 0;
				$totalBooksQuantity = 0;
				$tblVat = array();
				if(!is_object($h_bookshop_vat)) {
					$h_bookshop_vat = xoops_getmodulehandler('bookshop_vat', BOOKSHOP_DIRNAME);
				}
				if(!is_object($h_bookshop_books)) {
					$h_bookshop_books = xoops_getmodulehandler('bookshop_books', BOOKSHOP_DIRNAME);
				}
				if(!is_object($h_bookshop_cat)) {
					$h_bookshop_cat = xoops_getmodulehandler('bookshop_cat', BOOKSHOP_DIRNAME);
				}
				if(!is_object($h_bookshop_booksauthors)) {
					$h_bookshop_booksauthors = xoops_getmodulehandler('bookshop_booksauthors', BOOKSHOP_DIRNAME);
				}
				if(!is_object($h_bookshop_authors)) {
					$h_bookshop_authors = xoops_getmodulehandler('bookshop_authors', BOOKSHOP_DIRNAME);
				}
				if(!is_object($h_bookshop_discounts)) {
					$h_bookshop_discounts =  xoops_getmodulehandler('bookshop_discounts', BOOKSHOP_DIRNAME);
				}
				$tblVat = $h_bookshop_vat->GetAllVats();
				foreach($tblCaddie as $produit) {
					$datas = array();
					$book_id = $produit['id'];
					$book_number = $produit['number'];
					$book_qte = $produit['qty'];
					$book = null;
					$book = $h_bookshop_books->get($book_id);
					$totalBooksQuantity += $produit['qty'];
					if(!is_object($book)) {
						exit(_BOOKSHOP_ERROR9);
					}
					$cpt++;
					if(($cpt == $caddyCount)) {	// On arrive sur le dernier livre
						$category = null;
						$category = $h_bookshop_cat->get($book->getVar('book_cid'));
						if(is_object($category)) {
							$goOn = $h_bookshop_cat->GetCategoryLink($category->getVar('cat_cid'), $category->getVar('cat_title'));
						}
					}
					$datas = $book->toArray();
					// Recherche des auteurs
					$tblTmp = $tblTmp2 = $tblAuteurs = $tblJoin = array();
					$tblTmp = $h_bookshop_booksauthors->getObjects(new Criteria('ba_book_id', $book_id, '='), true);
					foreach($tblTmp as $item) {
						if($item->getVar('ba_type') == 1) {
							$tblTmp2[] = $item->getVar('ba_auth_id');	// Que les auteurs
						}
					}
					$tblAuteurs = $h_bookshop_authors->getObjects(new Criteria('auth_id', '('.implode(',', $tblTmp2).')', 'IN'), true);
					foreach($tblAuteurs as $item) {
						$tblJoin[] = $item->getVar('auth_firstname').' '.$item->getVar('auth_name');
					}
					if(count($tblJoin) > 0) {
						$datas['book_joined_authors'] = implode(', ', $tblJoin);
					}
					$datas['book_number'] = $book_number;

					// Calculs "financiers" ***************************************************************************
					if($book->getVar('book_discount_price') > 0) {
						$ht = floatval($book->getVar('book_discount_price'));
					} else {
						$ht = floatval($book->getVar('book_price'));
					}
					$htDiscounted = $ht;
					$fraisPortLivre = floatval($book->getVar('book_shipping_price')); 
					$h_bookshop_discounts->applyDiscountOnEachBook($book_id, $htDiscounted, $discountsDescription, $book_qte, $fraisPortLivre);
					$prixReelHT = $htDiscounted * $book_qte;
					$vatRate = $tblVat[$book->getVar('book_vat_id')]->getVar('vat_rate');
					$montantTVA = bookshop_getVAT($prixReelHT, $vatRate);
					$fraisPort =  $fraisPortLivre * $book_qte;
					$totalTTC = $prixReelHT + $montantTVA + $fraisPort;

					$datas['book_price_normal_ht'] = sprintf("%0.".bookshop_getmoduleoption('decimals_count').'f', $ht);
					$datas['book_price_discounted_ht'] = sprintf("%0.".bookshop_getmoduleoption('decimals_count').'f', $htDiscounted);
					$datas['book_qty'] = $book_qte;
					$datas['book_price_ht'] = sprintf("%0.".bookshop_getmoduleoption('decimals_count').'f', $prixReelHT);
					$datas['book_vat_rate'] = sprintf("%0.".bookshop_getmoduleoption('decimals_count').'f', $vatRate);
					$datas['book_vat_amount'] = sprintf("%0.".bookshop_getmoduleoption('decimals_count').'f', $montantTVA);
					$datas['book_shipping_amount'] = sprintf("%0.".bookshop_getmoduleoption('decimals_count').'f', $fraisPort);
					$datas['book_price_ttc'] = sprintf("%0.".bookshop_getmoduleoption('decimals_count').'f', $totalTTC);
					$cartForTemplate[] = $datas;

					// Les cumuls (dans les variables "globales")
					$shippingAmount += $fraisPort;
					$commandAmount += $prixReelHT;
					$vatAmount += $montantTVA;
				}
				// Règles sur TOUS les livres
				$h_bookshop_discounts->applyDiscountOnAllBooks($commandAmount, $discountsDescription, $totalBooksQuantity);

				// Règles sur les frais de port
				$h_bookshop_discounts->applyDiscountOnShipping($shippingAmount, $discountsDescription, $totalBooksQuantity);

				// Règles (n°2) sur les frais de port
				$h_bookshop_discounts->applyDiscountOnShipping2($shippingAmount, $commandAmount, $discountsDescription, $totalBooksQuantity);

				// Règles sur le montant global de la commande
				$commandAmountTTC = $shippingAmount + $commandAmount + $vatAmount;
				$h_bookshop_discounts->applyDiscountOnCommand($commandAmountTTC, $discountsDescription);
			}
		}
	}


	/**
	 * Indique si le caddy est vide ou pas
	 *
	 * @return boolean vide, ou pas...
	 */
	function isCartEmpty()
	{
		if(isset($_SESSION[CADDY_NAME])) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Vidage du caddy, s'il existe
	 */
	function emptyCart()
	{
		if(isset($_SESSION[CADDY_NAME])) {
			unset($_SESSION[CADDY_NAME]);
		}
	}

	/**
	 * Ajout d'un produit au caddy
	 *
	 * @param integer $book_id Identifiant du livre
	 * @param integer $quantity Quantité à ajouter
	 */
	function addProduct($book_id, $quantity)
	{
		$tbl_caddie = $tbl_caddie2 = array();
		if(isset($_SESSION[CADDY_NAME])) {
			$tbl_caddie = $_SESSION[CADDY_NAME];
		}
		$exists = false;
		foreach($tbl_caddie as $produit) {
			if($produit['id'] == $book_id) {
				$exists = true;
				$produit['qty'] += $quantity;
			}
			$tbl_caddie2[] = $produit;
		}
		if(!$exists) {
			$datas = array();
			$datas['number'] = count($tbl_caddie)+1;
			$datas['id'] = $book_id;
			$datas['qty'] = $quantity;
			$tbl_caddie[] = $datas;
			$_SESSION[CADDY_NAME] = $tbl_caddie;
		} else {
			$_SESSION[CADDY_NAME] = $tbl_caddie2;
		}
	}

	/**
	 * Suppression d'un produit du caddy
	 *
	 * @param integer $indice Indice de l'élément à supprimer
	 */
	function deleteProduct($indice)
	{
		$tbl_caddie = array();
		if(isset($_SESSION[CADDY_NAME])) {
			$tbl_caddie = $_SESSION[CADDY_NAME];
			if(isset($tbl_caddie[$indice])) {
				unset($tbl_caddie[$indice]);
				if(count($tbl_caddie) > 0) {
					$_SESSION[CADDY_NAME] = $tbl_caddie;
				} else {
					unset($_SESSION[CADDY_NAME]);
				}
			}
		}
	}

	/**
	 * Mise à jour des quantités du caddy suite à la validation du formulaire du caddy
	 */
	function updateQuantites()
	{
		global $h_bookshop_books;
		$tbl_caddie = $tbl_caddie2 = array();
		if(isset($_SESSION[CADDY_NAME])) {
			$tbl_caddie = $_SESSION[CADDY_NAME];
			foreach($tbl_caddie as $produit) {
				$number = $produit['number'];
				$name = 'qty_'.$number;
				if(isset($_POST[$name])) {
					$valeur = intval($_POST[$name]);
					if($valeur > 0) {
						$book_id = $produit['id'];
						$book = null;
						$book = $h_bookshop_books->get($book_id);
						if(is_object($book)) {
							if($book->getVar('book_stock') - $valeur > 0) {
								$produit['qty'] = $valeur;
								$tbl_caddie2[] = $produit;
							} else {
								$produit['qty'] = $book->getVar('book_stock');
								$tbl_caddie2[] = $produit;
							}
						}
					}
				}
			}
			if(count($tbl_caddie2) > 0 ) {
				$_SESSION[CADDY_NAME] = $tbl_caddie2;
			} else {
				unset($_SESSION[CADDY_NAME]);
			}
		}
	}

	/**
	 * Renvoie les éléments constituants une commande
	 *
	 * @param integer $caddy_cmd_id Identifiant de la commande
	 * @return array Tableau d'objets caddy
	 */
	function getCaddyFromCommand($caddy_cmd_id)
	{
		$ret = array();
		$critere = new Criteria('caddy_cmd_id', $caddy_cmd_id, '=');
		$ret = $this->getObjects($critere);
		return $ret;
	}

	/**
	 * Renvoie les ID de commandes pour un livre acheté
	 *
	 * @param integer $book_id Identifiant du livre
	 * @return array Les ID des commandes dans lesquelles ce livre a été commandé
	 */
	function getCommandIdFromBook($book_id)
	{
		$ret = array();
		$sql = 'SELECT caddy_cmd_id FROM '.$this->table.' WHERE caddy_book_id='.intval($book_id);
		$result = $this->db->query($sql);
        if (!$result) {
            return $ret;
        }
        while($myrow = $this->db->fetchArray($result)) {
        	$ret[] = $myrow['caddy_cmd_id'];
        }
        return $ret;
	}
}
?>