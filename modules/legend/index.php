<?php

if (!isset($page[1]) OR $page[1] == '') {
    header('Location: ' . $config['path_www'] . $page[0] . '/AllByDate.html');
    die;
}

$legend_page = explode('.', $page[1]);

if ($legend_page[1] != 'html') {

    header('HTTP/1.1 404 Not Found');
    header('Location: ' . $config['path_www']);

    die;
}

$legend_page = explode('_', $legend_page[0]);

if ($legend_page[0] != 'legend') {

    if ($legend_page[0] == 'AllByDate') {
        $sort = 'timest';
        $state = FALSE;
    } elseif ($legend_page[0] == 'AllByScore') {
        $sort = 'score';
        $state = FALSE;
    } elseif ($legend_page[0] == 'DayByDate') {
        $sort = 'timest';
        $state = 1;
    } elseif ($legend_page[0] == 'DayByScore') {
        $sort = 'score';
        $state = 1;
    } elseif ($legend_page[0] == 'NightByDate') {
        $sort = 'timest';
        $state = 2;
    } elseif ($legend_page[0] == 'NightByScore') {
        $sort = 'score';
        $state = 2;
    } else {
        header('HTTP/1.1 404 Not Found');
        header('Location: ' . $config['path_www']);
        die;
    }

    if ($admin == TRUE)
        $public = FALSE;
    else
        $public = TRUE;

    if (isset($legend_page[1]) AND (int) $legend_page[1] == 0) {
        header('HTTP/1.1 404 Not Found');
        header('Location: ' . $config['path_www']);
        die;
    } elseif (!isset($legend_page[1])) {
        $legend_items = $Legend->Get(FALSE, $state, (int) $config['maxtextinlist'], $public, 1, $sort, 'DESC', (int) $config['legendsinlist']);
    } elseif ((int) $legend_page[1] > 0) {
        $legend_items = $Legend->Get(FALSE, $state, (int) $config['maxtextinlist'], $public, (int) $legend_page[1], $sort, 'DESC', (int) $config['legendsinlist']);
    } else {
        header('HTTP/1.1 404 Not Found');
        header('Location: ' . $config['path_www']);
        die;
    }

    if ($legend_items == FALSE) {
        header('HTTP/1.1 404 Not Found');
        header('Location: ' . $config['path_www']);
        die;
    }

    $Tamplate_legend_list = new Templates;

    $Tamplate_legend_list->template_open('modules/' . $page[0] . '/legend_list.tpl');

    foreach ($legend_items as $legend_item) {

        $author = $Auth->GetUserInfo((int) $legend_item['uid']);

        $Tamplate_legend_items = new Templates;

        $Tamplate_legend_items->template_open('modules/' . $page[0] . '/legend_items.tpl');

        $Tamplate_legend_items->template_set('LEGEND_ITEM_TITLE', $legend_item['title']);

        $Tamplate_legend_items->template_set('LEGEND_ITEM_TEXT', $legend_item['text']);

        $Tamplate_legend_items->template_set('LID', $legend_item['lid']);

        if ((int) $legend_item['state'] == 1) {

            $Tamplate_legend_items->template_set('LEGEND_ITEM_STATE', $config['LEGEND_ITEM_STATE_DAY']);
            $Tamplate_legend_items->template_set('LEGEND_ITEM_STATE_DIVCLASS', $config['LEGEND_ITEM_STATE_DIVCLASS_DAY']);
        } elseif ((int) $legend_item['state'] == 2) {

            $Tamplate_legend_items->template_set('LEGEND_ITEM_STATE', $config['LEGEND_ITEM_STATE_NIGHT']);
            $Tamplate_legend_items->template_set('LEGEND_ITEM_STATE_DIVCLASS', $config['LEGEND_ITEM_STATE_DIVCLASS_NIGHT']);
        } else {

            $Tamplate_legend_items->template_set('LEGEND_ITEM_STATE', '');
            $Tamplate_legend_items->template_set('LEGEND_ITEM_STATE_DIVCLASS', '');
        }

        $Tamplate_legend_items->template_set('LEGEND_ITEM_SCORE', $legend_item['score']);

        $timest = time();

        $time_end_vote = (int) $legend_item['timest'] + (int) $config['vote_end_after'];

        $vote_exist = $DB->NumRows($config['dbprefix'] . 'vote', '*', "uid=" . (int) $user['uid'] . " AND lid=" . (int) $legend_item['lid']);

        if ((int) $time_end_vote < (int) $timest OR (int) $legend_item['uid'] == $user['uid'] OR (int) $vote_exist > 0) {

            $Tamplate_legend_items->template_set('LEGEND_ITEM_VOTEBTN_HIDE_BEGIN', '<!-- ');

            $Tamplate_legend_items->template_set('LEGEND_ITEM_VOTEBTN_HIDE_END', ' -->');
        } else {

            $Tamplate_legend_items->template_set('LEGEND_ITEM_VOTEBTN_HIDE_BEGIN', '');

            $Tamplate_legend_items->template_set('LEGEND_ITEM_VOTEBTN_HIDE_END', '');
        }


        if ($admin == TRUE) {

            if ((bool) $author['ban'] == TRUE) {

                $Tamplate_legend_items->template_set('LEGEND_ITEM_FIRST_NAME', '<s>' . $author['first_name']);

                $Tamplate_legend_items->template_set('LEGEND_ITEM_LAST_NAME', $author['last_name'] . '</s>');

                $Tamplate_legend_items->template_set('LEGEND_ITEM_USER_BAN', '<b>[забанен]</b>');
            } else {

                $Tamplate_legend_items->template_set('LEGEND_ITEM_FIRST_NAME', $author['first_name']);

                $Tamplate_legend_items->template_set('LEGEND_ITEM_LAST_NAME', $author['last_name']);

                $Tamplate_legend_items->template_set('LEGEND_ITEM_USER_BAN', '');
            }
        } else {

            $Tamplate_legend_items->template_set('LEGEND_ITEM_FIRST_NAME', $author['first_name']);

            $Tamplate_legend_items->template_set('LEGEND_ITEM_LAST_NAME', $author['last_name']);

            $Tamplate_legend_items->template_set('LEGEND_ITEM_USER_BAN', '');
        }

        $Tamplate_legend_items->template_set('LEGEND_ITEM_IDENTITY', $author['identity']);

        $Tamplate_legend_items->template_set('LEGEND_ITEM_TIMEST', gmdate('j', (int) $legend_item['timest'] + date('Z')) . ' ' . $config['month'][gmdate('n', (int) $legend_item['timest'] + date('Z'))] . ', ' . gmdate('H:i', (int) $legend_item['timest'] + date('Z')));

        $Tamplate_legend_items->template_set('LEGEND_ITEM_LINK', $config['path_www'] . $page[0] . '/legend_' . $legend_item['lid'] . '.html');

        if ((int) $legend_item['strlen'] > (int) $config['maxtextinlist'])
            $Tamplate_legend_items->template_set('LEGEND_ITEM_LINK_MORE', '<a href="' . $config['path_www'] . $page[0] . '/legend_' . $legend_item['lid'] . '.html" class="read">читать дальше</a>');
        else
            $Tamplate_legend_items->template_set('LEGEND_ITEM_LINK_MORE', '');

        $Tamplate_legend_items->template_set('LEGEND_ITEM_LINK_ABSOLUTE', 'http://' . $_SERVER['HTTP_HOST'] . $config['path_www'] . $page[0] . '/legend_' . $legend_item['lid'] . '.html');

        if ($admin == TRUE) {

            if ($legend_item['public'] == TRUE)
                $Tamplate_legend_items->template_set('LEGEND_ITEM_ADMIN_LINKS', '<li><b>опубликовано</b></li>');
            else
                $Tamplate_legend_items->template_set('LEGEND_ITEM_ADMIN_LINKS', '<li><b>скрыто</b></li>');
        }else {

            $Tamplate_legend_items->template_set('LEGEND_ITEM_ADMIN_LINKS', '');
        }

        $legends_list .= $Tamplate_legend_items->template_show();
    }

    $Tamplate_legend_list->template_set('LEGENDS_ITEMS', $legends_list);

    if ($state == FALSE) {
        $Tamplate_legend_list->template_set('LEGENDS_DIV_STATE_ALL', ' active');
        $var[] = 'TITLE';
        $con[] = 'Все легенды - ' . $config['TITLE'];
    } else {
        $Tamplate_legend_list->template_set('LEGENDS_DIV_STATE_ALL', '');
    }

    if ((int) $state == 1) {
        $Tamplate_legend_list->template_set('LEGENDS_DIV_STATE_DAY', ' active');
        $var[] = 'TITLE';
        $con[] = 'Легенды дня - ' . $config['TITLE'];
    } else {
        $Tamplate_legend_list->template_set('LEGENDS_DIV_STATE_DAY', '');
    }

    if ((int) $state == 2) {
        $Tamplate_legend_list->template_set('LEGENDS_DIV_STATE_NIGHT', ' active');
        $var[] = 'TITLE';
        $con[] = 'Легенды ночи - ' . $config['TITLE'];
    } else {
        $Tamplate_legend_list->template_set('LEGENDS_DIV_STATE_NIGHT', '');
    }

    if ((int) $state == FALSE AND !isset($legend_page[1])) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYDATE', $config['path_www'] . $page[0] . '/AllByDate.html');
    } elseif ((int) $state == 1 AND !isset($legend_page[1])) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYDATE', $config['path_www'] . $page[0] . '/DayByDate.html');
    } elseif ((int) $state == 2 AND !isset($legend_page[1])) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYDATE', $config['path_www'] . $page[0] . '/NightByDate.html');
    } elseif ($state == FALSE AND (int) $legend_page[1] > 0) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYDATE', $config['path_www'] . $page[0] . '/AllByDate_' . (int) $legend_page[1] . '.html');
    } elseif ((int) $state == 1 AND (int) $legend_page[1] > 0) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYDATE', $config['path_www'] . $page[0] . '/DayByDate_' . (int) $legend_page[1] . '.html');
    } elseif ((int) $state == 2 AND (int) $legend_page[1] > 0) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYDATE', $config['path_www'] . $page[0] . '/NightByDate_' . (int) $legend_page[1] . '.html');
    } else {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYDATE', $config['path_www'] . $page[0] . '/AllByDate.html');
    }

    if ((int) $state == FALSE AND !isset($legend_page[1])) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYSCORE', $config['path_www'] . $page[0] . '/AllByScore.html');
    } elseif ((int) $state == 1 AND !isset($legend_page[1])) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYSCORE', $config['path_www'] . $page[0] . '/DayByScore.html');
    } elseif ((int) $state == 2 AND !isset($legend_page[1])) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYSCORE', $config['path_www'] . $page[0] . '/NightByScore.html');
    } elseif ($state == FALSE AND (int) $legend_page[1] > 0) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYSCORE', $config['path_www'] . $page[0] . '/AllByScore_' . (int) $legend_page[1] . '.html');
    } elseif ((int) $state == 1 AND (int) $legend_page[1] > 0) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYSCORE', $config['path_www'] . $page[0] . '/DayByScore_' . (int) $legend_page[1] . '.html');
    } elseif ((int) $state == 2 AND (int) $legend_page[1] > 0) {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYSCORE', $config['path_www'] . $page[0] . '/NightScore_' . (int) $legend_page[1] . '.html');
    } else {
        $Tamplate_legend_list->template_set('LAGENDS_SORTBYSCORE', $config['path_www'] . $page[0] . '/AllByScore.html');
    }

    if ($sort == 'timest') {

        $Tamplate_legend_list->template_set('LEGENDS_SORTBYCURENT_TIMEST', '');
        $Tamplate_legend_list->template_set('LEGENDS_SORTBYCURENT_SCORE', $config['LEGEND_LIST_SORT_CURRENT_CLASS']);
    } elseif ($sort == 'score') {

        $Tamplate_legend_list->template_set('LEGENDS_SORTBYCURENT_TIMEST', $config['LEGEND_LIST_SORT_CURRENT_CLASS']);
        $Tamplate_legend_list->template_set('LEGENDS_SORTBYCURENT_SCORE', '');
    } else {

        $Tamplate_legend_list->template_set('LEGENDS_SORTBYCURENT_TIMEST', '');
        $Tamplate_legend_list->template_set('LEGENDS_SORTBYCURENT_SCORE', '');
    }

    if ($admin == TRUE) {

        if ((int) $state == FALSE) {
            $Tamplate_legend_list->template_set('LAGENDS_ADMIN_RECOUNT', '<li><form method="POST" action="' . $config['path_www'] . 'admin/" id="legend_recount_all" name="legend_recount_all"><input type="hidden" name="recount_all" value="TRUE"><a href="" onclick="if (confirm(\'Пересчитать рейтинг всех легенд?\')) document.forms[\'legend_recount_all\'].submit(); return false;">Пересчитать рейтинг всех легенд</a></form></li>');
        } elseif ((int) $state == 1) {
            $Tamplate_legend_list->template_set('LAGENDS_ADMIN_RECOUNT', '<li><form method="POST" action="' . $config['path_www'] . 'admin/" id="legend_recount_day" name="legend_recount_day"><input type="hidden" name="recount_state" value="' . (int) $state . '"><a href="" onclick="if (confirm(\'Пересчитать рейтинг легенд дня?\')) document.forms[\'legend_recount_day\'].submit(); return false;">Пересчитать рейтинг легенд дня</a></form></li>');
        } elseif ((int) $state == 2) {
            $Tamplate_legend_list->template_set('LAGENDS_ADMIN_RECOUNT', '<li><form method="POST" action="' . $config['path_www'] . 'admin/" id="legend_recount_night" name="legend_recount_night"><input type="hidden" name="recount_state" value="' . (int) $state . '"><a href="" onclick="if (confirm(\'Пересчитать рейтинг легенд ночи?\')) document.forms[\'legend_recount_night\'].submit(); return false;">Пересчитать рейтинг легенд ночи</a></form></li>');
        } else {
            $Tamplate_legend_list->template_set('LAGENDS_ADMIN_RECOUNT', '<li><form method="POST" action="' . $config['path_www'] . 'admin/" id="legend_recount_all" name="legend_recount_all"><input type="hidden" name="recount_all" value="TRUE"><a href="" onclick="if (confirm(\'Пересчитать рейтинг всех легенд?\')) document.forms[\'legend_recount_all\'].submit(); return false;">Пересчитать рейтинг всех легенд</a></form></li>');
        }
    } else {

        $Tamplate_legend_list->template_set('LAGENDS_ADMIN_RECOUNT', '');
    }

    $Tamplate_legend_list->template_set('LEGENDS_LINK_ALL', $config['path_www'] . $page[0] . '/AllByDate.html');
    $Tamplate_legend_list->template_set('LEGENDS_LINK_DAY', $config['path_www'] . $page[0] . '/DayByDate.html');
    $Tamplate_legend_list->template_set('LEGENDS_LINK_NIGHT', $config['path_www'] . $page[0] . '/NightByDate.html');

    if ($admin == TRUE)
        $c = $Legend->GetNumLegents($state, FALSE);
    else
        $c = $Legend->GetNumLegents($state, TRUE);

    if ((int) $legend_page[1] == 0)
        $p = 1;
    else
        $p = (int) $legend_page[1];

    $pages_num = @floor((int) $c / (int) $config['legendsinlist']);
    if (((int) $pages_num * (int) $config['legendsinlist']) < (int) $c)
        (int) $pages_num++;

    if ((int) $state == 0 AND (!isset($sort) OR $sort == 'timest')) {

        if ($p == 1) {

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/AllByDate.html');

            if ($pages_num > $p) {

                $next_page = $p + 1;

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/AllByDate_' . $next_page . '.html');
            } elseif ($pages_num == 1) {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/AllByDate.html');
            } else {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/AllByDate_' . $p . '.html');
            }
        } else {

            $page_prev = $p - 1;

            if ($page_prev > 1)
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $page[0] . '/AllByDate_' . $page_prev . '.html');
            else
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/AllByDate.html');

            if ($p < $pages_num)
                $next_page = $p + 1;
            else
                $next_page = $p;

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/AllByDate_' . $p . '.html');
        }
    }elseif ((int) $state == 1 AND (!isset($sort) OR $sort == 'timest')) {

        if ($p == 1) {

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/DayByDate.html');

            if ($pages_num > $p) {

                $next_page = $p + 1;

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/DayByDate_' . $next_page . '.html');
            } elseif ($pages_num == 1) {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/DayByDate.html');
            } else {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/DayByDate_' . $p . '.html');
            }
        } else {

            $page_prev = $p - 1;

            if ($page_prev > 1)
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $page[0] . '/DayByDate_' . $page_prev . '.html');
            else
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/DayByDate.html');

            if ($p < $pages_num)
                $next_page = $p + 1;
            else
                $next_page = $p;

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/DayByDate_' . $p . '.html');
        }
    }elseif ((int) $state == 2 AND (!isset($sort) OR $sort == 'timest')) {

        if ($p == 1) {

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/NightByDate.html');

            if ($pages_num > $p) {

                $next_page = $p + 1;

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/NightByDate_' . $next_page . '.html');
            } elseif ($pages_num == 1) {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/NightByDate.html');
            } else {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/NightByDate_' . $p . '.html');
            }
        } else {

            $page_prev = $p - 1;

            if ($page_prev > 1)
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $page[0] . '/NightByDate_' . $page_prev . '.html');
            else
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/NightByDate.html');

            if ($p < $pages_num)
                $next_page = $p + 1;
            else
                $next_page = $p;

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/NightByDate_' . $p . '.html');
        }
    }elseif ((int) $state == 0 AND $sort == 'score') {

        if ($p == 1) {

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/AllByScore.html');

            if ($pages_num > $p) {

                $next_page = $p + 1;

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/AllByScore_' . $next_page . '.html');
            } elseif ($pages_num == 1) {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/AllByScore.html');
            } else {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/AllByScore_' . $p . '.html');
            }
        } else {

            $page_prev = $p - 1;

            if ($page_prev > 1)
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $page[0] . '/AllByScore_' . $page_prev . '.html');
            else
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/AllByScore.html');

            if ($p < $pages_num)
                $next_page = $p + 1;
            else
                $next_page = $p;

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/AllByScore_' . $p . '.html');
        }
    }elseif ((int) $state == 1 AND $sort == 'score') {

        if ($p == 1) {

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/DayByScore.html');

            if ($pages_num > $p) {

                $next_page = $p + 1;

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/DayByScore_' . $next_page . '.html');
            } elseif ($pages_num == 1) {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/DayByScore.html');
            } else {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/DayByScore_' . $p . '.html');
            }
        } else {

            $page_prev = $p - 1;

            if ($page_prev > 1)
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $page[0] . '/DayByScore' . $page_prev . '.html');
            else
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/DayByScore.html');

            if ($p < $pages_num)
                $next_page = $p + 1;
            else
                $next_page = $p;

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/DayByScore_' . $p . '.html');
        }
    }elseif ((int) $state == 2 AND $sort == 'score') {

        if ($p == 1) {

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/NightByScore.html');

            if ($pages_num > $p) {

                $next_page = $p + 1;

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/NightByScore_' . $next_page . '.html');
            } elseif ($pages_num == 1) {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/NightByScore.html');
            } else {

                $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/NightByScore_' . $p . '.html');
            }

            if ($p < $pages_num)
                $next_page = $p + 1;
            else
                $next_page = $p;

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/NightByScore_' . $p . '.html');
        }else {

            $page_prev = $p - 1;

            if ($page_prev > 1)
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $page[0] . '/NightByScore_' . $page_prev . '.html');
            else
                $Tamplate_legend_list->template_set('LEGENDS_PAGES_PREV', $config['path_www'] . $page[0] . '/NightByScore.html');

            if ($p < $pages_num)
                $next_page = $p + 1;
            else
                $next_page = $p;

            $Tamplate_legend_list->template_set('LEGENDS_PAGES_NEXT', $config['path_www'] . $page[0] . '/NightByScore_' . $p . '.html');
        }
    }

    $str_nav = '';

    if ((int) $pages_num > 4 AND $p > 4) {

        if ((int) $state == 0 AND (!isset($sort) OR $sort == 'timest')) {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByDate.html">1</a></li>' . "\r\n";
        } elseif ((int) $state == 1 AND (!isset($sort) OR $sort == 'timest')) {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/DayByDate.html">1</a></li>' . "\r\n";
        } elseif ((int) $state == 2 AND (!isset($sort) OR $sort == 'timest')) {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/NightByDate.html">1</a></li>' . "\r\n";
        } elseif ((int) $state == 0 AND $sort == 'score') {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByScore.html">1</a></li>' . "\r\n";
        } elseif ((int) $state == 1 AND $sort == 'score') {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/DayByScore.html">1</a></li>' . "\r\n";
        } elseif ((int) $state == 2 AND $sort == 'score') {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/NightByScore.html">1</a></li>' . "\r\n";
        } else {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByDate.html">1</a></li>' . "\r\n";
        }
    }

    if ((int) $pages_num > 4 AND $p > 5)
        $str_nav.='<li class="inner">...</li>' . "\r\n";

    for ($i = 1; $i <= $pages_num; $i++) {

        if ($i < $p AND $i >= $p - 3 AND $i > 1) {

            if ((int) $state == 0 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 1 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/DayByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 2 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/NightByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 0 AND $sort == 'score') {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByScore_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 1 AND $sort == 'score') {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/DayByScore_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 2 AND $sort == 'score') {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/NightByScore_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } else {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            }
        } elseif ($i == $p AND $i > 1) {

            if ((int) $state == 0 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/AllByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 1 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/DayByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 2 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/NightByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 0 AND $sort == 'score') {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/AllByScore_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 1 AND $sort == 'score') {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/DayByScore_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 2 AND $sort == 'score') {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/NightByScore_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } else {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/AllByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            }
        } elseif ($i > $p AND $i <= $p + 3 AND $i > 1) {

            if ((int) $state == 0 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 1 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/DayByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 2 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/NightByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 0 AND $sort == 'score') {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByScore_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 1 AND $sort == 'score') {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/DayByScore_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } elseif ((int) $state == 2 AND $sort == 'score') {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/NightByScore_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            } else {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByDate_' . $i . '.html">' . $i . '</a></li>' . "\r\n";
            }
        } elseif ($i == 1 AND $p != 1) {

            if ((int) $state == 0 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByDate.html">1</a></li>' . "\r\n";
            } elseif ((int) $state == 1 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/DayByDate.html">1</a></li>' . "\r\n";
            } elseif ((int) $state == 2 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/NightByDate.html">1</a></li>' . "\r\n";
            } elseif ((int) $state == 0 AND $sort == 'score') {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByScore.html">1</a></li>' . "\r\n";
            } elseif ((int) $state == 1 AND $sort == 'score') {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/DayByScore.html">1</a></li>' . "\r\n";
            } elseif ((int) $state == 2 AND $sort == 'score') {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/NightByScore.html">1</a></li>' . "\r\n";
            } else {
                $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByDate.html">1</a></li>' . "\r\n";
            }
        } elseif ($i == 1 AND $p == 1) {

            if ((int) $state == 0 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/AllByDate.html">1</a></li>' . "\r\n";
            } elseif ((int) $state == 1 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/DayByDate.html">1</a></li>' . "\r\n";
            } elseif ((int) $state == 2 AND (!isset($sort) OR $sort == 'timest')) {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/NightByDate.html">1</a></li>' . "\r\n";
            } elseif ((int) $state == 0 AND $sort == 'score') {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/AllByScore.html">1</a></li>' . "\r\n";
            } elseif ((int) $state == 1 AND $sort == 'score') {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/DayByScore.html">1</a></li>' . "\r\n";
            } elseif ((int) $state == 2 AND $sort == 'score') {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/NightByScore.html">1</a></li>' . "\r\n";
            } else {
                $str_nav.='<li class="active"><a href="' . $config['path_www'] . $page[0] . '/AllByDate.html">1</a></li>' . "\r\n";
            }
        }
    }


    if ($pages_num - 4 >= $p) {

        $str_nav.='<li class="inner">...</li>' . "\r\n";

        if ((int) $state == 0 AND (!isset($sort) OR $sort == 'timest')) {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByDate_' . $pages_num . '.html">' . $pages_num . '</a></li>' . "\r\n";
        } elseif ((int) $state == 1 AND (!isset($sort) OR $sort == 'timest')) {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/DayByDate_' . $pages_num . '.html">' . $pages_num . '</a></li>' . "\r\n";
        } elseif ((int) $state == 2 AND (!isset($sort) OR $sort == 'timest')) {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/NightByDate_' . $pages_num . '.html">' . $pages_num . '</a></li>' . "\r\n";
        } elseif ((int) $state == 0 AND $sort == 'score') {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByScore_' . $pages_num . '.html">' . $pages_num . '</a></li>' . "\r\n";
        } elseif ((int) $state == 1 AND $sort == 'score') {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/DayByScore_' . $pages_num . '.html">' . $pages_num . '</a></li>' . "\r\n";
        } elseif ((int) $state == 2 AND $sort == 'score') {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/NightByScore_' . $pages_num . '.html">' . $pages_num . '</a></li>' . "\r\n";
        } else {
            $str_nav.='<li><a href="' . $config['path_www'] . $page[0] . '/AllByDate_' . $pages_num . '.html">' . $pages_num . '</a></li>' . "\r\n";
        }
    }

    $Tamplate_legend_list->template_set('LEGENDS_PAGES_NAV', $str_nav);

    $output = $Tamplate_legend_list->template_show();
} else {

    if ((int) $legend_page[1] > 0 AND strlen((int) $legend_page[1]) == strlen($legend_page[1])) {

        if ($admin == TRUE)
            $legend_item = $Legend->Get((int) $legend_page[1], FALSE, FALSE, FALSE);
        else
            $legend_item = $Legend->Get((int) $legend_page[1], FALSE, FALSE, TRUE);

        if ((int) $legend_item[0]['uid'] > 0) {

            $author = $Auth->GetUserInfo((int) $legend_item[0]['uid']);
        } else {

            header('HTTP/1.1 404 Not Found');
            header('Location: ' . $config['path_www']);

            die;
        }
    } else {

        header('HTTP/1.1 404 Not Found');
        header('Location: ' . $config['path_www']);

        die;
    }

    $Tamplate_legend_item = new Templates;

    $Tamplate_legend_item->template_open('modules/' . $page[0] . '/legend_item.tpl');

    $Tamplate_legend_item->template_set('LEGEND_ITEM_TITLE', $legend_item[0]['title']);

    $Tamplate_legend_item->template_set('LEGEND_ITEM_TEXT', preg_replace('/\n/', "</br>", $legend_item[0]['text']));

    $Tamplate_legend_item->template_set('LID', $legend_item[0]['lid']);

    $var[] = 'TITLE';

    $con[] = $legend_item[0]['title'] . ' - ' . $config['TITLE'];

    if ($admin == TRUE) {

        if ($legend_item[0]['public'] == TRUE)
            $Tamplate_legend_item->template_set('LEGEND_ITEM_ADMIN_LINKS', '<li><form method="POST" action="' . $config['path_www'] . 'admin/" id="legend_unpublic" name="legend_unpublic"><input type="hidden" name="unpub_lid" value="' . (int) $legend_item[0]['lid'] . '"><a href="" onclick="if (confirm(\'Отменить публикацию?\')) document.forms[\'legend_unpublic\'].submit(); return false;"><b>скрыть</b></a></form></li><li><a href="#" class="btn-link-edit">редакт.</a></li><li><form method="POST" action="' . $config['path_www'] . 'admin/" id="legend_delete" name="legend_delete"><input type="hidden" name="del_lid" value="' . (int) $legend_item[0]['lid'] . '"><a href="" onclick="if (confirm(\'Точно удалить?\')) document.forms[\'legend_delete\'].submit(); return false;">удалить</a></form></li><li><form method="POST" action="' . $config['path_www'] . 'admin/" id="legend_recount" name="legend_recount"><input type="hidden" name="recount_lid" value="' . (int) $legend_item[0]['lid'] . '"><a href="" onclick="if (confirm(\'Пересчитать рейтинг?\')) document.forms[\'legend_recount\'].submit(); return false;">пересчитать</a></form></li>');
        else
            $Tamplate_legend_item->template_set('LEGEND_ITEM_ADMIN_LINKS', '<li><form method="POST" action="' . $config['path_www'] . 'admin/" id="legend_public" name="legend_public"><input type="hidden" name="pub_lid" value="' . (int) $legend_item[0]['lid'] . '"><a href="" onclick="if (confirm(\'Опубликовать?\')) document.forms[\'legend_public\'].submit(); return false;"><b>показать</b></a></form></li><li><a href="#" class="btn-link-edit">редакт.</a></li><li><form method="POST" action="' . $config['path_www'] . 'admin/" id="legend_delete" name="legend_delete"><input type="hidden" name="del_lid" value="' . (int) $legend_item[0]['lid'] . '"><a href="" onclick="if (confirm(\'Точно удалить?\')) document.forms[\'legend_delete\'].submit(); return false;">удалить</a></form></li><li><form method="POST" action="' . $config['path_www'] . 'admin/" id="legend_recount" name="legend_recount"><input type="hidden" name="recount_lid" value="' . (int) $legend_item[0]['lid'] . '"><a href="" onclick="if (confirm(\'Пересчитать рейтинг?\')) document.forms[\'legend_recount\'].submit(); return false;">пересчитать</a></form></li>');
    }else {

        $Tamplate_legend_item->template_set('LEGEND_ITEM_ADMIN_LINKS', '');
    }

    if ((int) $legend_item[0]['state'] == 1) {

        $Tamplate_legend_item->template_set('LEGEND_ITEM_STATE', $config['LEGEND_ITEM_STATE_DAY']);
        $Tamplate_legend_item->template_set('LEGEND_ITEM_STATE_DIVCLASS', $config['LEGEND_ITEM_STATE_DIVCLASS_DAY']);
    } elseif ((int) $legend_item[0]['state'] == 2) {

        $Tamplate_legend_item->template_set('LEGEND_ITEM_STATE', $config['LEGEND_ITEM_STATE_NIGHT']);
        $Tamplate_legend_item->template_set('LEGEND_ITEM_STATE_DIVCLASS', $config['LEGEND_ITEM_STATE_DIVCLASS_NIGHT']);
    } else {

        $Tamplate_legend_item->template_set('LEGEND_ITEM_STATE', '');
    }

    $Tamplate_legend_item->template_set('LEGEND_ITEM_SCORE', $legend_item[0]['score']);

    $timest = time();

    $time_end_vote = (int) $legend_item[0]['timest'] + (int) $config['vote_end_after'];

    $vote_exist = $DB->NumRows($config['dbprefix'] . 'vote', '*', "uid=" . (int) $user['uid'] . " AND lid=" . (int) $legend_item[0]['lid']);

    if ((int) $time_end_vote < (int) $timest OR (int) $legend_item[0]['uid'] == $user['uid'] OR (int) $vote_exist > 0) {

        $Tamplate_legend_item->template_set('LEGEND_ITEM_VOTEBTN_HIDE_BEGIN', '<!-- ');

        $Tamplate_legend_item->template_set('LEGEND_ITEM_VOTEBTN_HIDE_END', ' -->');
    } else {

        $Tamplate_legend_item->template_set('LEGEND_ITEM_VOTEBTN_HIDE_BEGIN', '');

        $Tamplate_legend_item->template_set('LEGEND_ITEM_VOTEBTN_HIDE_END', '');
    }

    if ($admin == TRUE) {

        if ((bool) $author['ban'] == TRUE) {

            $Tamplate_legend_item->template_set('LEGEND_ITEM_FIRST_NAME', '<s>' . $author['first_name']);

            $Tamplate_legend_item->template_set('LEGEND_ITEM_LAST_NAME', $author['last_name'] . '</s>');

            $Tamplate_legend_item->template_set('LEGEND_ITEM_USER_BAN', '<form method="POST" action="' . $config['path_www'] . 'admin/" id="user_unban" name="user_unban"><input type="hidden" name="userunban" value="' . (int) $author['uid'] . '"><a href="" onclick="if (confirm(\'Точно разбанить?\')) document.forms[\'user_unban\'].submit(); return false;"><b>[забанен]</b></a></form>');
        } else {

            $Tamplate_legend_item->template_set('LEGEND_ITEM_FIRST_NAME', $author['first_name']);

            $Tamplate_legend_item->template_set('LEGEND_ITEM_LAST_NAME', $author['last_name']);

            $Tamplate_legend_item->template_set('LEGEND_ITEM_USER_BAN', '<form method="POST" action="' . $config['path_www'] . 'admin/" id="user_ban" name="user_ban"><input type="hidden" name="userban" value="' . (int) $author['uid'] . '"><a href="" onclick="if (confirm(\'Точно забанить?\')) document.forms[\'user_ban\'].submit(); return false;"><b>[забанить]</b></a></form>');
        }

        $Tamplate_legend_item_admin_editform = new Templates;

        $Tamplate_legend_item_admin_editform->template_open('modules/' . $page[0] . '/popup_edit.tpl');

        if ((int) $legend_item[0]['state'] == 1) {

            $Tamplate_legend_item_admin_editform->template_set('LEGEND_ITEM_ADMIN_EDITFORM_STATE_DAY', ' active');

            $Tamplate_legend_item_admin_editform->template_set('LEGEND_ITEM_ADMIN_EDITFORM_STATE_NIGHT', '');
        } elseif ((int) $legend_item[0]['state'] == 2) {

            $Tamplate_legend_item_admin_editform->template_set('LEGEND_ITEM_ADMIN_EDITFORM_STATE_DAY', '');

            $Tamplate_legend_item_admin_editform->template_set('LEGEND_ITEM_ADMIN_EDITFORM_STATE_NIGHT', ' active');
        } else {

            $Tamplate_legend_item_admin_editform->template_set('LEGEND_ITEM_ADMIN_EDITFORM_STATE_DAY', '');

            $Tamplate_legend_item_admin_editform->template_set('LEGEND_ITEM_ADMIN_EDITFORM_STATE_NIGHT', '');
        }

        $Tamplate_legend_item_admin_editform->template_set('LEGEND_ITEM_ADMIN_EDITFORM_TITLE', $legend_item[0]['title']);

        $Tamplate_legend_item_admin_editform->template_set('LEGEND_ITEM_ADMIN_EDITFORM_TEXT', $legend_item[0]['text']);

        $Tamplate_legend_item_admin_editform->template_set('LEGEND_ITEM_ADMIN_EDITFORM_LID', (int) $legend_item[0]['lid']);

        $Tamplate_legend_item->template_set('LEGEND_ITEM_ADMIN_EDITFORM', $Tamplate_legend_item_admin_editform->template_show());
    } else {

        $Tamplate_legend_item->template_set('LEGEND_ITEM_FIRST_NAME', $author['first_name']);

        $Tamplate_legend_item->template_set('LEGEND_ITEM_LAST_NAME', $author['last_name']);

        $Tamplate_legend_item->template_set('LEGEND_ITEM_USER_BAN', '');

        $Tamplate_legend_item->template_set('LEGEND_ITEM_ADMIN_EDITFORM', '');
    }

    $Tamplate_legend_item->template_set('LEGEND_ITEM_IDENTITY', $author['identity']);

    $Tamplate_legend_item->template_set('LEGEND_ITEM_TIMEST', gmdate('j', (int) $legend_item[0]['timest'] + date('Z')) . ' ' . $config['month'][gmdate('n', (int) $legend_item[0]['timest'] + date('Z'))] . ', ' . gmdate('H:i', (int) $legend_item[0]['timest'] + date('Z')));

    $Tamplate_legend_item->template_set('LEGENDS_LINK_ALL', $config['path_www'] . $page[0] . '/AllByDate.html');

    $Tamplate_legend_item->template_set('LEGENDS_LINK_DAY', $config['path_www'] . $page[0] . '/DayByDate.html');

    $Tamplate_legend_item->template_set('LEGENDS_LINK_NIGHT', $config['path_www'] . $page[0] . '/NightByDate.html');

    if ($legend_item[0]['state'] == FALSE)
        $Tamplate_legend_item->template_set('LEGENDS_DIV_STATE_ALL', ' active');
    else
        $Tamplate_legend_item->template_set('LEGENDS_DIV_STATE_ALL', '');

    if ((int) $legend_item[0]['state'] == 1)
        $Tamplate_legend_item->template_set('LEGENDS_DIV_STATE_DAY', ' active');
    else
        $Tamplate_legend_item->template_set('LEGENDS_DIV_STATE_DAY', '');

    if ((int) $legend_item[0]['state'] == 2)
        $Tamplate_legend_item->template_set('LEGENDS_DIV_STATE_NIGHT', ' active');
    else
        $Tamplate_legend_item->template_set('LEGENDS_DIV_STATE_NIGHT', '');

    $output = $Tamplate_legend_item->template_show();
}

$var[] = 'CONTENT';
$con[] = $output;

$var[] = 'DESCRIPTION';
$con[] = '';

$var[] = 'KEYWORDS';
$con[] = '';
?>
