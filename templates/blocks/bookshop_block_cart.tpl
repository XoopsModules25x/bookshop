<!-- Created by Instant Zero (http://www.instant-zero.com) -->
<link href="<{$xoops_url}>/modules/bookshop/assets/css/bookshop.css" rel="stylesheet" type="text/css"/>
<ul>
    <{foreach item=book from=$block.block_caddieProducts}>
        <li><a href="<{$book.book_url_rewrited}>" title="<{$book.book_href_title}>"><{$book.book_title}></a></li>
    <{/foreach}>
</ul>
<br>
<div class="bookshop_carttotal">
    <a href="<{$xoops_url}>/modules/bookshop/caddy.php"><{$smarty.const._BOOKSHOP_TOTAL}> <{$block.block_commandAmountTTC}> <{$block.block_money_short}></a>
</div>
