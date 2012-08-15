<div class="center-box">
    <strong class="logo"><a href="#">Новые Идеи</a></strong>
</div>
</div>
</div>
<div id="main">
    <div id="content">
        <div class="row-link">
            <a href="{_LEGENDS_LINK_ALL}" class="color01{_LEGENDS_DIV_STATE_ALL}"><span>Все легенды</span></a>
            <a href="{_LEGENDS_LINK_DAY}" class="color02{_LEGENDS_DIV_STATE_DAY}"><span>Легенды дня</span></a>
            <a href="{_LEGENDS_LINK_NIGHT}" class="color03{_LEGENDS_DIV_STATE_NIGHT}"><span>Легенды ночи</span></a>
        </div>
        <div class="text-content">
            <h1>{_LEGEND_ITEM_TITLE} </h1>
            <div class="text-content-hold">
                <div class="{_LEGEND_ITEM_STATE_DIVCLASS}">
                    <span class="title">{_LEGEND_ITEM_STATE}</span>
                    <div class="num">{_LEGEND_ITEM_SCORE}</div>
                    {_LEGEND_ITEM_VOTEBTN_HIDE_BEGIN}<form method="POST" action="{_PATH}vote/" id="vote{_LID}" name="vote{_LID}"><input type="hidden" name="lid" value="{_LID}"><a href="" onclick="document.forms['vote{_LID}'].submit(); return false;" class="btn{_GUEST}">голосовать</a></form>{_LEGEND_ITEM_VOTEBTN_HIDE_END}
                </div>
                <div class="text">
                    <p>{_LEGEND_ITEM_TEXT}</p>
                    <ul class="autor-info">
                        <li><a href="{_LEGEND_ITEM_IDENTITY}" target=_new>{_LEGEND_ITEM_FIRST_NAME} {_LEGEND_ITEM_LAST_NAME}</a></li><li>{_LEGEND_ITEM_USER_BAN}</li>
                        <li>{_LEGEND_ITEM_TIMEST}</li>{_LEGEND_ITEM_ADMIN_LINKS}
                    </ul>
                    <div class="soc-row">
                        <!-- <span>Поделиться</span> -->

                        <table><tr>

                                <td><a href="https://twitter.com/share" class="twitter-share-button" data-lang="ru">Твитнуть</a>
                                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></td>

                                <td><script type="text/javascript">
                                    VK.Widgets.Like("vk_like", {type: "mini", height: 18});
                                    </script>
                                    <div id="vk_like"></div></td>

                                <td><div class="fb-like" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true" data-font="arial"></div>
                                </td>

                            </tr></table>
                    </div>













                </div>
            </div>
        </div>
    </div>
    <div id="sidebar" class="sidebar">
        <div class="count">
            <div class="holder">
                <div class="col col-left">
                    <div class="title">Рейтинг дня</div>
                    <div class="num">{_SUM_SCORE_DAY}</div>
                </div>
                <div class="col col-right">
                    <div class="title">Рейтинг ночи</div>
                    <div class="num">{_SUM_SCORE_NIGHT}</div>
                </div>
            </div>
        </div>
        <a class="btn-link{_GUEST}" href="#"><span>Добавить идею</span></a>
        
    </div>
</div>

{_LEGEND_ITEM_ADMIN_EDITFORM}
