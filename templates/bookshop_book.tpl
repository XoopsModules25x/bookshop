<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<{* *********************************************** HEADER ************************************************ *}>
<div id="bookshop-logo">
    <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>bookshop.png" alt="" width="235" height="45"/>

    <{* Display category's advertisement and if it does not exists, the module's advertisement *}>
    <{if $category.cat_advertisement != ''}>
        <div id="bookshop_publicite-category"><{$category.cat_advertisement}></div>
    <{elseif $mod_pref.advertisement != ''}>
        <div id="bookshop_publicite"><{$mod_pref.advertisement}></div>
    <{/if}>
</div>
<{* *********************************************** /HEADER ************************************************ *}>

<{* ********************************************* BREADCRUMB ********************************************** *}>
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

<{* ********************************************* /BREADCRUMB ********************************************** *}>


<{* **************************************** BOOK INFORMATIONS ********************************************* *}>

<table cellspacing="0" width="100%">
    <tr>
        <td class="view-book-shad1_01"></td>
        <td colspan="2" class="view-book-shad1_02"></td>
    </tr>
    <tr>
        <td class="view-book-shad1_03"></td>
        <td class="bookshop_bookdescription">

            <table cellspacing="0">
                <tr>
                    <td colspan="2" class="bookshop_booktitle_view-book"><h2><{$book.book_recommended_picture}><{$book.book_title}></h2></td>
                </tr>
                <tr>
                    <td class="bookshop_bookthumb-big"><{if $book.book_image_url}><img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$book.book_image_url}>" alt="<{$book.book_href_title}>" /><{elseif $book.book_thumb_url != ''}><img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$book.book_thumb_url}>"
                                                                                                                                                                                                                                     alt="<{$book.book_href_title}>" /><{/if}></td>
                    <!--<td class="bookshop_bookssummary">-->
                    <td>
                        <div class=""><{if $book.book_number != ''}><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_NUMBER}></span>: <{$book.book_number}><{/if}> <{if $book.book_tome != ''}> <{$smarty.const._BOOKSHOP_TOME}>: <{$book.book_tome}><{/if}></div>
                        <{if $book_joined_authors != ''}>
                            <div class="bookshop_bookauthor_view-book">
                            <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>author.png" alt="" border="0"/>
                            <span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_BY}></span>
                            <{$book_joined_authors}></div><{/if}>
                        <{if $book_joined_translators != ''}>
                            <div class="bookshop_booktranslators_view-book"><span class="bookshop_bookdescription-contentTitles">
                            <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>author.png" alt="" border="0"/>
                            <{$smarty.const._BOOKSHOP_TRANSLATORS}></span><{$book_joined_translators}></div><{/if}>
                        <!-- Price box -->
                        <div class="bookshop_bookprice_view-book">
                            <div class="bookshop_view-book_price"><{if $book.book_stock > 0 }><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_PRICE}></span>: <a href="<{$smarty.const.BOOKSHOP_URL}>caddy.php?op=addbook&book_id=<{$book.book_id}>"
                                                                                                                                                                                                title="<{$smarty.const._BOOKSHOP_ADD_TO_CART}>"><{if $book.book_discount_price_ttc > 0}>
                                        <s><{$book.book_price_ttc}></s>
                                        <{$book.book_discount_price_ttc}><{else}><{$book.book_price_ttc}><{/if}> <{$mod_pref.money_full}>
                                    <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>cartadd.png" alt=""/>
                                    </a><{else}><{$mod_pref.nostock_msg}><{/if}></div>
                            <div class="bookshop_view-book_shipping-price"><{if $book.book_shipping_price != 0}><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_SHIPPING_PRICE}></span>: <{$book.book_shipping_price}> <{$mod_pref.money_full}><{/if}></div>
                        </div>
                        <!-- /Price box -->
                        <div class="bookshop_bookdate"><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_DATE}></span>: <{$book.book_date}></div>
                        <div class="bookshop_booklangue"><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_LANG}></span>: <{$book.book_language.lang_lang}></div>
                        <{if $book.book_attachment != ''}>
                            <a href="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$book.book_attachment}>" target="_blank"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>attach.gif" alt="" width="9" height="15"/> <{$smarty.const._BOOKSHOP_ATTACHED_FILE}></a>
                        <{/if}>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>

                        <!-- Book summary-->
                        <{if $book.book_summary != ''}>
                            <table width="100%" cellspacing="0">
                                <tr>
                                    <td class="box_blue-clip_01"></td>
                                    <td class="box_blue-clip_02"></td>
                                    <td class="box_blue-clip_03"></td>
                                </tr>
                                <tr>
                                    <td class="box_blue-clip_04"></td>
                                    <td class="bookshop_catdescription">
                                        <div class="bookshop_bookssummary_view-book"><h3><{$smarty.const._BOOKSHOP_SUMMARY}></h3><{$book.book_summary}></div>
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
                        <!-- /Book summary-->

                        <!-- Book description -->
                        <{if $book.book_description != ''}>
                            <table width="100%" cellspacing="0">
                                <tr>
                                    <td class="box_blue-clip_01"></td>
                                    <td class="box_blue-clip_02"></td>
                                    <td class="box_blue-clip_03"></td>
                                </tr>
                                <tr>
                                    <td class="box_blue-clip_04"></td>
                                    <td class="bookshop_catdescription">
                                        <div class="bookshop_description_view-book"><h3><{$smarty.const._BOOKSHOP_DESCRIPTION}></h3><{$book.book_description}></div>
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

                        <{if $book.book_format != '' && $book.book_url != '' && $book.book_isbn != '' && $book.book_pages != 0 && $book.book_pages_collection != 0 && $book.book_volumes_count != 0}>
                            <div class="bookshop_otherinf"><h3><{$smarty.const._BOOKSHOP_OTHER_INFORMATIONS}></h3>
                                <div><{if $book.book_format != ''}><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_FORMAT}></span>: <{$book.book_format}><{/if}></div>
                                <div><{if $book.book_url != ''}><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_SITEURL}></span>: <a href="<{$book.book_url}>" target="_blank"><{$smarty.const._BOOKSHOP_URL}></a><{/if}></div>
                                <div><{if $book.book_isbn != ''}><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_ISBN}></span>: <{$book.book_isbn}><{/if}></div>
                                <div><{if $book.book_ean != ''}><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_EAN}></span>: <{$book.book_ean}><{/if}></div>
                                <div><{if $book.book_pages != 0}><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_PAGES}></span>: <{$book.book_pages}><{/if}></div>
                                <div><{if $book.book_pages_collection != 0}><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_PAGES_COLLECTION}></span>: <{$book.book_pages_collection}><{/if}></div>
                                <div><{if $book.book_volumes_count != 0}><span class="bookshop_bookdescription-contentTitles"><{$smarty.const._BOOKSHOP_VOLUMES}></span>: <{$book.book_volumes_count}><{/if}></div>
                            </div>
                        <{/if}>
                    </td>
                </tr>
            </table>


        </td>
        <td class="view-book-shad2_03"></td>
    </tr>
    <tr>
        <td colspan="2" class="view-book-shad2_02"></td>
        <td class="view-book-shad2_01"></td>
    </tr>
