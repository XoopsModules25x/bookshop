<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<div align='center'>
    <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>bookshop.png" alt="" border="0"/>
    <{if $mod_pref.advertisement != ''}>
        <div id="bookshop_publicite"><{$mod_pref.advertisement}></div><{/if}>

    <h2><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>folder_orange_open.png" alt="" border="0" width="32" height="32"/><{$smarty.const._MI_BOOKSHOP_SMNAME4}></h2>
</div>

<table width="100%" cellspacing="0">
    <tr>
        <td class="page-curl_01">
            <div class="bookshop_cat-map">
                <ul>
                    <{foreach item=category from=$categories}>
                        <li><a href="<{$category.cat_url_rewrited}>" title="<{$category.cat_href_title}>"><{$category.cat_title}></a></li>
                    <{/foreach}>
                </ul>
            </div>
        </td>
    </tr>
</table>


<{* ********************************************* NOTIFICATION ******************************************* *}>
<{include file='db:system_notification_select.tpl'}>
<{* ******************************************** /NOTIFICATION ******************************************* *}>
