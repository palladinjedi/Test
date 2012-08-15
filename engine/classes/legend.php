<?php

class Legend {

    function Get($lid = FALSE, $state = FALSE, $short = FALSE, $public = TRUE, $pg = 1, $sort = 'lid', $position = 'DESC', $num_legends = 10) {
        global $DB, $config;

        if ((int) $pg < 1)
            $pg = 1;

        if ((int) $lid == 0) {

            (int) $max = (int) $pg * (int) $num_legends;
            (int) $min = (int) $max - (int) $num_legends;

            if ((int) $state > 0) {

                if ((bool) $public == TRUE)
                    $legends = $DB->GetValues($config['dbprefix'] . 'legend', '*', 'public=TRUE AND state=' . (int) $state, '', $sort, $position, (int) $min, (int) $max);
                else
                    $legends = $DB->GetValues($config['dbprefix'] . 'legend', '*', 'state=' . (int) $state, '', $sort, $position, (int) $min, (int) $max);
            }
            else {

                if ((bool) $public == TRUE)
                    $legends = $DB->GetValues($config['dbprefix'] . 'legend', '*', 'public=TRUE', '', $sort, $position, (int) $min, (int) $max);
                else
                    $legends = $DB->GetValues($config['dbprefix'] . 'legend', '*', '', '', $sort, $position, (int) $min, (int) $max);
            }
        }
        else {

            if ((bool) $public == TRUE)
                $legends = $DB->GetValues($config['dbprefix'] . 'legend', '*', "public=TRUE AND lid='" . (int) $lid . "'");
            else
                $legends = $DB->GetValues($config['dbprefix'] . 'legend', '*', "lid='" . (int) $lid . "'");

            if (count($legends) == 0)
                return FALSE;
        }

        for ($i = 0; $i < count($legends); $i++) {

            //$legends[$i]['title'] = iconv("UTF-8", "WINDOWS-1251", $legends[$i]['title']);
            //$legends[$i]['text'] = iconv("UTF-8", "WINDOWS-1251", $legends[$i]['text']);

            $legends[$i]['strlen'] = (int) strlen($legends[$i]['text']);

            if ((int) $short > 0 and $legends[$i]['strlen'] > (int) $short)
                $legends[$i]['text'] = substr($legends[$i]['text'] = wordwrap($legends[$i]['text'], $short, '[STREND]'), 0, strpos($legends[$i]['text'], '[STREND]'));
        }

        return $legends;
    }

    function doAdd($uid = FALSE, $title = '', $text = '', $state = FALSE, $public = FALSE) {
        global $DB, $config, $ip;

        if ((int) $uid == 0){
            return FALSE;
        }

        $legend_exist = $DB->NumRows($config['dbprefix'] . 'legend', '*', "uid=" . (int) $uid);

        if ((int) $legend_exist > 0){
            return FALSE;
        }

        $title = substr(trim(strip_tags($title)), 0, (int) $config['maxtitle']);

        $text = substr(trim(strip_tags($text)), 0, (int) $config['maxtext']);

        if (strlen($title) < (int) $config['mintitle'] OR strlen($text) < (int) $config['mintext'])
            return FALSE;

        $timest = time();

        /*if ((int) $state == 0){
            return FALSE;
        }*/

        if ($public == FALSE){
            $DB->Insert($config['dbprefix'] . 'legend', "'', " . $uid . ", '" . $title . "', '" . $text . "', '', " . (int) $state . ", 0, '" . $timest . "', '" . $ip . "'");
        }
        else{
            $DB->Insert($config['dbprefix'] . 'legend', "'', " . $uid . ", '" . $title . "', '" . $text . "', '', " . (int) $state . ", 1, '" . $timest . "', '" . $ip . "'");
        }

        $lid = $DB->GetValues($config['dbprefix'] . 'legend', 'lid', "uid=" . (int) $uid . " AND timest='" . (int) $timest . "' AND last_ip='" . $ip . "'", '', 'lid', 'DESC');

        if ((int) $lid[0]['lid'] > 0){
            return (int) $lid[0]['lid'];
        }
        else{
            return FALSE;
        }
    }

    function LegendExist($uid = FALSE) {
        global $DB, $config;

        $legend_exist = $DB->NumRows($config['dbprefix'] . 'legend', '*', "uid=" . (int) $uid);

        if ((int) $legend_exist > 0)
            return TRUE;
        else
            return FALSE;
    }

