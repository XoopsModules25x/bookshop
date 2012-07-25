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
	die('XOOPS root path not defined');
}

$modversion['name'] = _MI_BOOKSHOP_NAME;
$modversion['version'] = 1.5;
$modversion['description'] = _MI_BOOKSHOP_DESC;
$modversion['author'] = "Instant Zero (http://www.instant-zero.com), design XoopsDesign (http://www.xoopsdesign.com)";
$modversion['help'] = '';
$modversion['license'] = 'Commercial';
$modversion['official'] = 0;
$modversion['image'] = 'images/bookshop_logo.png';
$modversion['dirname'] = 'bookshop';

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][0] = 'bookshop_authors';
$modversion['tables'][1] = 'bookshop_books';
$modversion['tables'][2] = 'bookshop_booksauthors';
$modversion['tables'][3] = 'bookshop_caddy';
$modversion['tables'][4] = 'bookshop_cat';
$modversion['tables'][5] = 'bookshop_commands';
$modversion['tables'][6] = 'bookshop_related';
$modversion['tables'][7] = 'bookshop_vat';
$modversion['tables'][8] = 'bookshop_votedata';
$modversion['tables'][9] = 'bookshop_discounts';
$modversion['tables'][10] = 'bookshop_lang';

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Blocks
$cptb = 0;

$cptb++;
$modversion['blocks'][$cptb]['file'] = 'bookshop_new.php';
$modversion['blocks'][$cptb]['name'] = _MI_BOOKSHOP_BNAME1;
$modversion['blocks'][$cptb]['description'] = 'Shows recently added book titles';
$modversion['blocks'][$cptb]['show_func'] = 'b_bookshop_new_show';
$modversion['blocks'][$cptb]['edit_func'] = 'b_bookshop_new_edit';
$modversion['blocks'][$cptb]['options'] = '10|0';	// Voir 10 livres, pour toutes les catégories
$modversion['blocks'][$cptb]['template'] = 'bookshop_block_new.html';

$cptb++;
$modversion['blocks'][$cptb]['file'] = 'bookshop_top.php';
$modversion['blocks'][$cptb]['name'] = _MI_BOOKSHOP_BNAME2;
$modversion['blocks'][$cptb]['description'] = 'Shows most viewed book titles';
$modversion['blocks'][$cptb]['show_func'] = 'b_bookshop_top_show';
$modversion['blocks'][$cptb]['edit_func'] = 'b_bookshop_top_edit';
$modversion['blocks'][$cptb]['options'] = '10|0';
$modversion['blocks'][$cptb]['template'] = 'bookshop_block_top.html';

$cptb++;
$modversion['blocks'][$cptb]['file'] = 'bookshop_categories.php';
$modversion['blocks'][$cptb]['name'] = _MI_BOOKSHOP_BNAME3;
$modversion['blocks'][$cptb]['description'] = 'Show categories in relation with the category page';
$modversion['blocks'][$cptb]['show_func'] = 'b_bookshop_category_show';
$modversion['blocks'][$cptb]['edit_func'] = 'b_bookshop_category_edit';
$modversion['blocks'][$cptb]['options'] = '0';	// 0 = en relation avec la page, 1=classique
$modversion['blocks'][$cptb]['template'] = 'bookshop_block_categories.html';

$cptb++;
$modversion['blocks'][$cptb]['file'] = 'bookshop_best_sales.php';
$modversion['blocks'][$cptb]['name'] = _MI_BOOKSHOP_BNAME4;
$modversion['blocks'][$cptb]['description'] = 'Show most solded books';
$modversion['blocks'][$cptb]['show_func'] = 'b_bookshop_bestsales_show';
$modversion['blocks'][$cptb]['edit_func'] = 'b_bookshop_bestsales_edit';
$modversion['blocks'][$cptb]['options'] = '10|0';	// Voir 10 livres, pour toutes les catégories
$modversion['blocks'][$cptb]['template'] = 'bookshop_block_bestsales.html';

