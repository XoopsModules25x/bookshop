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

/**
 * Returns a module's option
 *
 * Return's a module's option (for the Bookshop module)
 *
 * @package       Bookshop
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 *
 * @param string $option module option's name
 *
 * @param string $repmodule
 *
 * @return bool
 */
function bookshop_getmoduleoption($option, $repmodule = 'bookshop')
{
    global $xoopsModuleConfig, $xoopsModule;
    static $tbloptions = array();
    if (is_array($tbloptions) && array_key_exists($option, $tbloptions)) {
        return $tbloptions[$option];
    }

    $retval = false;
    if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule && $xoopsModule->getVar('isactive'))) {
        if (isset($xoopsModuleConfig[$option])) {
            $retval = $xoopsModuleConfig[$option];
        }
    } else {
        $module_handler = xoops_getHandler('module');
        $module         =& $module_handler->getByDirname($repmodule);
        $config_handler = xoops_getHandler('config');
        if ($module) {
            $moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
            if (isset($moduleConfig[$option])) {
                $retval = $moduleConfig[$option];
            }
        }
    }
    $tbloptions[$option] = $retval;

    return $retval;
}

/**
 * Indique si on utilise Xoops 2.2.x
 *
 * @return boolean vrai si Xoops 2.2.x sinon false
 */
function bookshop_is_x22()
{
    $x22 = false;
    $xv  = str_replace('XOOPS ', '', XOOPS_VERSION);
    if (substr($xv, 2, 1) == '2') {
        $x22 = true;
    }

    return $x22;
}

/**
 * Retreive an editor according to the module's option "form_options"
 *
 * @param string $caption Titre de la zone d'édition
 * @param string $name Nom de la zone d'édition
 * @param string $value Contenu initial de la zone d'édition
 * @param string $width Largeur de la zone d'édition
 * @param string $height Hauteur de la zone d'édition
 * @param string $supplemental Paramètres supplémentaires à passer à la zone d'édition
 * @return object L'éditeur
 */
function bookshop_getWysiwygForm($caption, $name, $value = '', $width = '100%', $height = '400px', $supplemental = '')
{
    $editor                   = false;
    $x22                      = bookshop_is_x22();
    $editor_configs           = array();
    $editor_configs['name']   = $name;
    $editor_configs['value']  = $value;
    $editor_configs['rows']   = 35;
    $editor_configs['cols']   = 60;
    $editor_configs['width']  = $width;
    $editor_configs['height'] = $height;

    $editor_option = bookshop_getmoduleoption('bl_form_options');

    switch (strtolower($editor_option)) {
        case 'spaw':
            if (!$x22) {
                if (is_readable(XOOPS_ROOT_PATH . '/class/spaw/formspaw.php')) {
                    include_once(XOOPS_ROOT_PATH . '/class/spaw/formspaw.php');
                    $editor = new XoopsFormSpaw($caption, $name, $value);
                }
            } else {
                $editor = new XoopsFormEditor($caption, 'spaw', $editor_configs);
            }
            break;

        case 'fck':
            if (!$x22) {
                if (is_readable(XOOPS_ROOT_PATH . '/class/fckeditor/formfckeditor.php')) {
                    include_once(XOOPS_ROOT_PATH . '/class/fckeditor/formfckeditor.php');
                    $editor = new XoopsFormFckeditor($caption, $name, $value);
                }
            } else {
                $editor = new XoopsFormEditor($caption, 'fckeditor', $editor_configs);
            }
            break;

        case 'htmlarea':
            if (!$x22) {
                if (is_readable(XOOPS_ROOT_PATH . '/class/htmlarea/formhtmlarea.php')) {
                    include_once(XOOPS_ROOT_PATH . '/class/htmlarea/formhtmlarea.php');
                    $editor = new XoopsFormHtmlarea($caption, $name, $value);
                }
            } else {
                $editor = new XoopsFormEditor($caption, 'htmlarea', $editor_configs);
            }
            break;

        case 'dhtml':
            if (!$x22) {
                $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 10, 50, $supplemental);
            } else {
                $editor = new XoopsFormEditor($caption, 'dhtmltextarea', $editor_configs);
            }
            break;

        case 'textarea':
            $editor = new XoopsFormTextArea($caption, $name, $value);
            break;

        case 'tinyeditor':
            if (is_readable(XOOPS_ROOT_PATH . '/class/xoopseditor/tinyeditor/formtinyeditortextarea.php')) {
                include_once XOOPS_ROOT_PATH . '/class/xoopseditor/tinyeditor/formtinyeditortextarea.php';
                $editor = new XoopsFormTinyeditorTextArea(array('caption' => $caption, 'name' => $name, 'value' => $value, 'width' => $width, 'height' => $height));
            }
            break;

        case 'koivi':
            if (!$x22) {
                if (is_readable(XOOPS_ROOT_PATH . '/class/wysiwyg/formwysiwygtextarea.php')) {
                    include_once(XOOPS_ROOT_PATH . '/class/wysiwyg/formwysiwygtextarea.php');
                    $editor = new XoopsFormWysiwygTextArea($caption, $name, $value, '100%', '250px', '');
                }
            } else {
                $editor = new XoopsFormEditor($caption, 'koivi', $editor_configs);
            }
            break;
    }

    return $editor;
}

