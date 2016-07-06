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
 * @param int    $currentoption
 * @param string $breadcrumb
 */
function bookshop_adminMenu($currentoption = 0, $breadcrumb = '')
{
    global $xoopsConfig, $xoopsModule;
    if (file_exists(XOOPS_ROOT_PATH . '/modules/bookshop/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
        include_once XOOPS_ROOT_PATH . '/modules/bookshop/language/' . $xoopsConfig['language'] . '/modinfo.php';
    } else {
        include_once XOOPS_ROOT_PATH . '/modules/bookshop/language/english/modinfo.php';
    }
    global $adminmenu;
    include __DIR__ . '/menu.php';

    echo "<style type=\"text/css\">\n";
    echo "#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }\n";
    echo "#buttonbar { float:left; width:100%; background: #e7e7e7 url('../assets/images/modadminbg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }\n";
    echo "#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }\n";
    echo '#buttonbar li { display:inline; margin:0; padding:0; }';
    echo "#buttonbar a { float:left; background:url('../assets/images/left_both.gif') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }\n";
    echo "#buttonbar a span { float:left; display:block; background:url('../assets/images/right_both.gif') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }\n";
    echo "/* Commented Backslash Hack hides rule from IE5-Mac \*/\n";
    echo "#buttonbar a span {float:none;}\n";
    echo "/* End IE5-Mac hack */\n";
    echo "#buttonbar a:hover span { color:#333; }\n";
    echo "#buttonbar .current a { background-position:0 -150px; border-width:0; }\n";
    echo "#buttonbar .current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }\n";
    echo "#buttonbar a:hover { background-position:0% -150px; }\n";
    echo "#buttonbar a:hover span { background-position:100% -150px; }\n";
    echo "</style>\n";

    echo "<div id=\"buttontop\">\n";
    echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\">\n";
    echo "<tr>\n";
    echo "<td style=\"width: 70%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\">\n";
    echo "<a href=\"../index.php\">" . _AM_BOOKSHOP_GO_TO_MODULE . "</a> | <a href=\"" . XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid') . "\">" . _AM_BOOKSHOP_PREFERENCES . "</a>\n";
    echo "</td>\n";
    echo "<td style=\"width: 30%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;\">\n";
    echo '<b>' . $xoopsModule->getVar('name') . '&nbsp;' . _AM_BOOKSHOP_ADMINISTRATION . '</b>&nbsp;' . $breadcrumb . "\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</div>\n";
    echo "<div id=\"buttonbar\">\n";
    echo "<ul>\n";
    foreach ($GLOBALS['adminmenu'] as $key => $link) {
        if ($key == $currentoption) {
            echo "<li class=\"current\">\n";
        } else {
            echo "<li>\n";
        }
        echo "<a href=\"" . XOOPS_URL . '/modules/bookshop/' . $link['link'] . "\"><span>" . $link['title'] . "</span></a>\n";
        echo "</li>\n";
    }
    echo "</ul>\n";
    echo "</div>\n";
    echo "<br style=\"clear:both;\" />\n";
}

/**
 * Internal function
 */
function bookshop_get_mid()
{
    global $xoopsModule;

    return $xoopsModule->getVar('mid');
}

/**
 * Internal function
 */
function bookshop_get_config_handler()
{
    $config_handler = null;
    $config_handler = xoops_getHandler('config');
    if (!is_object($config_handler)) {
        trigger_error('Error, unable to get and handler on the Config object');
        exit;
    } else {
        return $config_handler;
    }
}

/**
 * Returns a module option
 *
 * @param string $option_name
 * @param bool   $as_object
 *
 * @return mixed
 */
function bookshop_get_module_option($option_name = '', $as_object = false)
{
    $tbl_options    = array();
    $mid            = bookshop_get_mid();
    $config_handler = bookshop_get_config_handler();
    $critere        = new CriteriaCompo();
    $critere->add(new Criteria('conf_modid', $mid, '='));
    $critere->add(new Criteria('conf_name', $option_name, '='));
    $tbl_options = $config_handler->getConfigs($critere, false, false);
    if (count($tbl_options) > 0) {
        $option = $tbl_options[0];
        if (!$as_object) {
            return $option->getVar('conf_value');
        } else {
            return $option;
        }
    }
}

/**
 * Set a module's option
 * @param string $option_name
 * @param string $option_value
 * @return mixed
 */
function bookshop_set_module_option($option_name = '', $option_value = '')
{
    $config_handler = bookshop_get_config_handler();
    $option         = bookshop_get_module_option($option_name, true);
    $option->setVar('conf_value', $option_value);
    $retval = $config_handler->insertConfig($option, true);

    return $retval;
}

/**
 * Create a unique upload filename
 *
 * @package          Bookshop
 * @author           Instant Zero http://www.instant-zero.com
 * @copyright    (c) Instant Zero http://www.instant-zero.com
 * @param      $folder
 * @param      $filename
 * @param bool $trimname
 * @return string
 */

function bookshop_createUploadName($folder, $filename, $trimname = false)
{
    $workingfolder = $folder;
    if (xoops_substr($workingfolder, strlen($workingfolder) - 1, 1) !== '/') {
        $workingfolder .= '/';
    }
    $ext  = basename($filename);
    $ext  = explode('.', $ext);
    $ext  = '.' . $ext[count($ext) - 1];
    $true = true;
    while ($true) {
        $ipbits = explode('.', $_SERVER['REMOTE_ADDR']);
        list($usec, $sec) = explode(' ', microtime());
        $usec = (integer)($usec * 65536);
        $sec  = ((integer)$sec) & 0xFFFF;

        if ($trimname) {
            $uid = sprintf('%06x%04x%04x', ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
        } else {
            $uid = sprintf('%08x-%04x-%04x', ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
        }
        if (!file_exists($workingfolder . $uid . $ext)) {
            $true = false;
        }
    }

    return $uid . $ext;
}

/**
 * @param string $title
 * @param int    $level
 */
function bookshop_htitle($title = '', $level = 1)
{
    printf('<h%01d>%s</h%01d>', $level, $title, $level);
}

/**
 * Verify that a field exists inside a mysql table
 * @param $fieldname
 * @param $table
 * @return bool
 */
function booksop_FieldExists($fieldname, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW COLUMNS FROM $table LIKE '$fieldname'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * Add a field to a mysql table
 * @param $field
 * @param $table
 * @return resource
 */
function booksop_AddField($field, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF('ALTER TABLE ' . $table . " ADD $field;");

    return $result;
}