</table>


<{* **************************************** /BOOK INFORMATIONS ********************************************* *}>

<{* ******************************************* RELATED BOOKS *********************************************** *}>
<{if count($book_related_books) > 0}>
    <div id="bookshop_related">
        <h2><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>icon-book-person.png" alt="<{$smarty.const._BOOKSHOP_CART}>" border="0"/><{$smarty.const._BOOKSHOP_RELATED_BOOKS}></h2>

        <table align='center' class="bookshop_categorylist">
            <tr>
                <{foreach item=oneitem from=$book_related_books}>
                <td valign="top" align="center">
                    <div class="bookshop_bookthumb"><a href="<{$oneitem.book_url_rewrited}>" title="<{$oneitem.book_href_title}>"><{if $oneitem.book_thumb_url}><img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$oneitem.book_thumb_url}>" alt="<{$oneitem.book_href_title}>"/></a><{/if}></div>
                    <a href="<{$oneitem.book_url_rewrited}>" title="<{$oneitem.book_href_title}>"><b><{$oneitem.book_title}></b></a>
                    <div class="bookshop_bookprice"><{$smarty.const._BOOKSHOP_PRICE}>: <a href="<{$smarty.const.BOOKSHOP_URL}>caddy.php?op=addbook&book_id=<{$oneitem.book_id}>"
                                                                                          title="<{$smarty.const._BOOKSHOP_ADD_TO_CART}>"><{if $oneitem.book_discount_price_ttc > 0}><{$oneitem.book_discount_price_ttc}><{else}><{$oneitem.book_price_ttc}><{/if}> <{$mod_pref.money_full}> <img
                                    src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>cartadd.png" alt="" border="0"/></a></div>
                </td>
                <{if $oneitem.count is div by 4}>
            </tr>
            <tr>
                <{/if}>
                <{/foreach}>
            </tr>
        </table>
    </div>
