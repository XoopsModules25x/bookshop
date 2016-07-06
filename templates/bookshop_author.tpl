<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<div align='center'>
    <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>bookshop.png" alt="" width="235" height="45"/>
    <{if $mod_pref.advertisement != ''}>
        <div id="bookshop_publicite"><{$mod_pref.advertisement}></div><{/if}>

    <{if $author.auth_email != '' }>
        <h2><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>author_32x32.png" alt="" width="32" height="32"/><{$author.auth_type_description}> : <a href="mailto:<{$author.auth_email}>"><{$author.auth_firstname}> <{$author.auth_name}></a></h2>
    <{else}>
        <h2><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>author_32x32.png" alt="" width="32" height="32"/> <{$author.auth_type_description}> : <{$author.auth_firstname}> <{$author.auth_name}></h2>
    <{/if}>

    <{if $author.auth_url != '' }>
        <div class="bookshop_authorurl">
            <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>url.png" alt="" width="16" height="16"/> <{$smarty.const._BOOKSHOP_SITEURL}> : <a href="<{$author.auth_url}>" title="" target="_blank"><{$author.auth_url}></a>
        </div>
    <{/if}>
</div>

<div class="bookshop_authorbio">
    <table width="100%" cellspacing="0">
        <tr>
            <td class="page-curl_01">
                <h3><{$smarty.const._BOOKSHOP_BIO}></h3>
                <{$author.auth_bio}>
                <div class="bookshop_authorphotos">
                    <{if $author.auth_photo1 != '' }><br><img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$author.auth_photo1}>" alt="" border="0" /><{/if}>
                    <{if $author.auth_photo2 != '' }><br><img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$author.auth_photo2}>" alt="" border="0" /><{/if}>
                    <{if $author.auth_photo3 != '' }><br><img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$author.auth_photo3}>" alt="" border="0" /><{/if}>
                    <{if $author.auth_photo4 != '' }><br><img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$author.auth_photo4}>" alt="" border="0" /><{/if}>
                    <{if $author.auth_photo5 != '' }><br><img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$author.auth_photo5}>" alt="" border="0" /><{/if}>
                </div>
            </td>
        </tr>
    </table>
</div>


<div id="bookshop_related">
    <h3><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>icon-book-person.png" alt="" border="0"/><{$smarty.const._MI_BOOKSBYTHISAUTHOR}></h3>
    <table border='0' cellspacing='5' cellpadding='0' align='center' class="bookshop_categorylist">
        <tr>
            <{foreach item=oneitem from=$books}>
            <td valign="top" align="center">
                <{if $oneitem.book_thumb_url}>
                    <div class="bookshop_bookthumb"><a href="<{$oneitem.book_url_rewrited}>" title="<{$oneitem.book_href_title}>"><img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$oneitem.book_thumb_url}>" alt="<{$oneitem.book_href_title}>" border="0"/></a></div><{/if}>
                <br><{$oneitem.book_recommended_picture}><b><{$oneitem.book_title}></b>
                <div class="bookshop_bookprice"><{if $oneitem.book_stock > 0 }><{$smarty.const._BOOKSHOP_PRICE}> : <a href="<{$smarty.const.BOOKSHOP_URL}>caddy.php?op=addbook&book_id=<{$oneitem.book_id}>"
                                                                                                                      title="<{$smarty.const._BOOKSHOP_ADD_TO_CART}>"><{if $oneitem.book_discount_price_ttc > 0}><{$oneitem.book_discount_price_ttc}><{else}><{$oneitem.book_price_ttc}><{/if}> <{$mod_pref.money_full}>
                        <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>cartadd.gif" alt="" border="0"/>
                        </a><{else}><{$mod_pref.nostock_msg}><{/if}></div>
            </td>
            <{if $oneitem.count is div by 4}>
        </tr>
        <tr>
            <{/if}>
            <{/foreach}>
        </tr>
    </table>
</div>