$cptb++;
$modversion['blocks'][$cptb]['file'] = 'bookshop_rated.php';
$modversion['blocks'][$cptb]['name'] = _MI_BOOKSHOP_BNAME5;
$modversion['blocks'][$cptb]['description'] = 'Shows best rated book';
$modversion['blocks'][$cptb]['show_func'] = 'b_bookshop_rated_show';
$modversion['blocks'][$cptb]['edit_func'] = 'b_bookshop_rated_edit';
$modversion['blocks'][$cptb]['options'] = '10|0';
$modversion['blocks'][$cptb]['template'] = 'bookshop_block_rated.html';

$cptb++;
$modversion['blocks'][$cptb]['file'] = 'bookshop_random.php';
$modversion['blocks'][$cptb]['name'] = _MI_BOOKSHOP_BNAME6;
$modversion['blocks'][$cptb]['description'] = 'Shows a random book';
$modversion['blocks'][$cptb]['show_func'] = 'b_bookshop_random_show';
$modversion['blocks'][$cptb]['edit_func'] = 'b_bookshop_random_edit';
$modversion['blocks'][$cptb]['options'] = '1|0';
$modversion['blocks'][$cptb]['template'] = 'bookshop_block_random.html';

$cptb++;
$modversion['blocks'][$cptb]['file'] = 'bookshop_promotion.php';
$modversion['blocks'][$cptb]['name'] = _MI_BOOKSHOP_BNAME7;
$modversion['blocks'][$cptb]['description'] = 'Shows books in promotion';
$modversion['blocks'][$cptb]['show_func'] = 'b_bookshop_promotion_show';
$modversion['blocks'][$cptb]['edit_func'] = 'b_bookshop_promotion_edit';
$modversion['blocks'][$cptb]['options'] = '10|0';
$modversion['blocks'][$cptb]['template'] = 'bookshop_block_promotion.html';

$cptb++;
$modversion['blocks'][$cptb]['file'] = 'bookshop_cart.php';
$modversion['blocks'][$cptb]['name'] = _MI_BOOKSHOP_BNAME8;
$modversion['blocks'][$cptb]['description'] = "Shows user's cart";
$modversion['blocks'][$cptb]['show_func'] = 'b_bookshop_cart_show';
$modversion['blocks'][$cptb]['edit_func'] = 'b_bookshop_cart_edit';
$modversion['blocks'][$cptb]['options'] = '4';	// Maximum count of items to show
$modversion['blocks'][$cptb]['template'] = 'bookshop_block_cart.html';

$cptb++;
$modversion['blocks'][$cptb]['file'] = 'bookshop_recommended.php';
$modversion['blocks'][$cptb]['name'] = _MI_BOOKSHOP_BNAME9;
$modversion['blocks'][$cptb]['description'] = "Shows last recommanded books";
$modversion['blocks'][$cptb]['show_func'] = 'b_bookshop_recomm_show';
$modversion['blocks'][$cptb]['edit_func'] = 'b_bookshop_recomm_edit';
$modversion['blocks'][$cptb]['options'] = '10|0';
$modversion['blocks'][$cptb]['template'] = 'bookshop_block_recommended.html';


// Menu
$modversion['hasMain'] = 1;
$cptm = 0;

$cptm++;
$modversion['sub'][$cptm]['name'] = _MI_BOOKSHOP_SMNAME1;
$modversion['sub'][$cptm]['url'] = 'caddy.php';
$cptm++;
$modversion['sub'][$cptm]['name'] = _MI_BOOKSHOP_SMNAME3;
$modversion['sub'][$cptm]['url'] = 'category.php';
$cptm++;
$modversion['sub'][$cptm]['name'] = _MI_BOOKSHOP_SMNAME4;
$modversion['sub'][$cptm]['url'] = 'categories-map.php';
$cptm++;
$modversion['sub'][$cptm]['name'] = _MI_BOOKSHOP_SMNAME5;
$modversion['sub'][$cptm]['url'] = 'whoswho.php';
$cptm++;
$modversion['sub'][$cptm]['name'] = _MI_BOOKSHOP_SMNAME6;
$modversion['sub'][$cptm]['url'] = 'all-books.php';
$cptm++;
$modversion['sub'][$cptm]['name'] = _MI_BOOKSHOP_SMNAME7;
$modversion['sub'][$cptm]['url'] = 'search.php';
$cptm++;
$modversion['sub'][$cptm]['name'] = _MI_BOOKSHOP_SMNAME9;
$modversion['sub'][$cptm]['url'] = 'recommended.php';

