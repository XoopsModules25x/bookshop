<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<div align='center'>
    <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>bookshop.gif" alt="" border="0"/>
    <{if $welcome_msg != ''}>
        <br>
        <br>
        <div id="bookshop_welcome"><{$welcome_msg}></div><{/if}>
    <{if $mod_pref.advertisement != ''}>
        <br>
        <br>
        <div id="bookshop_publicite"><{$mod_pref.advertisement}></div>
        <br>
    <{/if}>
</div>
<h2><{$smarty.const._MI_BOOKSHOP_SMNAME6}></h2>
<div align="right"><i><{$smarty.const._BOOKSHOP_CATALOG_HLP}></i></div>
<br>
<script language="javascript" type="text/javascript" src="<{$smarty.const.BOOKSHOP_URL}>assets/js/tableWidget.js"></script>
<div class="widget_tableDiv">
    <table id="myTable">
        <thead>
        <tr>
            <td><{$smarty.const._BOOKSHOP_TITLE}></td>
            <td><{$smarty.const._BOOKSHOP_CATEGORY}></td>
            <td><{$smarty.const._BOOKSHOP_PRICE}></td>
            <td><{$smarty.const._BOOKSHOP_ADD_TO_CART}></td>
        </tr>
        </thead>
        <tbody class="scrollingContent">
        <{foreach item=book from=$books}>
            <tr>
                <td><a href="<{$book.book_url_rewrited}>" title="<{$book.book_href_title}>"><{$book.book_title}></a></td>
                <td><{$book.book_category.cat_title}></td>
                <td><{if $book.book_discount_price_ttc > 0}><{$book.book_discount_price_ttc}><{else}><{$book.book_price_ttc}><{/if}> <{$mod_pref.money_short}></td>
                <td align='center'><{if $book.book_stock > 0 }><a href="<{$smarty.const.BOOKSHOP_URL}>caddy.php?op=addbook&book_id=<{$book.book_id}>" title="<{$smarty.const._BOOKSHOP_ADD_TO_CART}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>cartadd.gif"
                                                                                                                                                                                                           alt="<{$smarty.const._BOOKSHOP_ADD_TO_CART}>" border="0"/>
                        </a><{else}><{$mod_pref.nostock_msg}><{/if}></td>
            </tr>
        <{/foreach}>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    <{* Table's ID, Width of table, Height of table , An array telling how the columns should be sorted. "S" = String, "N" = Numeric. Use false(without quotes) instead of "S" and "N" if you have columns that shouldn't be sorted at all. *}>
    initTableWidget('myTable', 600, 450, array('S', 'S', 'N', false));
</script>

<{if $pdf_catalog == 1 }>
    <br>
    <br>
    <h3><{$smarty.const._BOOKSHOP_PDF_CATALOG}></h3>
    <form name="frmCatalog" id="frmCatalog" method="post" action="<{$smarty.const.BOOKSHOP_URL}>pdf/catalog.php">
        <input type="radio" name="catalogFormat" id="catalogFormat" value="0" checked="checked"/><{$smarty.const._BOOKSHOP_PDF_CATALOG1}>
        <br><input type="radio" name="catalogFormat" id="catalogFormat" value="1"/><{$smarty.const._BOOKSHOP_PDF_CATALOG2}>
        <br><input type="submit" name="btnSubmit" id="btnSubmit" value="<{$smarty.const._BOOKSHOP_PDF_GETIT}>"/>
    </form>
<{/if}>

<{* ********************************************* NOTIFICATION ******************************************* *}>
<{include file='db:system_notification_select.tpl'}>
<{* ******************************************** /NOTIFICATION ******************************************* *}>