    function doVote($uid = FALSE, $lid = FALSE) {
        global $DB, $config, $ip;

        if ((int) $uid == 0 OR (int) $lid == 0)
            return FALSE;

        $vote_exist = $DB->NumRows($config['dbprefix'] . 'vote', '*', "uid=" . (int) $uid . " AND lid=" . (int) $lid);

        if ((int) $vote_exist == 0) {

            $legend = $DB->GetValues($config['dbprefix'] . 'legend', '*', "lid=" . (int) $lid . " AND public=1");

            if ((int) $legend[0]['lid'] == 0)
                return FALSE;

            if ((int) $legend[0]['uid'] == (int) $uid)
                return FALSE;

            $timest = time();

            $time_end_vote = (int) $legend[0]['timest'] + (int) $config['vote_end_after'];

            if ((int) $time_end_vote < (int) $timest)
                return FALSE;

            $DB->Insert($config['dbprefix'] . 'vote', "'', " . (int) $uid . ", '" . (int) $legend[0]['lid'] . "', '" . (int) $timest . "', '" . $ip . "'");

            $DB->Update($config['dbprefix'] . 'legend', "`score`=`score`+1", "lid=" . (int) $legend[0]['lid']);

            if ((int) $legend[0]['score'] > 0)
                return (int) $legend[0]['score'];
            else
                return FALSE;
        }
        else {

            return FALSE;
        }
    }

    function doSet($lid, $title = '', $text = '', $state = FALSE) {
        global $DB, $config;

        if ((int) $lid == 0 OR (int) $state == 0)
            return FALSE;

        $title = substr(trim(strip_tags($title)), 0, (int) $config['maxtitle']);

        $text = substr(trim(strip_tags($text)), 0, (int) $config['maxtext']);

        if ((int) strlen($title) == 0 OR (int) strlen($text) == 0)
            return FALSE;

        $timest = time();

        $DB->Update($config['dbprefix'] . 'legend', "`title`='" . $title . "', `text`='" . $text . "', `state`=" . (int) $state, "lid=" . (int) $lid);

        return TRUE;
    }

    function doDelete($lid) {
        global $DB, $config;

        if ((int) $lid == 0)
            return FALSE;

        $DB->Delete($config['dbprefix'] . 'legend', "lid=" . (int) $lid);

        return TRUE;
    }

    function doPublic($lid) {
        global $DB, $config;

        if ((int) $lid == 0)
            return FALSE;

        $DB->Update($config['dbprefix'] . 'legend', "`public`=1", "lid=" . (int) $lid);

        return TRUE;
    }

    function doUnPublic($lid) {
        global $DB, $config;

        if ((int) $lid == 0)
            return FALSE;

        $DB->Update($config['dbprefix'] . 'legend', "`public`=0", "lid=" . (int) $lid);

        return TRUE;
    }

    function doRecount($lid = FALSE) {
        global $DB, $config;

        if ((int) $lid == 0) {

            $legend_list = $DB->GetValues($config['dbprefix'] . 'legend', 'lid');

            foreach ($legend_list as $legend_item) {

                $vote_count = $DB->NumRows($config['dbprefix'] . 'vote', '*', "lid=" . (int) $legend_item['lid']);

                $DB->Update($config['dbprefix'] . 'legend', "score=" . (int) $vote_count, "lid=" . (int) $legend_item['lid']);
            }

            return TRUE;
        } else {

            $legend_exist = $DB->NumRows($config['dbprefix'] . 'legend', '*', "lid=" . (int) $lid);

            if ((int) $legend_exist == 1)
                $vote_count = $DB->NumRows($config['dbprefix'] . 'vote', '*', "lid=" . (int) $lid);
            else
                die('Data incorrect!  ERROR: LRECOUNTLID <a href="javascript:history.go(-1);"><<Back</a>');

            $DB->Update($config['dbprefix'] . 'legend', "score=" . (int) $vote_count, "lid=" . (int) $lid);

            return (int) $vote_count;
        }
    }

    function GetScoreByState($state = FALSE) {
        global $DB, $config;

        if ((int) $state == 0)
            return FALSE;
        else
            $score = $DB->Select($config['dbprefix'] . 'legend', 'SUM(score)', "state=" . (int) $state . " AND public=1");

        return $DB->Show();
    }

    function GetNumLegents($state = FALSE, $public = TRUE) {
        global $DB, $config;

        if ($public == TRUE) {

            if ((int) $state == 0)
                $count = $DB->Select($config['dbprefix'] . 'legend', 'COUNT(*)', 'public=1');
            else
                $count = $DB->Select($config['dbprefix'] . 'legend', 'COUNT(*)', 'public=1 AND state=' . (int) $state);
        }else {

            if ((int) $state == 0)
                $count = $DB->Select($config['dbprefix'] . 'legend', 'COUNT(*)');
            else
                $count = $DB->Select($config['dbprefix'] . 'legend', 'COUNT(*)', 'state=' . (int) $state);
        }

        return (int) $DB->Show();
    }

}

?>