/**
 * Create (in a link) a javascript confirmation's box
 *
 * @package         CP
 * @author          Instant Zero http://www.instant-zero.com
 * @copyright   (c) Instant Zero http://www.instant-zero.com
 *
 * @param string $msg	Le message à afficher
 * @param boolean $form Est-ce une confirmation pour un formulaire ?
 * @return string La "commande" javscript à insérer dans le lien
 */
function bookshop_JavascriptLinkConfirm($msg, $form = false)
{
    if (!$form) {
        return "onclick=\"javascript:return confirm('" . str_replace("'", ' ', $msg) . "')\"";
    } else {
        return "onSubmit=\"javascript:return confirm('" . str_replace("'", ' ', $msg) . "')\"";
    }
}

/**
 * Fonction chargée de renvoyer l'adresse IP du visiteur courant
 * En essayant de tenir compte des proxy
 *
 * @return string L'adresse IP (format Ipv4)
 */
function bookshop_IP()
{
    $proxy_ip = '';
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
        $proxy_ip = $_SERVER['HTTP_FORWARDED'];
    } elseif (!empty($_SERVER['HTTP_VIA'])) {
        $proxy_ip = $_SERVER['HTTP_VIA'];
    } elseif (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
        $proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
    } elseif (!empty($_SERVER['HTTP_COMING_FROM'])) {
        $proxy_ip = $_SERVER['HTTP_COMING_FROM'];
    }
    $regs = array();
    if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0) {
        $the_IP = $regs[0];
    } else {
        $the_IP = $_SERVER['REMOTE_ADDR'];
    }

    return $the_IP;
}

/**
 * Set the page's title, meta description and meta keywords
 * Datas are supposed to be sanitized
 *
 * @param string $page_title       Page's Title
 * @param string $meta_description Page's meta description
 * @param string $meta_keywords    Page's meta keywords
 * @return void
 */
function bookshop_set_metas($page_title = '', $meta_description = '', $meta_keywords = '')
{
    global $xoTheme, $xoTheme, $xoopsTpl;
    $xoopsTpl->assign('xoops_pagetitle', $page_title);
    if (isset($xoTheme) && is_object($xoTheme)) {
        if (!empty($meta_keywords)) {
            $xoTheme->addMeta('meta', 'keywords', $meta_keywords);
        }
        if (!empty($meta_description)) {
            $xoTheme->addMeta('meta', 'description', $meta_description);
        }
    } elseif (isset($xoopsTpl) && is_object($xoopsTpl)) {    // Compatibility for old Xoops versions
        if (!empty($meta_keywords)) {
            $xoopsTpl->assign('xoops_meta_keywords', $meta_keywords);
        }
        if (!empty($meta_description)) {
            $xoopsTpl->assign('xoops_meta_description', $meta_description);
        }
    }
}

/**
 * Envoi d'un email à partir d'un template à un groupe de personnes
 *
 * @param string $tpl_name	Nom du template à utiliser
 * @param array  $recipients Liste des destinataires
 * @param string $subject    Sujet du mail
 * @param array $variables	Variables à passer au template
 * @return boolean Le résultat de l'envoi du mail
 */
