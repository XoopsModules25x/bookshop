<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<link href="<{$xoops_url}>/modules/bookshop/assets/css/bookshop.css" rel="stylesheet" type="text/css"/>

<table border="0" class="bookshop_bookindex">
    <{foreach item=book from=$block.block_books}>
        <tr>
            <td class="bookshop_bookthumb"><{if $book.book_thumb_url}><a href="<{$book.book_url_rewrited}>" title="<{$book.book_href_title}>"><img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$book.book_thumb_url}>" alt="<{$book.book_href_title}>" border="0"/></a><{/if}></td>
            <td class="bookshop_bookssummary">
                <table width="100%" cellspacing="0">
                    <tr>
                        <td class="page-curl_01">
                            <div class="bookshop_booktitle"><{$book.book_recommended_picture}><a href="<{$book.book_url_rewrited}>" title="<{$book.book_href_title}>"><{$book.book_title}></a></div>
                            <div class="bookshop_bookauthor"><{if $book.book_joined_authors != ''}><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>author.png" alt="" border="0" /><{$smarty.const._BOOKSHOP_BY}> <{$book.book_joined_authors}><{/if}></div>
                            <div class="bookshop_bookprice"><br><{if $book.book_stock > 0 }><{$smarty.const._BOOKSHOP_PRICE}> <a href="<{$smarty.const.BOOKSHOP_URL}>caddy.php?op=addbook&book_id=<{$book.book_id}>"
                                                                                                                                  title="<{$smarty.const._BOOKSHOP_ADD_TO_CART}>"><{if $book.book_discount_price_ttc > 0}>
                                    <s><{$book.book_price_ttc}></s>
                                    <{$book.book_discount_price_ttc}><{else}><{$book.book_price_ttc}><{/if}> <{$mod_pref.money_full}> <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>cartadd.png" alt="<{$smarty.const._BOOKSHOP_ADD_TO_CART}>" border="0"/>
                                </a><{else}><{$block.nostock_msg}><{/if}></div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    <{/foreach}>
</table>