<{/if}>
<{* ******************************************* /RELATED BOOKS *********************************************** *}>

<{* ******************************************** OTHER BOOKS ************************************************* *}>
<{if $showprevnextlink  || $summarylast > 0 || $summarycategory > 0 || $better_together > 0}>
    <div id="bookshop_otherbooks">
        <{if $previous_book_id != 0 || $next_book_id != 0}>
            <h2><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>icon-book-person.png" alt="" border="0"/><{$smarty.const._BOOKSHOP_OTHER_BOOKS}></h2>
        <{/if}>
        <{if $previous_book_id != 0}>
            <br>
            <a href="<{$previous_book_url_rewrited}>" title="<{$previous_book_href_title}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>go-previous.png" alt="" border="0"/> <{$smarty.const._BOOKSHOP_PREVIOUS_BOOK}>: <{$previous_book_title}></a>
        <{/if}>

        <{if $next_book_id != 0}>
            <br>
            <a href="<{$next_book_url_rewrited}>" title="<{$next_book_href_title}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>go-next.png" alt="" border="0"/> <{$smarty.const._BOOKSHOP_NEXT_BOOK}>: <{$next_book_title}></a>
        <{/if}>

        <{if $better_together > 0 && $bestwith}>
            <br>
            <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>icon-star.png" alt="" border="0"/>
            <{$smarty.const._BOOKSHOP_BEST_WITH}>
            <a href="<{$bestwith.book_url_rewrited}>" title="<{$bestwith.book_href_title}>"><{$bestwith.book_title}></a>
        <{/if}>

        <{if count($book_all_categs) > 0}>
            <h2><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>icon-book-person.png" alt="" border="0"/><{$smarty.const._BOOKSHOP_RECENT_CATEGS}></h2>
            <table border='0' cellspacing='5' cellpadding='0' align='center' class="bookshop_lastbooks">
                <{foreach item=oneitem from=$book_all_categs}>
                    <tr>
                        <td><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>arrow-black2.png" alt="" border="0" width="13" height="7"/><a href="<{$oneitem.last_categs_book_url_rewrited}>" title="<{$oneitem.last_categs_book_href_title}>"><{$oneitem.last_categs_book_title}></a></td>
                    </tr>
                <{/foreach}>
                </tr>
            </table>
        <{/if}>

        <{if count($book_current_categ) > 0}>
            <h2><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>icon-book-person.png" alt="" border="0"/><{$smarty.const._BOOKSHOP_RECENT_CATEG}></h2>
            <table border='0' cellspacing='5' cellpadding='0' align='center' class="bookshop_lastbooks">
                <{foreach item=oneitem from=$book_current_categ}>
                    <tr>
                        <td><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>arrow-black2.png" alt="" border="0" width="13" height="7"/><a href="<{$oneitem.last_categ_book_url_rewrited}>" title="<{$oneitem.last_categ_book_href_title}>"><{$oneitem.last_categ_book_title}></a></td>
                    </tr>
                <{/foreach}>
                </tr>
            </table>
        <{/if}>
    </div>
