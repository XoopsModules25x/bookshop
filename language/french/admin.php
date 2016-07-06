<?php
//  ------------------------------------------------------------------------ //
//                      BOOKSHOP - MODULE FOR XOOPS 2                        //
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

define('_AM_BOOKSHOP_GO_TO_MODULE', 'Aller au module');
define('_AM_BOOKSHOP_PREFERENCES', 'Préférences');
define('_AM_BOOKSHOP_ADMINISTRATION', 'Administration');
define('_AM_BOOKSHOP_CATEGORIES', 'Catégories');
define('_AM_BOOKSHOP_CATEG_CONFIG', 'Configuration des blocs sur les pages des catégories');
define('_AM_BOOKSHOP_CHUNK', 'Bloc');
define('_AM_BOOKSHOP_POSITION', 'Position & visibilité');
define('_AM_BOOKSHOP_INVISIBLE', 'Invisible');
define('_AM_BOOKSHOP_OK', 'Ok');
define('_AM_BOOKSHOP_SAVE_OK', 'Données enregistrées avec succès');
define('_AM_BOOKSHOP_SAVE_PB', 'Problème durant la sauvegarde des données');
define('_AM_BOOKSHOP_ACTION', 'Action');
define('_AM_BOOKSHOP_ADD_ITEM', 'Ajouter un élément');
define('_AM_BOOKSHOP_CONF_DELITEM', 'Voulez vous vraiment supprimer cet élément ?');
define('_AM_BOOKSHOP_LIST', 'Liste');
define('_AM_BOOKSHOP_ID', 'Id');
define('_AM_BOOKSHOP_RATE', 'Taux');

define('_AM_BOOKSHOP_ADD_VAT', 'Ajouter une TVA');
define('_AM_BOOKSHOP_EDIT_VAT', 'Editer une TVA');

define('_AM_BOOKSHOP_ADD_CATEG', 'Ajouter une catégorie');
define('_AM_BOOKSHOP_EDIT_CATEG', 'Editer une catégorie');

define('_AM_BOOKSHOP_ADD_AUTH', 'Ajouter un auteur / traducteur');
define('_AM_BOOKSHOP_EDIT_AUTH', 'Editer un auteur / traducteur');

define('_AM_BOOKSHOP_ADD_BOOK', 'Ajouter un livre (tous les champs ne sont pas obligatoires)');
define('_AM_BOOKSHOP_EDIT_BOOK', 'Editer un livre (tous les champs ne sont pas obligatoires)');

define('_AM_BOOKSHOP_ADD_LANG', 'Ajouter une langue');
define('_AM_BOOKSHOP_EDIT_LANG', 'Editer une langue');

define('_AM_BOOKSHOP_ADD_DSICOUNT', 'Ajouter une promotion');
define('_AM_BOOKSHOP_EDIT_DISCOUNT', 'Editer une promotion');

define('_AM_BOOKSHOP_ERROR_1', "Erreur, pas d'identifiant spécifié");
define('_AM_BOOKSHOP_ERROR_2', 'Erreur, impossible de supprimer cette TVA, elle est utilisée par des livres');
define('_AM_BOOKSHOP_ERROR_3', 'Erreur pendant le téléchargement du fichier ');
define('_AM_BOOKSHOP_ERROR_4', 'Erreur, impossible de supprimer cette catégorie, elle est utilisée par des livres');
define('_AM_BOOKSHOP_ERROR_5', 'Erreur, impossible de supprimer cet auteur / tradcuteur,  il est utilisée par des livres');
define('_AM_BOOKSHOP_ERROR_6', 'Erreur, impossible de supprimer cette langue, elle est utilisée par un ou plusieurs livres');
define('_AM_BOOKSHOP_ERROR_7', "Erreur, impossible de créer le fichier d'export");
define('_AM_BOOKSHOP_ERROR_8', 'Erreur, veuillez créer une catégorie avant de créer un livre');
define('_AM_BOOKSHOP_ERROR_9', 'Erreur, veuillez créer une TVA avant de créer un livre');
define('_AM_BOOKSHOP_ERROR_10', 'Erreur, catégorie inconnue');
define('_AM_BOOKSHOP_ERROR_11', 'Pas de TVA !');
define('_AM_BOOKSHOP_NOT_FOUND', 'Erreur, élément introuvable');
define('_AM_BOOKSHOP_CONF_DEL_CATEG', 'Voulez-vous vraiment supprimer cette catégorie et ses sous-catégories ?<br>%s');

define('_AM_BOOKSHOP_MODIFY', 'Modifier');
define('_AM_BOOKSHOP_ADD', 'Ajouter');

define('_AM_BOOKSHOP_PARENT_CATEG', 'Catégorie mère');
define('_AM_BOOKSHOP_CURRENT_PICTURE', 'Image courante');
define('_AM_BOOKSHOP_PICTURE', 'Image');
define('_AM_BOOKSHOP_DESCRIPTION', 'Description');

