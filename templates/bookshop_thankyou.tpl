<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<div id="bookshop-logo">
    <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>bookshop.png" width="235" height="45" alt="" border="0"/>
</div>
<br>
<{if $success }>
    <h3><{$smarty.const._BOOKSHOP_THANK_YOU}></h3>
    <br>
    <br>
    <h4><{$smarty.const._BOOKSHOP_TRANSACTION_FINSIHED}></h4>
<{else}>
    <h3><{$smarty.const._BOOKSHOP_PAYPAL_FAILED}></h3>
<{/if}>
<br>
<br>
<a href="<{$smarty.const.BOOKSHOP_URL}>"><{$smarty.const._BOOKSHOP_CONTINUE_SHOPPING}></a>
