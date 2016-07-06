<!-- Created by Instant Zero (http://www.instant-zero.com), Design XoopsDesign (http://www.xoopsdesign.com) -->
<br><br><br>

<hr size="1" noshade="noshade"/>
<table border="0" cellpadding="1" cellspacing="0" width="80%" align="center">
    <tr>
        <td>
            <h3><{$book.book_title}></h3>
            <ul>
                <li><{$smarty.const._BOOKSHOP_VOTEONCE}></li>
                <li><{$smarty.const._BOOKSHOP_RATINGSCALE}></li>
                <li><{$smarty.const._BOOKSHOP_BEOBJECTIVE}></li>
                <li><{$smarty.const._BOOKSHOP_DONOTVOTE}></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td align="center">
            <form method="post" action="<{$smarty.const.BOOKSHOP_URL}>rate-book.php">
                <input type="hidden" name="book_id" id="book_id" value="<{$book.book_id}>"/>
                <select name="rating" id="rating">
                    <option value="--">--</option>
                    <option value='10'>10</option>
                    <option value='9'>9</option>
                    <option value='8'>8</option>
                    <option value='7'>7</option>
                    <option value='6'>6</option>
                    <option value='5'>5</option>
                    <option value='4'>4</option>
                    <option value='3'>3</option>
                    <option value='2'>2</option>
                    <option value='1'>1</option>
                </select>&nbsp;&nbsp;
                <input type="submit" name="btnsubmit" id="btnsubmit" value="<{$smarty.const._BOOKSHOP_RATEIT}>"/> <input type='button' value="<{$smarty.const._CANCEL}>" onclick="location='<{$smarty.const.BOOKSHOP_URL}>book.php?book_id=<{$book.book_id}>'"/>
            </form>
        </td>
    </tr>
</table>
