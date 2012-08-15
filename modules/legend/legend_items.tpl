<li class="text-content-hold">
    <div class="{_LEGEND_ITEM_STATE_DIVCLASS}">
        <span class="title">{_LEGEND_ITEM_STATE}</span>
        <div class="num">{_LEGEND_ITEM_SCORE}</div>
        {_LEGEND_ITEM_VOTEBTN_HIDE_BEGIN}<form method="POST" action="{_PATH}vote/" id="vote{_LID}" name="vote{_LID}"><input type="hidden" name="lid" value="{_LID}"><a href="" onclick="document.forms['vote{_LID}'].submit(); return false;" class="btn{_GUEST}">голосовать</a></form>{_LEGEND_ITEM_VOTEBTN_HIDE_END}
    </div>
    <div class="text">
        <h2><a href="{_LEGEND_ITEM_LINK}">{_LEGEND_ITEM_TITLE}</a> </h2>
        <p>{_LEGEND_ITEM_TEXT}... {_LEGEND_ITEM_LINK_MORE}</p>
        <ul class="autor-info">
            <li><a href="{_LEGEND_ITEM_IDENTITY}" target=_new>{_LEGEND_ITEM_FIRST_NAME} {_LEGEND_ITEM_LAST_NAME}</a> {_LEGEND_ITEM_USER_BAN}</li>
            <li>{_LEGEND_ITEM_TIMEST}</li>{_LEGEND_ITEM_ADMIN_LINKS}
        </ul>
        <div class="soc-row">
            <!-- <span>Поделиться</span> -->
            <table  style='text-align:left'><tr>

                    <td width="96" height="20">
                        <a href="https://twitter.com/share" class="twitter-share-button" data-url="{_LEGEND_ITEM_LINK_ABSOLUTE}" data-lang="ru">Твитнуть</a>
                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    </td>

                    <td width="96" height="20">
                        <a href="http://vk.com/share.php?url={_LEGEND_ITEM_LINK_ABSOLUTE}" target="_new"><img src="{_PATH}images/vk_share.png" alt="" width="46" height="20"></a>
                    </td>

                    <td id="fb" width="96" height="20">
                        <iframe src="//www.facebook.com/plugins/like.php?href={_LEGEND_ITEM_LINK_ABSOLUTE}&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=262300953825491" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>
                    </td>

                </tr></table>
        </div>
    </div>
</li>

