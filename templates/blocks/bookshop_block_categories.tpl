<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<{if $block.block_option == 0 }>
    <ul>
        <{if $block.block_current_category}>
            <li><{$block.block_current_category.cat_title}></li>
        <{/if}>
        <{foreach item=onecategory from=$block.block_categories}>
            <li><{$onecategory}></li>
        <{/foreach}>
    </ul>
<{else}>
    <div style="text-align: center;">
        <form name="categoryForm" id="categoryForm" action="<{$smarty.const.BOOKSHOP_URL}>category.php" method="get"><{$block.htmlSelect}></form>
    </div>
<{/if}>