function bookshop_send_email_from_tpl($tpl_name, $recipients, $subject, $variables)
{
    global $xoopsConfig;
    include_once XOOPS_ROOT_PATH . '/class/xoopsmailer.php';
    if (!is_array($recipients)) {
        if (trim($recipients) == '') {
            return false;
        }
    } else {
        if (count($recipients) == 0) {
            return false;
        }
    }
    if (function_exists('xoops_getMailer')) {
        $xoopsMailer =& xoops_getMailer();
    } else {
        $xoopsMailer =& getMailer();
    }

    $xoopsMailer->useMail();
    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/bookshop/language/' . $xoopsConfig['language'] . '/mail_template');
    $xoopsMailer->setTemplate($tpl_name);
    $xoopsMailer->setToEmails($recipients);
    // Change !
    //$xoopsMailer->setFromEmail('contact@gbphotosports.com');
    //$xoopsMailer->setFromName('PhotoSports');
    $xoopsMailer->setSubject($subject);
    foreach ($variables as $key => $value) {
        $xoopsMailer->assign($key, $value);
    }
    $res = $xoopsMailer->send();
    unset($xoopsMailer);

    $fp = @fopen(XOOPS_UPLOAD_PATH . '/logmail_bookshop.txt', 'a');
    if ($fp) {
        fwrite($fp, str_repeat('-', 120) . "\n");
        fwrite($fp, date('d/m/Y H:i:s') . "\n");
        fwrite($fp, 'Nom du template : ' . $tpl_name . "\n");
        fwrite($fp, 'Sujet du mail : ' . $subject . "\n");
        if (is_array($recipients)) {
            fwrite($fp, 'Destinaire(s) du mail : ' . implode(',', $recipients) . "\n");
        } else {
            fwrite($fp, 'Destinaire(s) du mail : ' . $recipients . "\n");
        }
        fwrite($fp, 'Variables transmises : ' . implode(',', $variables) . "\n");
        fclose($fp);
    }

    return $res;
}

/**
 * Remove module's cache
 *
 * @return void
 */
function bookshop_updateCache()
{
    global $xoopsModule;
    $folder  = $xoopsModule->getVar('dirname');
    $tpllist = array();
    include_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
    include_once XOOPS_ROOT_PATH . '/class/template.php';
    $tplfile_handler = xoops_getHandler('tplfile');
    $tpllist         = $tplfile_handler->find(null, null, null, $folder);
    xoops_template_clear_module_cache($xoopsModule->getVar('mid'));            // Clear module's blocks cache

    // Remove cache for each page.
    foreach ($tpllist as $onetemplate) {
        if ($onetemplate->getVar('tpl_type') === 'module') {
            // Note, I've been testing all the other methods (like the one of Smarty) and none of them run, that's why I have used this code
            $files_del = array();
            $files_del = glob(XOOPS_CACHE_PATH . '/*' . $onetemplate->getVar('tpl_file') . '*');
            if (count($files_del) > 0 && is_array($files_del)) {
                foreach ($files_del as $one_file) {
                    if (is_file($one_file)) {
                        unlink($one_file);
                    }
                }
            }
        }
    }
}

/**
 * Create an infotip
 *
 * @param string $text Le texte dont on veut créer une bulle d'aide
 * @return string La bulle d'aide
 */
function bookshop_make_infotips($text)
{
    $infotips = bookshop_getmoduleoption('infotips');
    if ($infotips > 0) {
        $myts = MyTextSanitizer::getInstance();

        return $myts->htmlSpecialChars(xoops_substr(strip_tags($text), 0, $infotips));
    }
}

/**
 * Redirect user with a message
 *
 * @param string $message message to display
 * @param string $url     The place where to go
 * @param        integer  timeout Time to wait before to redirect
 */
function bookshop_redirect($message = '', $url = 'index.php', $time = 2)
{
    redirect_header($url, $time, $message);
    exit();
}

/**
 * Renvoie l'objet du module ...
 *
 * @return object L'objet XoopsModule pour Bookshop
 */
function bookshop_get_module()
{
    static $mymodule;
    if (!isset($mymodule)) {
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == BOOKSHOP_DIRNAME) {
            $mymodule =& $xoopsModule;
        } else {
            $hModule  = xoops_getHandler('module');
            $mymodule = $hModule->getByDirname(BOOKSHOP_DIRNAME);
        }
    }

    return $mymodule;
}