// Ajout des catégories mères en sous menu ********************************************************
global $xoopsModule;
if (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $modversion['dirname'] && $xoopsModule->getVar('isactive')) {
	if(!isset($h_bookshop_cat)) {
		$h_bookshop_cat = xoops_getmodulehandler('bookshop_cat', 'bookshop');
	}
	$tbl_categories = array();
	$criteria = new Criteria('cat_pid', 0, '=');
	$criteria->setSort('cat_title');
	$tbl_categories = $h_bookshop_cat->getObjects($criteria, true);
	foreach($tbl_categories as $item) {
		$cptm++;
		$modversion['sub'][$cptm]['name'] = $item->getVar('cat_title');
		$modversion['sub'][$cptm]['url'] = basename($h_bookshop_cat->GetCategoryLink($item->getVar('cat_cid'), $item->getVar('cat_title')));
	}
}

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'bookshop_search';

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'book_id';
$modversion['comments']['pageName'] = 'book.php';

// Comment callback functions
$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'bookshop_com_approve';
$modversion['comments']['callback']['update'] = 'bookshop_com_update';

// Templates
$modversion['templates'][1]['file'] = 'bookshop_chunk.html';
$modversion['templates'][1]['description'] = '';

$modversion['templates'][2]['file'] = 'bookshop_index.html';
$modversion['templates'][2]['description'] = '';

$modversion['templates'][3]['file'] = 'bookshop_category.html';
$modversion['templates'][3]['description'] = '';

$modversion['templates'][4]['file'] = 'bookshop_book.html';
$modversion['templates'][4]['description'] = '';

$modversion['templates'][5]['file'] = 'bookshop_bill.html';
$modversion['templates'][5]['description'] = '';

$modversion['templates'][6]['file'] = 'bookshop_caddy.html';
$modversion['templates'][6]['description'] = '';

$modversion['templates'][7]['file'] = 'bookshop_command.html';
$modversion['templates'][7]['description'] = '';

$modversion['templates'][8]['file'] = 'bookshop_thankyou.html';
$modversion['templates'][8]['description'] = '';

$modversion['templates'][9]['file'] = 'bookshop_cgv.html';
$modversion['templates'][9]['description'] = 'General Conditions Of Sale';

$modversion['templates'][10]['file'] = 'bookshop_search.html';
$modversion['templates'][10]['description'] = '';

$modversion['templates'][11]['file'] = 'bookshop_rss.html';
$modversion['templates'][11]['description'] = '';

$modversion['templates'][12]['file'] = 'bookshop_map.html';
$modversion['templates'][12]['description'] = '';

$modversion['templates'][13]['file'] = 'bookshop_whoswho.html';
$modversion['templates'][13]['description'] = '';

$modversion['templates'][14]['file'] = 'bookshop_allbooks.html';
$modversion['templates'][14]['description'] = '';

$modversion['templates'][15]['file'] = 'bookshop_author.html';
$modversion['templates'][15]['description'] = '';

$modversion['templates'][16]['file'] = 'bookshop_rate_book.html';
$modversion['templates'][16]['description'] = '';

$modversion['templates'][17]['file'] = 'bookshop_pdf_catalog.html';
$modversion['templates'][17]['description'] = '';

$modversion['templates'][18]['file'] = 'bookshop_purchaseorder.html';
$modversion['templates'][18]['description'] = '';

$modversion['templates'][19]['file'] = 'bookshop_cancelpurchase.html';
$modversion['templates'][19]['description'] = '';

$modversion['templates'][20]['file'] = 'bookshop_recommended.html';
$modversion['templates'][20]['description'] = 'Latest recommended books';

