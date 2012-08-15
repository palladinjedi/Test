<div class="center-box">
    <strong class="logo"><a href="{_PATH}index.html">Новые Идеи</a></strong>
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
            <ul class="sort-sub">
                <li {_LEGENDS_SORTBYCURENT_TIMEST}><a href="{_LAGENDS_SORTBYDATE}">По дате публикации</a></li>
                <li {_LEGENDS_SORTBYCURENT_SCORE}><a href="{_LAGENDS_SORTBYSCORE}">По рейтингу</a></li>
                {_LAGENDS_ADMIN_RECOUNT}
            </ul>
            <ul class="sort-list">

                {_LEGENDS_ITEMS}
            </ul>
            <div class="paging">
                <a href="#" class="btn-link{_GUEST}"><span>Добавить идею</span></a>
                <a href="{_LEGENDS_PAGES_PREV}" class="prev">Назад</a>
                <ul>
                    {_LEGENDS_PAGES_NAV}
                </ul>
                <a href="{_LEGENDS_PAGES_NEXT}" class="next">Вперед</a>
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