/**
 * Renvoie le nom du module (tel que défini dans le gestionnaire de modules de Xoops)
 *
 * @return string Le nom du module
 */
function bookshop_get_module_name()
{
    static $module_name;
    if (!isset($module_name)) {
        $mymodule    = bookshop_get_module();
        $module_name = $mymodule->getVar('name');
    }

    return $module_name;
}

/**
 * Création d'une titre pour être utilisé par l'url rewriting
 *
 * @param string $content Le contenu a utiliser pour créer l'url
 * @param integer $urw Limite basse en dessous de laquelle chaque "mot" n'est pas utilisé
 * @return string Le texte qui peut être utilisé pour l'URL
 */
function bookshop_makeSEOurl($content, $urw = 1)
{
	$s       = "ÀÁÂÃÄÅÒÓÔÕÖØÈÉÊËÇÌÍÎÏÙÚÛÜŸÑàáâãäåòóôõöøèéêëçìíîïùúûüÿñ '()";
    $r       = 'AAAAAAOOOOOOEEEECIIIIUUUUYNaaaaaaooooooeeeeciiiiuuuuyn----';
    $content = strtr($content, $s, $r);
    $content = strip_tags($content);
    $content = strtolower($content);
    $content = htmlentities($content);
    $content = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/', '$1', $content);
    $content = html_entity_decode($content);
    $content = preg_replace('/quot/i', ' ', $content);
    $content = preg_replace("/'/i", ' ', $content);
    $content = preg_replace('/-/i', ' ', $content);
    $content = preg_replace('/[[:punct:]]/i', '', $content);

    // Selon option mais attention au fichier .htaccess !
    //$content = eregi_replace('[[:digit:]]','', $content);
    $content = preg_replace('/[^a-z|A-Z|0-9]/', '-', $content);    // moi

    $words    = explode(' ', $content);
    $keywords = '';
    foreach ($words as $word) {
        if (strlen($word) >= $urw) {
            $keywords .= '-' . trim($word);
        }
    }
    if (!$keywords) {
        $keywords = '-';
    }
    // Supprime les tirets en double
    $keywords = str_replace('--', '-', $keywords);
	// Supprime un éventuel tiret à la fin de la chaine
    if (substr($keywords, strlen($keywords) - 1, 1) == '-') {
        $keywords = substr($keywords, 0, strlen($keywords) - 1);
    }

    return $keywords;
}

/**
 * Mise en place de l'appel à la feuille de style du module dans le template
 *
 * @return void
 */
function bookshop_setCSS()
{
    global $xoopsTpl;
    $url = BOOKSHOP_URL . 'assets/css/bookshop.css';
    $xoopsTpl->assign('xoops_module_header', "<link rel=\"stylesheet\" type=\"text/css\" href=\"$url\" />");
}

/**
 * Création d'un titre pour les balises href des liens html
 *
 * @param string $title La chaine que l'on souhaite utiliser comme titre
 * @return string La chaine formatée pour être utilisée dans l'attribut title d'une balise anchor
 */
function bookshop_makeHrefTitle($title)
{
    $s = "\"'";
    $r = '  ';

    return strtr($title, $s, $r);
}

/**
 * Formate une monaie en fonction des préférences du module
 *
 * @param float $ttc Le montant à formater
 * @return string Le montant formaté
 */
function bookshop_formatMoney($ttc)
{
    $retval = sprintf('%0.' . bookshop_getmoduleoption('decimals_count') . 'f', $ttc);

    return $retval;
}

/**
 * Calcul du TTC � partir du HT et de la TVA
 *
 * @param float   $ht   Le montant HT dont on veut calculer le TTC
 * @param float $vat Le montant de la TVA
 * @param boolean $edit Est-ce que le montant est pour être visualisé, auquel cas on le formate
 *
 * @return float|string
 */
function bookshop_getTTC($ht, $vat, $edit = false)
{
    $ttc = $ht * (1 + ($vat / 100));
    if (!$edit) {
        return bookshop_formatMoney($ttc);
    } else {
        return $ttc;
    }
}