// ********************************************************************************************************************
// ****************************************** SETTINGS ****************************************************************
// ********************************************************************************************************************
$cpto = 0;

$cpto++;
$modversion['config'][$cpto]['name'] = 'newbooks';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_NEWLINKS';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_NEWLINKSDSC';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 10;

$cpto++;
$modversion['config'][$cpto]['name'] = 'perpage';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_PERPAGE';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_PERPAGEDSC';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 10;

/**
 * Email adress to use for Paypal
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'paypal_email';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_PAYPAL_EMAIL';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_PAYPAL_EMAILDSC';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '';

/**
 * Paypal money's code
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'paypal_money';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_MONEY_P';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = 'EUR';

/**
 * Are you in Paypal test mode ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'paypal_test';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_PAYPAL_TEST';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 1;

/**
 * Payment Data Transfer Token (optional)
 */
 /*
$cpto++;
$modversion['config'][$cpto]['name'] = 'paypal_pdt';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_PDT';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '';
*/

/**
 * Money, full label
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'money_full';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_MONEY_F';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = 'euro(s)';

/**
 * Money, short label
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'money_short';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_MONEY_S';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '€';


/**
 * Do you want to use URL rewriting ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'urlrewriting';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_URL_REWR';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;


/**
 * Editor to use
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'bl_form_options';
$modversion['config'][$cpto]['title'] = "_MI_BOOKSHOP_FORM_OPTIONS";
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_FORM_OPTIONS_DESC';
$modversion['config'][$cpto]['formtype'] = 'select';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['options'] = array(
											_MI_BOOKSHOP_FORM_DHTML=>'dhtml',
											_MI_BOOKSHOP_FORM_COMPACT=>'textarea',
											_MI_BOOKSHOP_FORM_SPAW=>'spaw',
											_MI_BOOKSHOP_FORM_HTMLAREA=>'htmlarea',
											_MI_BOOKSHOP_FORM_KOIVI=>'koivi',
											_MI_BOOKSHOP_FORM_FCK=>'fck',
											_MI_BOOKSHOP_FORM_TINYEDITOR=>'tinyeditor'
											);
$modversion['config'][$cpto]['default'] = 'dhtml';

/**
 * Tooltips, or infotips are some small textes you can see when you
 * move your mouse over an article's title. This text contains the
 * first (x) characters of the story
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'infotips';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_INFOTIPS';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_INFOTIPS_DES';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = '0';

/**
 * MAX Filesize Upload in kilo bytes
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'maxuploadsize';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_UPLOADFILESIZE';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 1048576;


/**
 * If you set this option to yes then you will see two links at the bottom
 * of each item. The first link will enable you to go to the previous
 * item and the other link will bring you to the next item
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'showprevnextlink';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_PREVNEX_LINK';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_PREVNEX_LINK_DESC';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;

/**
 * Display a summary table of the last published books (in all categories) ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'summarylast';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_SUMMARY1_SHOW';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_SUMMARY1_SHOW_DESC';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 10;

/**
 * Display a summary table of the last published books in the same category ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'summarycategory';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_SUMMARY2_SHOW';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_SUMMARY2_SHOW_DESC';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 10;


/**
 * Better Together ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'better_together';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_BEST_TOGETHER';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;

/**
 * Display unpublished books ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'show_unpublished';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_UNPUBLISHED';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;


/**
 * METAGEN, Max count of keywords to create
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'metagen_maxwords';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_OPT23';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_OPT23_DSC';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 40;

/**
 * METAGEN - Keywords order
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'metagen_order';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_OPT24';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'select';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 5;
$modversion['config'][$cpto]['options'] = array('_MI_BOOKSHOP_OPT241' => 0, '_MI_BOOKSHOP_OPT242' => 1, '_MI_BOOKSHOP_OPT243' => 2);

/**
 * METAGEN - Black list
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'metagen_blacklist';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_OPT25';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_OPT25_DSC';
$modversion['config'][$cpto]['formtype'] = 'textarea';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '';

/**
 * Do you want to enable your visitors to rate books ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'ratebooks';
$modversion['config'][$cpto]['title'] = "_MI_BOOKSHOP_RATE";
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;

/**
 * Global module's Advertisement
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'advertisement';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_ADVERTISEMENT';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_ADV_DESCR';
$modversion['config'][$cpto]['formtype'] = 'textarea';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '';


/**
 * Mime Types
 * Default values : Web pictures (png, gif, jpeg), zip, pdf, gtar, tar, pdf
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'mimetypes';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_MIMETYPES';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textarea';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = "image/gif\nimage/jpeg\nimage/pjpeg\nimage/x-png\nimage/png\napplication/x-zip-compressed\napplication/zip\napplication/pdf\napplication/x-gtar\napplication/x-tar";


/**
 * Group of users to wich send an email when a book's stock is low (if nothing is typed then there's no alert)
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'stock_alert_email';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_STOCK_EMAIL';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_STOCK_EMAIL_DSC';
$modversion['config'][$cpto]['formtype'] = 'group';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;


/**
 * Group of users to wich send an email when a book is sold
 */