define('_AM_BOOKSHOP_ALL', 'Tous');
define('_AM_BOOKSHOP_AUTHORS', 'Auteurs');
define('_AM_BOOKSHOP_TRANSLATORS', 'Traducteurs');
define('_AM_BOOKSHOP_LIMIT_TO', 'Filtre');
define('_AM_BOOKSHOP_FILTER', 'Filtrer');
define('_AM_BOOKSHOP_INDEX_PAGE', "Page d'index");
define('_AM_BOOKSHOP_RELATED_HELP', "Attention, à ne saisir qu'après avoir saisi tous les livres");
define('_AM_BOOKSHOP_SUBDATE_HELP', 'Entrer la date au format AAAA-MM-JJ');
define('_AM_BOOKSHOP_IMAGE1_HELP', 'Image courante de la couverture');
define('_AM_BOOKSHOP_IMAGE2_HELP', 'Image courante de la miniature');
define('_AM_BOOKSHOP_IMAGE1_CHANGE', "Modifier l'image de la couverture");
define('_AM_BOOKSHOP_IMAGE2_CHANGE', "Modifier l'image de la miniature");
define('_AM_BOOKSHOP_ATTACHED_HLP', "Par exemple le sommaire ou un extrait d'ouvrage en PDF");
define('_AM_BOOKSHOP_CATEG_HLP', 'Catégorie du livre');
define('_AM_BOOKSHOP_CATEG_TITLE', 'Titre de la catégorie');
define('_AM_BOOKSHOP_FORMAT_HLP', 'Format du livre (optionnel)');
define('_AM_BOOKSHOP_URL_HLP', 'Adresse internet externe du livre (optionnelle)');
define('_AM_BOOKSHOP_SELECT_HLP', 'Utilisez la touche Ctrl (ou la touche pomme sur Mac) pour choisir plusieurs éléments');
define('_AM_BOOKSHOP_STOCK_HLP', "Envoi d'un email si le stock atteint le nombre de ...");
define('_AM_BOOKSHOP_DISCOUNT_HLP', 'Prix promotionel (temporaire) TTC');
define('_AM_BOOKSHOP_DISCOUNT_DESCR', 'Description de la réduction (pour votre client)');
define('_AM_BOOKSHOP_DATE', 'Date');
define('_AM_BOOKSHOP_CLIENT', 'Client');
define('_AM_BOOKSHOP_TOTAL_SHIPP', 'Total / Frais de ports');
define('_AM_BOOKSHOP_NEWSLETTER_BETWEEN', 'S&eacute;lectionner les livres publi&eacute;s entre le');
define('_AM_BOOKSHOP_EXPORT_AND', ' et ');
define('_AM_BOOKSHOP_IN_CATEGORY', 'Dans les catégories suivantes');
define('_AM_BOOKSHOP_REMOVE_BR', 'Convertir les balises html &lt;br /&gt; en un retour à la ligne ?');
define('_AM_BOOKSHOP_NEWSLETTER_HTML_TAGS', 'Supprimer les balises html ?');
define('_AM_BOOKSHOP_NEWSLETTER_HEADER', 'Entête');
define('_AM_BOOKSHOP_NEWSLETTER_FOOTER', 'Pied de page');
define('_AM_BOOKSHOP_CSV_EXPORT', 'Export au format CSV');
define('_AM_BOOKSHOP_CSV_READY', "Votre fichier CSV est prêt pour téléchargement, cliquez sur ce lien pour l'obtenir");
define('_AM_BOOKSHOP_NEW_QUANTITY', 'Nouvelle quantité');
define('_AM_BOOKSHOP_UPDATE_QUANTITIES', 'Mettre à jour les quantités');
define('_AM_BOOKSHOP_NEWSLETTER_READY', 'Votre newsletter est prêt, cliquez sur ce lien pour la récupérer.');
define('_AM_BOOKSHOP_DUPLICATED', 'Dupliqué');    // Added to the book's title to distinguish it

// Added on 14/04/2007 17:11
define('_AM_BOOKSHOP_SORRY_NOREMOVE', 'Désolé mais nous ne pouvons pas supprimer ce livre car il fait partie des commandes suivantes');
define('_AM_BOOKSHOP_CONF_VALIDATE', 'Confirmez vous la validation de cette commande ?');
define('_AM_BOOKSHOP_LAST_ORDERS', 'Dernières commandes');
define('_AM_BOOKSHOP_LAST_VOTES', 'Derniers votes');
define('_AM_BOOKSHOP_NOTE', 'Note');
define('_AM_BOOKSHOP_AT_COUNT', '%d Auteur(s) / %d Traducteur(s)');

// Added in v1.4
define('_AM_BOOKSHOP_RECOMMEND_IT', 'Le recommander');
define('_AM_BOOKSHOP_DONOTRECOMMEND_IT', 'Arrêter de le recommander');
define('_AM_BOOKSHOP_RECOMMENDED', 'Recommandé');
define('_AM_BOOKSHOP_RECOMM_TEXT', 'Texte à afficher sur la page des produits recommandés');
define('_AM_BOOKSHOP_META_KEYWORDS', 'Meta keywords');
define('_AM_BOOKSHOP_META_DESCRIPTION', 'Meta description');
define('_AM_BOOKSHOP_META_PAGETITLE', 'Titre de la page');
