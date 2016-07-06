<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<div align='center'>
    <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>bookshop.png" alt="" width="235" height="45"/>
    <{if $mod_pref.advertisement != ''}>
        <div id="bookshop_publicite"><{$mod_pref.advertisement}></div><{/if}>
</div>
<h2><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>icon-book-person.png" alt="" border="0"/><{$smarty.const._MI_BOOKSHOP_SMNAME5}></h2>
<div class="bookshop_alphabet"><{foreach item=letter from=$alphabet}><a href="#<{$letter}>"><{$letter}></a> <{/foreach}></div>

<table border="0" cellpadding="0" cellspacing="0" class="bookshop_whoswho">
    <{foreach key=initiale item=donnees from=$authors}>
        </tr>
        <td colspan="2">

            <table width="100%" cellspacing="0">
                <tr>
                    <td class="box_blue-clip_01"></td>
                    <td class="box_blue-clip_02"></td>
                    <td class="box_blue-clip_03"></td>
                </tr>
                <tr>
                    <td class="box_blue-clip_04"></td>
                    <td class="bookshop_catdescription">
                        <table width="100%" cellspacing="0">
                            <tr>
                                <td align="center" width="40%"><h3><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>author.png" alt="" border="0"/> <{$smarty.const._BOOKSHOP_AUTHOR}></h3></td>
                                <td align="center" width="20%">
                                    <div class="bookshop_lettrine"><a name="<{$initiale}>"><span class="bookshop_lettrine-L"><{$initiale}></span></a></div>
                                </td>
                                <td align="center" width="40%"><h3><img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>author.png" alt="" border="0"/> <{$smarty.const._BOOKSHOP_TRANSLATOR}></h3></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="bookshop_listauthors">
                                    <div>
                                        <{foreach item=autor from=$donnees.Author}>
                                            <br>
                                            <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>arrow-black2.png" alt="" border="0" width="13"  height="7" /><{$autor.author_url_rewrited}>
                                        <{/foreach}>            </div>
                                </td>
                                <td class="bookshop_listauthors">
                                    <div>
                                        <{foreach item=translator from=$donnees.Translator}>
                                            <br>
                                            <img src="<{$smarty.const.BOOKSHOP_IMAGES_URL}>arrow-black2.png" alt="" border="0" width="13"  height="7" /><{$translator.author_url_rewrited}>
                                        <{/foreach}>            </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="box_blue-clip_05"></td>
                </tr>
                <tr>
                    <td class="box_blue-clip_06"></td>
                    <td class="box_blue-clip_07"></td>
                    <td class="box_blue-clip_08"></td>
                </tr>
            </table>
        </td>
        </tr>
    <{/foreach}>
</table>
