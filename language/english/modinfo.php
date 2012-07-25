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

// The name of this module
define("_MI_BOOKSHOP_NAME","BookShop");

// A brief description of this module
define("_MI_BOOKSHOP_DESC","Creates an online bookshop to display and sell books.");

// Names of blocks for this module (Not all module has blocks)
define("_MI_BOOKSHOP_BNAME1","Recent Books");
define("_MI_BOOKSHOP_BNAME2","Top Books");
define("_MI_BOOKSHOP_BNAME3","Categories");
define("_MI_BOOKSHOP_BNAME4","Best Sellers");
define("_MI_BOOKSHOP_BNAME5","Best Rated Books");
define("_MI_BOOKSHOP_BNAME6","Random Book");
define("_MI_BOOKSHOP_BNAME7","Books on promotion");
define("_MI_BOOKSHOP_BNAME8","Cart");
define("_MI_BOOKSHOP_BNAME9","Last recommended books");

// Sub menu titles
define("_MI_BOOKSHOP_SMNAME1","Cart");
define("_MI_BOOKSHOP_SMNAME2","Index");
define("_MI_BOOKSHOP_SMNAME3","Categories");
define("_MI_BOOKSHOP_SMNAME4","Categories map");
define("_MI_BOOKSHOP_SMNAME5","Who's who");
define("_MI_BOOKSHOP_SMNAME6","All books");
define("_MI_BOOKSHOP_SMNAME7","Search");
define("_MI_BOOKSHOP_SMNAME8","General Conditions Of Sale");
define("_MI_BOOKSHOP_SMNAME9","Recommended Books");

// Names of admin menu items
define("_MI_BOOKSHOP_ADMENU0","Language");
define("_MI_BOOKSHOP_ADMENU1","VAT");
define("_MI_BOOKSHOP_ADMENU2","Categories");
define("_MI_BOOKSHOP_ADMENU3","Authors / Translators");
define("_MI_BOOKSHOP_ADMENU4","Books");
define("_MI_BOOKSHOP_ADMENU5","Orders");
define("_MI_BOOKSHOP_ADMENU6","Discounts");
define("_MI_BOOKSHOP_ADMENU7","Newsletter");
define("_MI_BOOKSHOP_ADMENU8", "Texts");
define("_MI_BOOKSHOP_ADMENU9", "Low stocks");
define("_MI_BOOKSHOP_ADMENU10", "Dashboard");
define("_MI_BOOKSHOP_ADMENU11", "Email");

// Title of config items
define('_MI_BOOKSHOP_NEWLINKS', 'Select the maximum number of new books displayed on top page');
define('_MI_BOOKSHOP_PERPAGE', 'Select the maximum number of books displayed in each page');
define('_MI_BOOKSHOP_AUTOAPPROVE','Auto approve new titles without admin intervention?');

// Description of each config items
define('_MI_BOOKSHOP_NEWLINKSDSC', '');
define('_MI_BOOKSHOP_PERPAGEDSC', '');

// Text for notifications

define('_MI_BOOKSHOP_GLOBAL_NOTIFY', 'Global');
define('_MI_BOOKSHOP_GLOBAL_NOTIFYDSC', 'Global lists notification options.');

define('_MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFY', 'New Category');
define('_MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notify me when a new book category is created.');
define('_MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Receive notification when a new book category is created.');
define('_MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New book category');

define('_MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFY', 'New Book');
define('_MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFYCAP', 'Notify me when any new book is posted.');
define('_MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFYDSC', 'Receive notification when any new book is posted.');
define('_MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New Book');

// Ajouts Hervé Thouzard, Instant Zero ********************************************************************************
define('_MI_BOOKSHOP_PAYPAL_EMAIL', "Paypal Email address");
define('_MI_BOOKSHOP_PAYPAL_EMAILDSC', "Address to use for payments and orders notifications");
define('_MI_BOOKSHOP_PAYPAL_TEST', "Use Paypal sandbox ?");
define("_MI_BOOKSHOP_FORM_OPTIONS","Form Option");
define("_MI_BOOKSHOP_FORM_OPTIONS_DESC","Select the editor to use. If you have a 'simple' install (e.g you use only xoops core editor class, provided in the standard xoops core package), then you can just select DHTML and Compact");

define("_MI_BOOKSHOP_FORM_COMPACT","Compact");
define("_MI_BOOKSHOP_FORM_DHTML","DHTML");
define("_MI_BOOKSHOP_FORM_SPAW","Spaw Editor");
define("_MI_BOOKSHOP_FORM_HTMLAREA","HtmlArea Editor");
define("_MI_BOOKSHOP_FORM_FCK","FCK Editor");
define("_MI_BOOKSHOP_FORM_KOIVI","Koivi Editor");
define("_MI_BOOKSHOP_FORM_TINYEDITOR","TinyEditor");