$cpto++;
$modversion['config'][$cpto]['name']= 'grp_sold';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_GRP_SOLD';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'group';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;


/**
 * Group of users authorized to modify books quantities from the book page
 */
$cpto++;
$modversion['config'][$cpto]['name']= 'grp_qty';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_GRP_QTY';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'group';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;


/**
 * Display books when there are no more books ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'nostock_display';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_NO_MORE';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 1;

/**
 * Message to display where there's not more quantity for a book ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'nostock_msg';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_MSG_NOMORE';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textarea';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '';

/**
 * Use RSS Feeds ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'use_rss';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_OPT7';
$modversion['config'][$cpto]['description'] = '_MI_BOOKSHOP_OPT7_DSC';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 1;

/**
 * Enable PDF Catalog ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'pdf_catalog';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_PDF_CATALOG';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 1;

/**
 * Decimals count
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'decimals_count';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_DECIMAL';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = '2';

/**
 * Enter meta data manually ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'manual_meta';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_MANUAL_META';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;


// ************************************************************************************************
// ************************* Hidden settings ******************************************************
// ************************************************************************************************
$cpto++;
$modversion['config'][$cpto]['name'] = 'chunk1';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_CHUNK1';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'hidden';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 1;

$cpto++;
$modversion['config'][$cpto]['name'] = 'chunk2';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_CHUNK2';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'hidden';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 2;

$cpto++;
$modversion['config'][$cpto]['name'] = 'chunk3';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_CHUNK3';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'hidden';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 3;

$cpto++;
$modversion['config'][$cpto]['name'] = 'chunk4';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_CHUNK4';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'hidden';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 4;

/**
 * Count of items visible in the module's administration
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'items_count';
$modversion['config'][$cpto]['title'] = '_MI_BOOKSHOP_ITEMSCNT';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 15;


// ************************************************************************************************
// Notifications **********************************************************************************
// ************************************************************************************************
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'bookshop_notify_iteminfo';

$modversion['notification']['category'][1]['name'] = 'global';
$modversion['notification']['category'][1]['title'] = _MI_BOOKSHOP_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description'] = _MI_BOOKSHOP_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = array('index.php','category.php','book.php', 'categories-map.php', 'all-books.php');

$modversion['notification']['event'][1]['name'] = 'new_category';
$modversion['notification']['event'][1]['category'] = 'global';
$modversion['notification']['event'][1]['title'] = _MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFY;
$modversion['notification']['event'][1]['caption'] = _MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFYCAP;
$modversion['notification']['event'][1]['description'] = _MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'global_newcategory_notify';
$modversion['notification']['event'][1]['mail_subject'] = _MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFYSBJ;

$modversion['notification']['event'][2]['name'] = 'new_book';
$modversion['notification']['event'][2]['category'] = 'global';
$modversion['notification']['event'][2]['title'] = _MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFY;
$modversion['notification']['event'][2]['caption'] = _MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFYCAP;
$modversion['notification']['event'][2]['description'] = _MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'global_newbook_notify';
$modversion['notification']['event'][2]['mail_subject'] = _MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFYSBJ;
?>