/**
 * Calcul de la réduction
 *
 * @param float $price Le montant dont on veut calculer la réduction
 * @param integer $dicount Le montant de la rédution, par exemple 10 pour 10%
 * @return float Le montant de la réduction
 */
function bookshop_getDiscountedPrice($price, $discount)
{
    return $price - ($price * ($discount / 100));
}

/**
 * Renvoie le montant de la tva
 *
 * @param float $ht Le montant HT
 * @param float $vat Le montant de la TVA (par exemple 19.6)
 * @return float Le montant de la TVA
 */
function bookshop_getVAT($ht, $vat)
{
    return ($ht * $vat) / 100;
}

/**
 * Renvoie le HT d'un livre à partir de son TTC
 *
 * @param float $ttc  Le montant ttc
 * @param float ^vat Le montant de la TVA
 * @return string Le montant HT formaté avec les paramètres de monnaie
 */
function bookshop_getHT($ttc, $vat)
{
    $ht = $ttc / (1 + ($vat / 100));

    return bookshop_formatMoney($ht);
}

/**
 * Création des meta keywords à partir d'un contenu
 *
 * @param string $content Contenu dont il faut extraire les mots clés
 * @return void
 */
function bookshop_createmeta_keywords($content)
{
    $keywordscount = bookshop_getmoduleoption('metagen_maxwords');
    $keywordsorder = bookshop_getmoduleoption('metagen_order');

    $tmp = array();
    // Search for the "Minimum keyword length"
    if (isset($_SESSION['bookshop_keywords_limit'])) {
        $limit = $_SESSION['bookshop_keywords_limit'];
    } else {
        $config_handler                      = xoops_getHandler('config');
        $xoopsConfigSearch                   =& $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);
        $limit                               = $xoopsConfigSearch['keyword_min'];
        $_SESSION['bookshop_keywords_limit'] = $limit;
    }
    $myts            = MyTextSanitizer::getInstance();
    $content         = str_replace('<br>', ' ', $content);
    $content         = $myts->undoHtmlSpecialChars($content);
    $content         = strip_tags($content);
    $content         = strtolower($content);
    $search_pattern  = array('&nbsp;', "\t", "\r\n", "\r", "\n", ',', '.', "'", ';', ':', ')', '(', '"', '?', '!', '{', '}', '[', ']', '<', '>', '/', '+', '-', '_', '\\', '*');
    $replace_pattern = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
    $content         = str_replace($search_pattern, $replace_pattern, $content);
    $keywords        = explode(' ', $content);
    switch ($keywordsorder) {
        case 0:    // Ordre d'apparition dans le texte
            $keywords = array_unique($keywords);
            break;
		case 1:	// Ordre de fréquence des mots
            $keywords = array_count_values($keywords);
            asort($keywords);
            $keywords = array_keys($keywords);
            break;
		case 2:	// Ordre inverse de la fréquence des mots
            $keywords = array_count_values($keywords);
            arsort($keywords);
            $keywords = array_keys($keywords);
            break;
    }
    // Remove black listed words
    if (xoops_trim(bookshop_getmoduleoption('metagen_blacklist')) != '') {
        $metagen_blacklist = str_replace("\r", '', bookshop_getmoduleoption('metagen_blacklist'));
        $metablack         = explode("\n", $metagen_blacklist);
        array_walk($metablack, 'trim');
        $keywords = array_diff($keywords, $metablack);
    }

    foreach ($keywords as $keyword) {
        if (strlen($keyword) >= $limit && !is_numeric($keyword)) {
            $tmp[] = $keyword;
        }
    }
    $tmp = array_slice($tmp, 0, $keywordscount);
    if (count($tmp) > 0) {
        return implode(',', $tmp);
    } else {
        if (!isset($config_handler) || !is_object($config_handler)) {
            $config_handler = xoops_getHandler('config');
        }
        $xoopsConfigMetaFooter =& $config_handler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
        if (isset($xoopsConfigMetaFooter['meta_keywords'])) {
            return $xoopsConfigMetaFooter['meta_keywords'];
        } else {
            return '';
        }
    }
}

/**
 * Renvoie la liste des utilisateurs d'un groupe
 *
 * @param int $group_id	Groupe recherché
 * @return array tableau d'objets XoopsUser
 */
