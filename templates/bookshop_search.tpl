<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<br><br>
<{if $search_results}>
    <h2><{$smarty.const._BOOKSHOP_SEARCHRESULTS}></h2>
    <br>
    <{foreach item=book from=$books}>
        <img src='<{$smarty.const.BOOKSHOP_IMAGES_URL}>book.png' alt='' border='0'/>
        &nbsp;
        <b><a href="<{$book.link}>" title="<{$book.href_title}>"><{$book.title}></a></b>
        <br>
    <{/foreach}>
    <{if $pagenav !=''}>
        <div style="text-align: right; margin: 10px;"><{$pagenav}></div><{/if}>
    <br>
<{/if}>
<{$search_form}>
