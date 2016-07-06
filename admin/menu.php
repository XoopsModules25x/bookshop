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
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

//$path = dirname(dirname(dirname(__DIR__)));
//include_once $path . '/mainfile.php';

$module_handler = xoops_getHandler('module');
$module         = $module_handler->getByDirname(basename(dirname(__DIR__)));
$pathIcon32     = '../../' . $module->getInfo('icons32');
xoops_loadLanguage('modinfo', $module->dirname());

$pathModuleAdmin = XOOPS_ROOT_PATH . '/' . $module->getInfo('dirmoduleadmin') . '/moduleadmin';
if (!file_exists($fileinc = $pathModuleAdmin . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathModuleAdmin . '/language/english/main.php';
}
include_once $fileinc;

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png'
);

$adminmenu[] = array(
    'title' => _MI_BOOKSHOP_ADMENU10,
    'link'  => 'admin/main.php?op=dashboard',
    'icon'  => $pathIcon32 . '/manage.png'
);

$adminmenu[] = array(
    'title' => _MI_BOOKSHOP_ADMENU0,
    'link'  => 'admin/main.php?op=lang',
    'icon'  => $pathIcon32 . '/languages.png'
);

$adminmenu[] = array(
    'title' => _MI_BOOKSHOP_ADMENU1,
    'link'  => 'admin/main.php?op=vat',
    'icon'  => $pathIcon32 . '/calculator.png'
);

$adminmenu[] = array(
    'title' => _MI_BOOKSHOP_ADMENU2,
    'link'  => 'admin/main.php?op=categories',
    'icon'  => $pathIcon32 . '/category.png'
);

$adminmenu[] = array(
    'title' => _MI_BOOKSHOP_ADMENU3,
    'link'  => 'admin/main.php?op=authors',
    'icon'  => $pathIcon32 . '/translations.png'
);

$adminmenu[] = array(
    'title' => _MI_BOOKSHOP_ADMENU4,
    'link'  => 'admin/main.php?op=books',
    'icon'  => $pathIcon32 . '/album.png'
);

$adminmenu[] = array(
    'title' => _MI_BOOKSHOP_ADMENU5,
    'link'  => 'admin/main.php?op=commands',
    'icon'  => $pathIcon32 . '/exec.png'
);

$adminmenu[] = array(
    'title' => _MI_BOOKSHOP_ADMENU6,
    'link'  => 'admin/main.php?op=discount',
    'icon'  => $pathIcon32 . '/discount.png'
);

$adminmenu[] = array(
    'title' => _MI_BOOKSHOP_ADMENU7,
    'link'  => 'admin/main.php?op=newsletter',
    'icon'  => $pathIcon32 . '/prune.png'
);

$adminmenu[] = array(
    'title' => _MI_BOOKSHOP_ADMENU8,
    'link'  => 'admin/main.php?op=texts',
    'icon'  => $pathIcon32 . '/highlight.png'
);

$adminmenu[] = array(
    'title' => _MI_BOOKSHOP_ADMENU9,
    'link'  => 'admin/main.php?op=lowstock',
    'icon'  => $pathIcon32 . '/alert.png'
);

//$adminmenu[] = array(
//    'title' => 'XOOPS',
//    'link'  => 'admin/main.php?op=xoops',
//    'icon'  => $pathIcon32 . '/manage.png'
//);

//$adminmenu[12]['title'] = _MI_BOOKSHOP_ADMENU11;
//$adminmenu[12]['link'] = "admin/main.php?op=email";

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
);