function bookshop_getUsersFromGroup($group_id)
{
    $tbl_users      = array();
    $member_handler = xoops_getHandler('member');
    $tbl_users      = $member_handler->getUsersByGroup($group_id, true);

    return $tbl_users;
}

/**
 * Renvoie la liste des adresses email d'un groupe
 *
 * @param int $group_id	Le numéro du groupe
 * @return array La liste des emails
 */
function bookshop_getEmailsFromGroup($group_id)
{
    $ret       = array();
    $tbl_users = bookshop_getUsersFromGroup($group_id);
    foreach ($tbl_users as $user) {
        $ret[] = $user->getVar('email');
    }

    return $ret;
}

/**
 * Inutilisé, sert normalement pour l'IPN
 * @param $datastream
 * @param $url
 * @return string
 */
function bookshop_post_it($datastream, $url)
{
    $url     = preg_replace('@^http://@i', '', $url);
    $host    = substr($url, 0, strpos($url, '/'));
    $uri     = strstr($url, '/');
    $reqbody = '';
    foreach ($datastream as $key => $val) {
        if (!empty($reqbody)) {
            $reqbody .= '&';
        }
        $reqbody .= $key . '=' . urlencode($val);
    }
    $contentlength = strlen($reqbody);
    $reqheader     = "POST $uri HTTP/1.1\r\n" . "Host: $host\n" . "Content-Type: application/x-www-form-urlencoded\r\n" . "Content-Length: $contentlength\r\n\r\n" . "$reqbody\r\n";

    return $reqheader;
}

/**
 * Verify that the current user is a member of the Admin group
 *
 * @return booleean Admin or not
 */
function bookshop_isAdmin()
{
    global $xoopsUser, $xoopsModule;
    if (is_object($xoopsUser)) {
        if (in_array(XOOPS_GROUP_ADMIN, $xoopsUser->getGroups())) {
            return true;
        } else {
            if (isset($xoopsModule)) {
                if ($xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}

/**
 * Indique si l'utilisateur courant fait partie d'une groupe donné (avec gestion de cache)
 *
 * @param int $group
 * @param integer $groupe Groupe recherché
 * @return boolean vrai si l'utilisateur fait partie du groupe, faux sinon
 */
function bookshop_isMemberOfGroup($group = 0)
{
    global $xoopsUser;
    static $tblBuffer = array();
    $retval = false;
    if (is_object($xoopsUser)) {
        $uid = $xoopsUser->getVar('uid');
    } else {
        $uid = 0;
    }
    if (is_array($tblBuffer) && array_key_exists($group, $tblBuffer)) {
        $retval = $tblBuffer[$group];
    } else {
        $member_handler    = xoops_getHandler('member');
        $tblGroups         = $member_handler->getGroupsByUser($uid, false);    // Renvoie un tableau d'ID (de groupes)
        $retval            = in_array($group, $tblGroups);
        $tblBuffer[$group] = $retval;
    }

    return $retval;
}

/**
 * This function indicates if the current Xoops version needs to add asterisks to required fields in forms
 *
 * @return boolean Yes = we need to add them, false = no
 */
function bookshop_needsAsterisk()
{
    if (bookshop_is_x22()) {
        return false;
    }
    if (strpos(strtolower(XOOPS_VERSION), 'legacy') === false) {
        $xv = xoops_trim(str_replace('XOOPS ', '', XOOPS_VERSION));
        if ((int)substr($xv, 4, 2) >= 17) {
            return false;
        }
    }

    return true;
}

/**
 * Mark the mandatory fields of a form with a star
 *
 * @param object $sform The form to modify
 * @internal param string $caracter The character to use to mark fields
 * @return object The modified form
 */
function bookshop_formMarkRequiredFields(&$sform)
{
    if (bookshop_needsAsterisk()) {
        $tblRequired = array();
        foreach ($sform->getRequired() as $item) {
            $tblRequired[] = $item->_name;
        }
        $tblElements = array();
        $tblElements = &$sform->getElements();
        $cnt         = count($tblElements);
        for ($i = 0; $i < $cnt; ++$i) {
            if (is_object($tblElements[$i]) && in_array($tblElements[$i]->_name, $tblRequired)) {
                $tblElements[$i]->_caption .= ' *';
            }
        }
    }

    return $sform;
}
