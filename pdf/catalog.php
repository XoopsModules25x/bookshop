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
 * Impression du catalogue au format PDF
 */

include('../../../mainfile.php');
include_once XOOPS_ROOT_PATH.'/modules/bookshop/include/common.php';
include_once XOOPS_ROOT_PATH.'/class/template.php';

if(bookshop_getmoduleoption('pdf_catalog') != 1) {
	die();
}

$details = isset($_POST['catalogFormat']) ? intval($_POST['catalogFormat']) : 0;
$Tpl = new XoopsTpl();
$tblVat = $tbl_categories  = array();
$tblVat = $h_bookshop_vat->GetAllVats();
$tbl_categories = $h_bookshop_cat->GetAllCategories();
$Tpl->assign('mod_pref', $mod_pref);	// Préférences du module

$cat_cid = 0 ;
$tbl_tmp = array();
$tblBooks = array();
$tblBooks = $h_bookshop_books->getRecentBooks(0, 0, $cat_cid);

if(count($tblBooks) > 0) {
	if (file_exists( BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/modinfo.php')) {
		include_once  BOOKSHOP_PATH.'language/'.$xoopsConfig['language'].'/modinfo.php';
	} else {
		include_once  BOOKSHOP_PATH.'language/english/modinfo.php';
	}
	$Tpl->assign('details', $details);
	$tblAuthors = $tbl_tmp = $tblAuthorsPerBook = array();
	$tblAuthors = $h_bookshop_booksauthors->getObjects(new Criteria('ba_book_id', '('.implode(',', array_keys($tblBooks)).')', 'IN'), true);
	foreach($tblAuthors as $item) {
		$tbl_tmp[] = $item->getVar('ba_auth_id');
		$tblAuthorsPerBook[$item->getVar('ba_book_id')][] = $item;
	}
	$tbl_tmp = array_unique($tbl_tmp);
	$tblAuthors = $h_bookshop_authors->getObjects(new Criteria('auth_id', '('.implode(',', $tbl_tmp).')', 'IN'), true);
	foreach($tblBooks as $item) {
		$tbl_tmp = array();
		$tbl_tmp = $item->toArray();
		$tbl_tmp['book_category'] = isset($tbl_categories[$item->getVar('book_cid')]) ? $tbl_categories[$item->getVar('book_cid')]->toArray() : null;
		$tbl_tmp['book_price_ttc'] = bookshop_getTTC($item->getVar('book_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate'));
		$tbl_tmp['book_discount_price_ttc'] = bookshop_getTTC($item->getVar('book_discount_price'), $tblVat[$item->getVar('book_vat_id')]->getVar('vat_rate') );
		$tbl_join = array();
		foreach($tblAuthorsPerBook[$item->getVar('book_id')] as $author) {	// Renvoie des objets de type booksauthors
				if($author->getVar('ba_type') == 1) {
					$auteur = $tblAuthors[$author->getVar('ba_auth_id')];
					$tbl_join[] = $auteur->getVar('auth_firstname').' '.$auteur->getVar('auth_name');
			}
		}
		if(count($tbl_join) > 0) {
			$tbl_tmp['book_joined_authors'] = implode(', ', $tbl_join);
		}
		$Tpl->append('books', $tbl_tmp);
	}
}

$content1 = utf8_encode($Tpl->fetch('db:bookshop_pdf_catalog.html'));
$content2 = utf8_encode($Tpl->fetch('db:bookshop_purchaseorder.html'));
//echo $content2; exit;

// ****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************
// ****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************
// ****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************

$doc_title = _BOOKSHOP_CATALOG;
$doc_subject = _BOOKSHOP_CATALOG;
$doc_keywords = "Instant Zero";

require_once BOOKSHOP_PATH.'pdf/config/lang/'._LANGCODE.'.php';
require BOOKSHOP_PATH.'pdf/tcpdf.php';


//create new PDF document (document units are set by default to millimeters)
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

$firstLine = utf8_encode(bookshop_get_module_name().' - '.$xoopsConfig['sitename']);
$secondLine = BOOKSHOP_URL.' - '.formatTimestamp(time(), 'm');
$pdf->SetHeaderData('', '', $firstLine, $secondLine);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->setLanguageArray($l); //set language items


//initialize document
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->writeHTML($content1, true, 0);
$pdf->AddPage();
$pdf->writeHTML($content2, true, 0);
$pdf->Output();
?>