define("_MI_BOOKSHOP_INFOTIPS","Length of tooltips");
define("_MI_BOOKSHOP_INFOTIPS_DES","If you use this option, links related to books will contains the first (n) characters of the book. If you set this value to 0 then the infotips will be empty");
define('_MI_BOOKSHOP_UPLOADFILESIZE', 'MAX Filesize Upload (KB) 1048576 = 1 Meg');

define('_MI_BOOKSBYTHISAUTHOR', 'Books by the same author');

define('_MI_BOOKSHOP_PREVNEX_LINK','Show Previous and Next link ?');
define('_MI_BOOKSHOP_PREVNEX_LINK_DESC','When this option is set to \'Yes\', two new links are visibles at the bottom of each book. Those links are used to go to the previous and next book according to the publish date');

define('_MI_BOOKSHOP_SUMMARY1_SHOW','Show recent books in all categories?');
define('_MI_BOOKSHOP_SUMMARY1_SHOW_DESC','When you use this option, a summary containing links to all the recent published books is visible at the bottom of each book');

define('_MI_BOOKSHOP_SUMMARY2_SHOW','Show recent books in current category ?');
define('_MI_BOOKSHOP_SUMMARY2_SHOW_DESC','When you use this option, a summary containing links to all the recent published books is visible at the bottom of each book');

define('_MI_BOOKSHOP_OPT23',"[METAGEN] - Maximum count of keywords to generate");
define('_MI_BOOKSHOP_OPT23_DSC',"Select the maximum count of keywords to automatically generate.");

define('_MI_BOOKSHOP_OPT24',"[METAGEN] - Keywords order");
define('_MI_BOOKSHOP_OPT241',"Create them in the order they appear in the text");
define('_MI_BOOKSHOP_OPT242',"Order of word's frequency");
define('_MI_BOOKSHOP_OPT243',"Reverse order of word's frequency");

define('_MI_BOOKSHOP_OPT25',"[METAGEN] - Blacklist");
define('_MI_BOOKSHOP_OPT25_DSC',"Enter words (separated by a comma) to remove from meta keywords");
define('_MI_BOOKSHOP_RATE','Enable users to rate books ?');

define("_MI_BOOKSHOP_ADVERTISEMENT","Advertisement");
define("_MI_BOOKSHOP_ADV_DESCR","Enter a text or a javascript code to display in your books");
define("_MI_BOOKSHOP_MIMETYPES","Enter authorised Mime Types for upload (separated them on a new line)");
define('_MI_BOOKSHOP_STOCK_EMAIL', "Email address to use when stocks are low");
define('_MI_BOOKSHOP_STOCK_EMAIL_DSC', "Don't type anything if you don't want to use this function.");

define('_MI_BOOKSHOP_OPT7',"Use RSS feeds ?");
define('_MI_BOOKSHOP_OPT7_DSC',"The last books will be available via an RSS Feed");

define('_MI_BOOKSHOP_CHUNK1',"Span for most recent books");
define('_MI_BOOKSHOP_CHUNK2',"Span for most purchased books");
define('_MI_BOOKSHOP_CHUNK3',"Span for most viewed books");
define('_MI_BOOKSHOP_CHUNK4',"Span for best ranked books");
define('_MI_BOOKSHOP_ITEMSCNT',"Items count to display in the module's administration");
define('_MI_BOOKSHOP_PDF_CATALOG',"Allow the use of the PDF catalog ?");
define('_MI_BOOKSHOP_URL_REWR',"Use Url Rewriting ?");

define('_MI_BOOKSHOP_MONEY_F',"Name of currency");
define('_MI_BOOKSHOP_MONEY_S',"Symbol for currency");
define('_MI_BOOKSHOP_MONEY_P',"Enter Paypal currency code");
define('_MI_BOOKSHOP_NO_MORE',"Display books even when there is no stock available ?");
define('_MI_BOOKSHOP_MSG_NOMORE',"Text to display when there's no more stock for a book");
define('_MI_BOOKSHOP_GRP_SOLD',"Group to send an email when a book is sold ?");
define('_MI_BOOKSHOP_GRP_QTY',"Group of users authorized to modify books quantities from the book page");
define('_MI_BOOKSHOP_BEST_TOGETHER',"Display 'Better Together' ?");
define('_MI_BOOKSHOP_UNPUBLISHED',"Display book's publication date if later than today ?");
define('_MI_BOOKSHOP_DECIMAL', "Decimal point for money");
define('_MI_BOOKSHOP_PDT', "Paypal - Payment Data Transfer Token (optional)");
define('_MI_BOOKSHOP_MANUAL_META', "Enter meta data manually ?");
?>