<{/if}>
<{* ******************************************** /OTHER BOOKS ************************************************* *}>

<{* ********************************************* CADDY ********************************************** *}>
<div id="bookshop_caddy" align="right">
    <br>
    <a href="<{$smarty.const.BOOKSHOP_URL}>caddy.php" title="<{$smarty.const._BOOKSHOP_CART}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>cart.png" alt="<{$smarty.const._BOOKSHOP_CART}>" border="0"/></a>&nbsp;
    <{if $mod_pref.rss}>
        <a href="<{$smarty.const.BOOKSHOP_URL}>rss.php" title="<{$smarty.const._BOOKSHOP_RSS_FEED}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>rss.gif" alt="<{$smarty.const._BOOKSHOP_RSS_FEED}>" border="0"/></a>
        &nbsp;
    <{/if}>
    <a href="<{$baseurl}>&op=print" rel="nofollow" target="_blank" title="<{$smarty.const._BOOKSHOP_PRINT_VERSION}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>print.png" alt="<{$smarty.const._BOOKSHOP_PRINT_VERSION}>" border="0"/></a>&nbsp;
    <a href="<{$mail_link}>" rel="nofollow" target="_blank" title="<{$smarty.const._BOOKSHOP_TELLAFRIEND}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>email.png" alt="<{$smarty.const._BOOKSHOP_TELLAFRIEND}>" border="0"/></a>&nbsp;
    <{if $mod_pref.isAdmin}><a href="<{$smarty.const.BOOKSHOP_URL}>admin/index.php?op=editbook&id=<{$book.book_id}>" target="_blank" title="<{$smarty.const._EDIT}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>edit.png" alt="<{$smarty.const._EDIT}>"/></a><{/if}>
    <{if $canChangeQuantity}><a href="<{$baseurl}>&stock=add" title="<{$BookStockQuantity}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>plus.gif" alt="<{$BookStockQuantity}>"/></a> <{if $book.book_stock -1 > 0}><a href="<{$baseurl}>&stock=substract" title="<{$BookStockQuantity}>"><img
                src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>minus.gif" alt="<{$BookStockQuantity}>"/></a><{/if}><{/if}>
</div>
<br>
<{* ********************************************* /CADDY ********************************************** *}>

<{* ********************************************* VOTES ********************************************** *}>
<{if $canRateBooks}>
    <div class="bookshop_rating">
        <{$smarty.const._BOOKSHOP_RATINGC}> <{$book.book_rating_formated}> (<{$book.book_votes_count}>)
        <{if $userCanRate}>
            -
            <a href="<{$smarty.const.BOOKSHOP_URL}>rate-book.php?book_id=<{$book.book_id}>" title="<{$smarty.const._BOOKSHOP_RATETHISBOOK}>"><{$smarty.const._BOOKSHOP_RATETHISBOOK}></a>
        <{/if}>
    </div>
<{/if}>
<{* ********************************************* /VOTES ********************************************** *}>


<{* ******************************************** COMMENTS ******************************************* *}>
<div style="text-align: center; padding: 3px; margin:3px;">
    <{$commentsnav}>
    <{$lang_notice}>
</div>

<div style="margin:3px; padding: 3px;">
    <{if $comment_mode == "flat"}>
        <{include file="db:system_comments_flat.tpl"}>
    <{elseif $comment_mode == "thread"}>
        <{include file="db:system_comments_thread.tpl"}>
    <{elseif $comment_mode == "nest"}>
        <{include file="db:system_comments_nest.tpl"}>
    <{/if}>
</div>
<{include file='db:system_notification_select.tpl'}>
<{* ******************************************** /COMMENTS ******************************************* *}>
