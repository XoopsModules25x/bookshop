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
define("_MI_BOOKSHOP_BNAME1","Recente boeken");
define("_MI_BOOKSHOP_BNAME2","Top Boeken");
define("_MI_BOOKSHOP_BNAME3","Categorieën");
define("_MI_BOOKSHOP_BNAME4","Meest Verkocht");
define("_MI_BOOKSHOP_BNAME5","Best beoordeelde boeken");
define("_MI_BOOKSHOP_BNAME6","Random Boek");
define("_MI_BOOKSHOP_BNAME7","Boeken op promotie");
define("_MI_BOOKSHOP_BNAME8","Cart");
define("_MI_BOOKSHOP_BNAME9","Last recommended books");

// Sub menu titles
define("_MI_BOOKSHOP_SMNAME1","Winkelwagentje");
define("_MI_BOOKSHOP_SMNAME2","Index");
define("_MI_BOOKSHOP_SMNAME3","Categorieën");
define("_MI_BOOKSHOP_SMNAME4","Categorieën map");
define("_MI_BOOKSHOP_SMNAME5","Wie is wie");
define("_MI_BOOKSHOP_SMNAME6","Alle boeken");
define("_MI_BOOKSHOP_SMNAME7","Zoek");
define("_MI_BOOKSHOP_SMNAME8","Verkoopvoorwaarden");
define("_MI_BOOKSHOP_SMNAME9","Recommended Books");

// Names of admin menu items
define("_MI_BOOKSHOP_ADMENU0","Taal");
define("_MI_BOOKSHOP_ADMENU1","BTW");
define("_MI_BOOKSHOP_ADMENU2","Categorieën");
define("_MI_BOOKSHOP_ADMENU3","Auteurs / Vertalers");
define("_MI_BOOKSHOP_ADMENU4","Boeken");
define("_MI_BOOKSHOP_ADMENU5","Bestellingen");
define("_MI_BOOKSHOP_ADMENU6","Kortingen");
define("_MI_BOOKSHOP_ADMENU7","Nieuwsbrief");
define("_MI_BOOKSHOP_ADMENU8", "Teksten");
define("_MI_BOOKSHOP_ADMENU9", "Lage voorraden");
define("_MI_BOOKSHOP_ADMENU10", "Dashboard");
define("_MI_BOOKSHOP_ADMENU11", "Email");

// Title of config items
define('_MI_BOOKSHOP_NEWLINKS', 'Selecteer het maximaal aantal nieuwe boeken op de toppagina');
define('_MI_BOOKSHOP_PERPAGE', 'Selecteer het maximum aantal boeken op iedere pagina');
define('_MI_BOOKSHOP_AUTOAPPROVE','Automatisch goedkeuren nieuwe titels zonder tussenkomst Admin?');

// Description of each config items
define('_MI_BOOKSHOP_NEWLINKSDSC', '');
define('_MI_BOOKSHOP_PERPAGEDSC', '');

// Text for notifications

define('_MI_BOOKSHOP_GLOBAL_NOTIFY', 'Global');
define('_MI_BOOKSHOP_GLOBAL_NOTIFYDSC', 'Globale lijst notificatie opties.');

define('_MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFY', 'Nieuwe categorie');
define('_MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Stel me op de hoogte als een nieuwe boekcategorie is aangemaakt.');
define('_MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Ontvang een notificatie als een nieuwe boekcategorie is aangemaakt.');
define('_MI_BOOKSHOP_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Nieuwe boekcategorie');

define('_MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFY', 'Nieuw boek');
define('_MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFYCAP', 'Stel me op de hoogte me als een nieuw boek is gepubliceerd.');
define('_MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFYDSC', 'Ontvang een notificatie als een nieuw boek is gepubliceerd.');
define('_MI_BOOKSHOP_GLOBAL_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Nieuw boek');

// Ajouts Hervé Thouzard, Instant Zero ********************************************************************************
define('_MI_BOOKSHOP_PAYPAL_EMAIL', "Paypal E-mail adres");
define('_MI_BOOKSHOP_PAYPAL_EMAILDSC', "Adres dat wordt gebruikt voor notificaties van betalingen en bestellingen");
define('_MI_BOOKSHOP_PAYPAL_TEST', "Gebruik Paypal zandloper?");
define("_MI_BOOKSHOP_FORM_OPTIONS","Formulier opties");
define("_MI_BOOKSHOP_FORM_OPTIONS_DESC","Selecteer de gewenste editor. Als u een 'simpele' installatie heeft (bijvoorbeeld als u alleen de xoops core editor class gebruikt die meegeleverd wordt in het standaardpakket), dan kunt u het beste kiezen voor DHTML en Compact");

define("_MI_BOOKSHOP_FORM_COMPACT","Compact");
define("_MI_BOOKSHOP_FORM_DHTML","DHTML");
define("_MI_BOOKSHOP_FORM_SPAW","Spaw Editor");
define("_MI_BOOKSHOP_FORM_HTMLAREA","HtmlArea Editor");
define("_MI_BOOKSHOP_FORM_FCK","FCK Editor");
define("_MI_BOOKSHOP_FORM_KOIVI","Koivi Editor");
define("_MI_BOOKSHOP_FORM_TINYEDITOR","TinyEditor");

