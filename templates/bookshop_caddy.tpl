<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<div align="center"><h2><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>add-to-basket.png" alt="" border="0"/><{$smarty.const._MI_BOOKSHOP_SMNAME1}></h2></div>

<{if $emptyCart}>
    <i><{$smarty.const._BOOKSHOP_CART_IS_EMPTY}></i>
<{else}>
    <form method="post" name="frmUpdate" id="frmUpdate" action="<{$smarty.const.BOOKSHOP_URL}>caddy.php" style="margin:0; padding:0; border: 0; display: inline;">
        <table cellspacing="0" id="bookshop_caddy">
            <tr>
                <th><span class="bookshop_caddy-titles"><{$smarty.const._BOOKSHOP_ITEMS}></span></th>
                <th><span class="bookshop_caddy-titles"><{$smarty.const._BOOKSHOP_UNIT_PRICE}></span></th>
                <th><span class="bookshop_caddy-titles"><{$smarty.const._BOOKSHOP_UNIT_PRICE2}></span></th>
                <th><span class="bookshop_caddy-titles"><{$smarty.const._BOOKSHOP_QUANTITY}></span></th>
                <th><span class="bookshop_caddy-titles"><{$smarty.const._BOOKSHOP_CART1}></span></th>
                <th><span class="bookshop_caddy-titles"><{$smarty.const._BOOKSHOP_CART2}></span></th>
                <th><span class="bookshop_caddy-titles"><{$smarty.const._BOOKSHOP_CART3}></th>
                <th><span class="bookshop_caddy-titles"><{$smarty.const._BOOKSHOP_SHIPPING_PRICE}></span></th>
                <th><span class="bookshop_caddy-titles"><{$smarty.const._BOOKSHOP_PRICE}></span></th>
            </tr>
            <{foreach item=book from=$caddieProducts}>
                <tr>
                    <td>
                        <div class="bookshop_booktitle"><a href="<{$book.book_url_rewrited}>" title="<{$book.book_href_title}>"><{$book.book_title}></a></div>
                        <div class="bookshop_bookauthor"><{if $book.book_joined_authors != ''}><{$smarty.const._BOOKSHOP_BY}> <{$book.book_joined_authors}><{/if}></div>
                    </td>
                    <td>
                        <div class="bookshop_bookprice" align="right"><{$book.book_price_normal_ht}>&nbsp;<{$mod_pref.money_short}></div>
                    </td>
                    <td>
                        <div class="bookshop_bookprice" align="right"><{$book.book_price_discounted_ht}>&nbsp;<{$mod_pref.money_short}></div>
                    </td>
                    <td align="center"><input type="text" name="qty_<{$book.book_number}>" id="qty_<{$book.book_number}>" value="<{$book.book_qty}>" size="3"/></td>
                    <td>
                        <div class="bookshop_bookprice" align="right"><{$book.book_price_ht}>&nbsp;<{$mod_pref.money_short}></div>
                    </td>
                    <td><{$book.book_vat_rate}></td>
                    <td>
                        <div class="bookshop_bookprice" align="right"><{$book.book_vat_amount}>&nbsp;<{$mod_pref.money_short}></div>
                    </td>
                    <td>
                        <div class="bookshop_bookprice" align="right"><{$book.book_shipping_amount}>&nbsp;<{$mod_pref.money_short}></div>
                    </td>
                    <td>
                        <div class="bookshop_bookprice" align="right"><{$book.book_price_ttc}>&nbsp;<{$mod_pref.money_short}></div>
                    </td>
                    <td><a href="<{$smarty.const.BOOKSHOP_URL}>caddy.php?op=delete&book_id=<{$book.book_number}>" <{$confirm_delete_book}> title="<{$smarty.const._BOOKSHOP_REMOVE_ITEM}>"><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>cartdelete.png" alt="<{$smarty.const._BOOKSHOP_REMOVE_ITEM}>"
                                                                                                                                                                                                border="0"/></td>
                </tr>
            <{/foreach}>
            <tr class="bookshop_carttotal">
                <td colspan="4"><h3><{$smarty.const._BOOKSHOP_TOTAL}></h3></td>
                <td align="right" valign="middle"><{$commandAmount}> <{$mod_pref.money_short}></td>
                <td>&nbsp;</td>
                <td align="right" valign="middle"><{$vatAmount}> <{$mod_pref.money_short}></td>
                <td align="right" valign="middle"><{$shippingAmount}> <{$mod_pref.money_short}></td>
                <td colspan="2" align="right" valign="middle"><{$commandAmountTTC}> <{$mod_pref.money_full}></td>
            </tr>
            <tr>
                <td colspan="8">
                    <{$smarty.const._BOOKSHOP_QTE_MODIFIED}>
                    <input type="hidden" name="op" id="op" value="update"/>
                    <input type="submit" name="btnUpdate" id="btnUpdate" value="<{$smarty.const._BOOKSHOP_UPDATE}>"/>
    </form>
    <form method="post" name="frmEmpty" id="frmEmpty" action="<{$smarty.const.BOOKSHOP_URL}>caddy.php" <{$confEmpty}> style="margin:0; padding:0; border: 0; display: inline;">
        <input type="hidden" name="op" id="op" value="empty"/>
        <input type="submit" name="btnEmpty" id="btnEmpty" value="<{$smarty.const._BOOKSHOP_EMPTY_CART}>"/>
    </form>
    <form method="post" name="frmGoOn" id="frmGoOn" action="<{$goOn}>" style="margin:0; padding:0; border: 0; display: inline;">
        <input type="submit" name="btnGoOn" id="btnGoOn" value="<{$smarty.const._BOOKSHOP_GO_ON}>"/>
    </form>
    </td>
    <td colspan="2" align="center">
        <form method="post" name="frmCheckout" id="frmCheckout" action="<{$smarty.const.BOOKSHOP_URL}>checkout.php" style="margin:0; padding:0; border: 0; display: inline;">
            <input type="submit" name="btnCheckout" id="btnCheckout" value="<{$smarty.const._BOOKSHOP_CHECKOUT}>"/>
        </form>
    </td>
    </tr>
    </table>

    <{if count($discountsDescription) > 0}>
        <div class="bookshop_discounts">
            <h3><{$smarty.const._BOOKSHOP_CART4}></h3>
            <ul>
                <{foreach item=discount from=$discountsDescription}>
                    <li class="bookshop_discount-description"><{$discount}></li>
                <{/foreach}>
            </ul>
        </div>
    <{/if}>
<{/if}>
