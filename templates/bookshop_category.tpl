<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<{* *********************************************** HEADER ************************************************ *}>
<div id="bookshop-logo">
    <{if $category.cat_imgurl != ''}>
        <img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$category.cat_imgurl}>" alt="<{$category.cat_title}>"/>
    <{else}>
        <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>bookshop.png" alt="" width="235" height="45"/>
    <{/if}>
</div>
<div>
    <{* Display category's advertisement and if it does not exists, the module's advertisement *}>
    <{if $category.cat_advertisement != ''}>
        <div id="bookshop_publicite-category"><{$category.cat_advertisement}></div>
    <{elseif $mod_pref.advertisement != ''}>
        <div id="bookshop_publicite"><{$mod_pref.advertisement}></div>
    <{/if}>

    <{if $category.cat_title != ''}>
        <table width="100%" cellspacing="0">
            <tr>
                <td class="box_blue-clip_01"></td>
                <td class="box_blue-clip_02"></td>
                <td class="box_blue-clip_03"></td>
            </tr>
            <tr>
                <td class="box_blue-clip_04"></td>
                <td class="bookshop_catdescription">

                    <h2><{$category.cat_title}></h2>
                    <{if $category.cat_description != ''}><{$category.cat_description}><{/if}>

                </td>
                <td class="box_blue-clip_05"></td>
            </tr>
            <tr>
                <td class="box_blue-clip_06"></td>
                <td class="box_blue-clip_07"></td>
                <td class="box_blue-clip_08"></td>
            </tr>
        </table>
    <{/if}>
</div>
<{* *********************************************** /HEADER ************************************************ *}>

<{if $case == 1}> <{* We are on a main category (without parents) or we are on the main category page, so we are going to display chunks *}>
    <{if count($chunk1) > 0 || count($chunk2) > 0 || count($chunk3) > 0 || count($chunk4) > 0}>
        <{if count($chunk1) > 0}>
            <{include file="db:bookshop_chunk.tpl" books=$chunk1 title=$chunk1Title}>
        <{/if}>
        <{if count($chunk2) > 0}>
            <{include file="db:bookshop_chunk.tpl" books=$chunk2 title=$chunk2Title}>
        <{/if}>
        <{if count($chunk3) > 0}>
            <{include file="db:bookshop_chunk.tpl" books=$chunk3 title=$chunk3Title}>
        <{/if}>
        <{if count($chunk4) > 0}>
            <{include file="db:bookshop_chunk.tpl" books=$chunk4 title=$chunk4Title}>
        <{/if}>
    <{else}>
        <h2><{$smarty.const._BOOKSHOP_SORRY_NO_BOOK}></h2>
    <{/if}>
<{else}>    <{* We are on a child category so we display its books *}>
    <!-- Breadcrumb -->
    <table cellspacing="0" class="breadcrumbT">
        <tr>
            <td class="breadcrumb_01"></td>
            <td class="breadcrumb_02">
                <div id="bookshoop_breadcrumb"><{$breadcrumb}></div>
            </td>
            <td class="breadcrumb_03"></td>
        </tr>
        <tr>
            <td colspan="3" class="red-line"></td>
        </tr>
    </table>
    <!-- /Breadcrumb -->
    <{if count($books) > 0}>
        <{if $pagenav !=''}>
            <div style="text-align: right; margin: 10px;"><{$pagenav}></div><{/if}>
        <table border="0" class="bookshop_bookindex">
            <{foreach item=book from=$books}>
                <tr>
                    <td class="bookshop_bookthumb"><{if $book.book_thumb_url}><a href="<{$book.book_url_rewrited}>" title="<{$book.book_href_title}>"><img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$book.book_thumb_url}>" alt="<{$book.book_href_title}>" border="0"/></a><{/if}></td>
                    <td class="bookshop_bookssummary">

                        <table width="100%" cellspacing="0">
                            <tr>
                                <td class="page-curl_01">
                                    <div class="bookshop_booktitle"><{$book.book_recommended_picture}><a href="<{$book.book_url_rewrited}>" title="<{$book.book_href_title}>"><{$book.book_title}></a></div>
                                    <div class="bookshop_bookauthor"><{if $book.book_joined_authors != ''}><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>author.png" alt="" border="0" /><{$smarty.const._BOOKSHOP_BY}> <{$book.book_joined_authors}><{/if}></div>
                                    <div class="bookshop_bookprice"><{if $book.book_stock > 0 }><{$smarty.const._BOOKSHOP_PRICE}> <a href="<{$smarty.const.BOOKSHOP_URL}>caddy.php?op=addbook&book_id=<{$book.book_id}>"
                                                                                                                                     title="<{$smarty.const._BOOKSHOP_ADD_TO_CART}>"><{if $book.book_discount_price_ttc > 0}>
                                                <s><{$book.book_price_ttc}></s>
                                                <{$book.book_discount_price_ttc}><{else}><{$book.book_price_ttc}><{/if}> <{$mod_pref.money_full}>
                                            <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>cartadd.png" alt="<{$smarty.const._BOOKSHOP_ADD_TO_CART}>" border="0"/>
                                            </a><{else}><{$mod_pref.nostock_msg}><{/if}></div>
                                    <div class="bookshop_description"><{$book.book_summary}></div>
                                    <div class="bookshop_read-more"><a href="<{$book.book_url_rewrited}>" title="<{$smarty.const._BOOKSHOP_READ_MORE}> <{$book.book_href_title}>"><{$smarty.const._BOOKSHOP_READ_MORE}></a></div>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
            <{/foreach}>
        </table>
        <{if $pagenav !=''}>
            <div style="text-align: left; margin: 10px;"><{$pagenav}></div><{/if}>
    <{else}>
        <h2><{$smarty.const._BOOKSHOP_SORRY_NO_BOOK}></h2>
    <{/if}>
<{/if}>
<{* **************************************** CADDY & RSS ****************************************** *}>
<div id="bookshop_caddy" align="right">
    <a href="<{$smarty.const.BOOKSHOP_URL}>caddy.php" title="<{$smarty.const._BOOKSHOP_CART}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>cart.png" alt="<{$smarty.const._BOOKSHOP_CART}>" border="0"/></a>&nbsp;
    <{if $mod_pref.rss}>
        <a href="<{$smarty.const.BOOKSHOP_URL}>rss.php<{if $category.cat_cid > 0}>?cat_cid=<{$category.cat_cid}><{/if}>" title="<{$smarty.const._BOOKSHOP_RSS_FEED}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>rss.gif" alt="<{$smarty.const._BOOKSHOP_RSS_FEED}>" border="0"/></a>
    <{/if}>
</div>
<{* **************************************** /CADDY & RSS ****************************************** *}>

<{* ********************************************* NOTIFICATION ******************************************* *}>
<{include file='db:system_notification_select.tpl'}>
<{* ******************************************** /NOTIFICATION ******************************************* *}>