define("_MI_BOOKSHOP_INFOTIPS","Lengte van de tooltips");
define("_MI_BOOKSHOP_INFOTIPS_DES","Als u deze optie gebruikt, dan zullen linken naar boeken de eerste (n) karakters van het boek bevatten. Als u de waarde op 0 zet, dan zullen de infotips leeg zijn");
define('_MI_BOOKSHOP_UPLOADFILESIZE', 'MAX bestandsgrootte Upload (KB) 1048576 = 1 Meg');

define('_MI_BOOKSBYTHISAUTHOR', 'Boeken door dezelfde auteur');

define('_MI_BOOKSHOP_PREVNEX_LINK','Laat de vorige en de volgende linken zien?');
define('_MI_BOOKSHOP_PREVNEX_LINK_DESC','Als deze optie op \'Ja\' is gezet, dan worden twee linken getoond onderaan ieder boek. Deze linken worden gebruikt om naar het vorige en het volgende boek te gaan, volgens volgorde van publicatiedatum');

define('_MI_BOOKSHOP_SUMMARY1_SHOW','Laat recente boeken zien in alle categorieën?');
define('_MI_BOOKSHOP_SUMMARY1_SHOW_DESC','Als u deze optie gebruikt, dan zal een samenvatting met linken zichtbaar zijn van alle recent gepubliceerde boeken onderaan ieder boek');

define('_MI_BOOKSHOP_SUMMARY2_SHOW','Laat recente boeken zien in de huidige categorie?');
define('_MI_BOOKSHOP_SUMMARY2_SHOW_DESC','Als u deze optie gebruikt, dan zal een samenvatting met linken zichtbaar zijn van alle recent gepubliceerde boeken onderaan ieder boek');

define('_MI_BOOKSHOP_OPT23',"[METAGEN] - Maximum aantal keywords die worden gegenereerd");
define('_MI_BOOKSHOP_OPT23_DSC',"Selecteer het maximum aantal keywords dat automatisch wordt gegenereerd.");

define('_MI_BOOKSHOP_OPT24',"[METAGEN] - Keywords volgorde");
define('_MI_BOOKSHOP_OPT241',"Crëeer ze in de volgorde zoals ze in de tekst staan");
define('_MI_BOOKSHOP_OPT242',"Volgorde van de woord-frequentie");
define('_MI_BOOKSHOP_OPT243',"Omgekeerde volgorde woord-frequentie");

define('_MI_BOOKSHOP_OPT25',"[METAGEN] - Blacklist");
define('_MI_BOOKSHOP_OPT25_DSC',"Voeg woorden toe (gescheiden door een komma) om te verwijderen uit de meta keywords");
define('_MI_BOOKSHOP_RATE','Mogelijk maken dat bezoekers boeken beoordelen?');

define("_MI_BOOKSHOP_ADVERTISEMENT","Advertentie");
define("_MI_BOOKSHOP_ADV_DESCR","Voeg een tekst of javascriptcode in dat bij uw boek wordt getoond");
define("_MI_BOOKSHOP_MIMETYPES","Voeg geldige Mime Types in voor uploads (gescheiden op een nieuwe regel)");
define('_MI_BOOKSHOP_STOCK_EMAIL', "E-mailadres dat gebruikt wordt als de voorraden te laag zijn");
define('_MI_BOOKSHOP_STOCK_EMAIL_DSC', "Vul hier niets in als u deze functie niet wilt gebruiken.");

define('_MI_BOOKSHOP_OPT7',"Gebruik RSS feeds?");
define('_MI_BOOKSHOP_OPT7_DSC',"De laatste boeken zijn beschikbaar via een RSS Feed");

define('_MI_BOOKSHOP_CHUNK1',"Meest recente boeken");
define('_MI_BOOKSHOP_CHUNK2',"Meest gekochte boeken");
define('_MI_BOOKSHOP_CHUNK3',"Meest bekeken boeken");
define('_MI_BOOKSHOP_CHUNK4',"Best beoordeelde boeken");
define('_MI_BOOKSHOP_ITEMSCNT',"Items aantal om te laten zien");
define('_MI_BOOKSHOP_PDF_CATALOG',"Sta het gebruik toe van de PDF-catalogus?");
define('_MI_BOOKSHOP_URL_REWR',"Gebruik Url-Rewriting?");

define('_MI_BOOKSHOP_MONEY_F',"Naam van de valuta");
define('_MI_BOOKSHOP_MONEY_S',"Symbool valuta");
define('_MI_BOOKSHOP_MONEY_P',"Voeg Paypal valutacode in");
define('_MI_BOOKSHOP_NO_MORE',"Laat de boeken zien, zelfs als de voorraad op is?");
define('_MI_BOOKSHOP_MSG_NOMORE',"Tekst die getoond wordt als er geen voorraad meer is");
define('_MI_BOOKSHOP_GRP_SOLD',"Groep naar wie een e-mail wordt verstuurd als een boek is verkocht?");
define('_MI_BOOKSHOP_GRP_QTY',"Gebruikersgroep die geautoriseerd is om de boekenvoorraad op de boekenpaginas aan te passen");
define('_MI_BOOKSHOP_BEST_TOGETHER',"Toon 'Better Together' ?");
define('_MI_BOOKSHOP_UNPUBLISHED',"Laat de publicatiedatum van een boek zien als die later dan vandaag is?");
define('_MI_BOOKSHOP_DECIMAL', "Aantal cijfers achter de komma");
define('_MI_BOOKSHOP_PDT', "Paypal - Payment Data Transfer Token (optie)");
define('_MI_BOOKSHOP_MANUAL_META', "Enter meta data manually ?");
?>