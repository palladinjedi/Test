<?php
                                    
        if($page[0]!='' AND $page[0]!='index.html'){
        
        header('HTTP/1.1 404 Not Found');
        header('Location: ' . $config['path_www'] . 'index.html');
        
        die;
        
        }
        
$Tamplate_index = new Templates;
                 
$Tamplate_index ->template_open('modules/' . $config['modules_index'] . '/index.tpl');

$score_day = $Legend ->GetScoreByState(1);
$score_night = $Legend ->GetScoreByState(2);

        if((int)$score_day == (int)$score_night){
        
        $left_column = $config['tmpl_index_dynbg_avg']; 
        
        }elseif((int)$score_day == 0 AND (int)$score_night > 0){
        
        $left_column = (int)$config['tmpl_index_dynbg_min'];
        
        }elseif((int)$score_day > 0 AND (int)$score_night == 0){
        
        $left_column = (int)$config['tmpl_index_dynbg_max'];
        
        }else{
        
        $left_column = $config['tmpl_index_dynbg_min'];
        
        $score_total = (int)$score_day + (int)$score_night;
        
        if((int)$score_total > 0) $score_percent = number_format(((int)$score_day * 100) / (int)$score_total, 0);
        else $score_percent = 50;
        
        for($i=1; $score_percent>=$i AND $i<100; $i=$i+(int)$config['tmpl_index_dynbg_step']) $left_column++;
        
        unset($i);
        
        }
        
$Tamplate_index ->template_set('INDEX_SCORE_LEFT_COLUMN_DYN', $left_column . '%');

$legends_day = $Legend ->Get(FALSE, 1, (int)$config['maxtextinindex'], TRUE, 1, 'timest', 'DESC', 3);

$author = $Auth ->GetUserInfo($legends_day[0]['uid']);

        if((int)$legends_day[0]['uid']>0) {
        
        $Tamplate_index ->template_set('INDEX_LEGENDDAY1_NOT_EXIST_BEGIN', '');
        
        $Tamplate_index ->template_set('INDEX_LEGENDDAY1_NOT_EXIST_END', '');
        
        }else{
        
        $Tamplate_index ->template_set('INDEX_LEGENDDAY1_NOT_EXIST_BEGIN', '<!-- ');

        $Tamplate_index ->template_set('INDEX_LEGENDDAY1_NOT_EXIST_END', ' -->');

        }

$Tamplate_index ->template_set('INDEX_LEGENDDAY1_FIRSTNAME', $author['first_name']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY1_LASTNAME', $author['last_name']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY1_TITLE', $legends_day[0]['title']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY1_TEXT', $legends_day[0]['text']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY1_LINK', $config['path_www'] . 'legend' . '/legend_' . $legends_day[0]['lid'] . '.html');

$author = $Auth ->GetUserInfo($legends_day[1]['uid']);

        if((int)$legends_day[1]['uid']>0) {
        
        $Tamplate_index ->template_set('INDEX_LEGENDDAY2_NOT_EXIST_BEGIN', '');
        
        $Tamplate_index ->template_set('INDEX_LEGENDDAY2_NOT_EXIST_END', '');
        
        }else{
        
        $Tamplate_index ->template_set('INDEX_LEGENDDAY2_NOT_EXIST_BEGIN', '<!-- ');

        $Tamplate_index ->template_set('INDEX_LEGENDDAY2_NOT_EXIST_END', ' -->');

        }

$Tamplate_index ->template_set('INDEX_LEGENDDAY2_FIRSTNAME', $author['first_name']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY2_LASTNAME', $author['last_name']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY2_TITLE', $legends_day[1]['title']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY2_TEXT', $legends_day[1]['text']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY2_LINK', $config['path_www'] . 'legend' . '/legend_' . $legends_day[1]['lid'] . '.html');

$author = $Auth ->GetUserInfo($legends_day[2]['uid']);

        if((int)$legends_day[2]['uid']>0) {
        
        $Tamplate_index ->template_set('INDEX_LEGENDDAY3_NOT_EXIST_BEGIN', '');
        
        $Tamplate_index ->template_set('INDEX_LEGENDDAY3_NOT_EXIST_END', '');
        
        }else{
        
        $Tamplate_index ->template_set('INDEX_LEGENDDAY3_NOT_EXIST_BEGIN', '<!-- ');

        $Tamplate_index ->template_set('INDEX_LEGENDDAY3_NOT_EXIST_END', ' -->');

        }

$Tamplate_index ->template_set('INDEX_LEGENDDAY3_FIRSTNAME', $author['first_name']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY3_LASTNAME', $author['last_name']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY3_TITLE', $legends_day[2]['title']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY3_TEXT', $legends_day[2]['text']);

$Tamplate_index ->template_set('INDEX_LEGENDDAY3_LINK', $config['path_www'] . 'legend' . '/legend_' . $legends_day[2]['lid'] . '.html');

$Tamplate_index ->template_set('LEGENDS_LINK_DAY', $config['path_www'] . 'legend/DayByDate.html');

$Tamplate_index ->template_set('LEGENDS_LINK_NIGHT', $config['path_www'] . 'legend/NightByDate.html');

$legends_night = $Legend ->Get(FALSE, 2, (int)$config['maxtextinindex'], TRUE,  1, 'timest', 'DESC', 3);

$author = $Auth ->GetUserInfo($legends_night[0]['uid']);

        if((int)$legends_night[0]['uid']>0) {
        
        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT1_NOT_EXIST_BEGIN', '');
        
        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT1_NOT_EXIST_END', '');
        
        }else{
        
        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT1_NOT_EXIST_BEGIN', '<!-- ');

        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT1_NOT_EXIST_END', ' -->');

        }

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT1_FIRSTNAME', $author['first_name']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT1_LASTNAME', $author['last_name']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT1_TITLE', $legends_night[0]['title']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT1_TEXT', $legends_night[0]['text']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT1_LINK', $config['path_www'] . 'legend' . '/legend_' . $legends_night[0]['lid'] . '.html');

$author = $Auth ->GetUserInfo($legends_night[1]['uid']);

        if((int)$legends_night[1]['uid']>0) {
        
        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT2_NOT_EXIST_BEGIN', '');
        
        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT2_NOT_EXIST_END', '');
        
        }else{
        
        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT2_NOT_EXIST_BEGIN', '<!-- ');

        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT2_NOT_EXIST_END', ' -->');

        }

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT2_FIRSTNAME', $author['first_name']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT2_LASTNAME', $author['last_name']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT2_TITLE', $legends_night[1]['title']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT2_TEXT', $legends_night[1]['text']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT2_LINK', $config['path_www'] . 'legend' . '/legend_' . $legends_night[1]['lid'] . '.html');

$author = $Auth ->GetUserInfo($legends_night[2]['uid']);

        if((int)$legends_night[2]['uid']>0) {
        
        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT3_NOT_EXIST_BEGIN', '');
        
        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT3_NOT_EXIST_END', '');
        
        }else{
        
        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT3_NOT_EXIST_BEGIN', '<!-- ');

        $Tamplate_index ->template_set('INDEX_LEGENDNIGHT3_NOT_EXIST_END', ' -->');

        }

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT3_FIRSTNAME', $author['first_name']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT3_LASTNAME', $author['last_name']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT3_TITLE', $legends_night[2]['title']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT3_TEXT', $legends_night[2]['text']);

$Tamplate_index ->template_set('INDEX_LEGENDNIGHT3_LINK', $config['path_www'] . 'legend' . '/legend_' . $legends_night[2]['lid'] . '.html');

$Tamplate_index ->template_set('LEGENDS_LINK_DAY', $config['path_www'] . 'legend/DayByDate.html');

$Tamplate_index ->template_set('LEGENDS_LINK_NIGHT', $config['path_www'] . 'legend/NightByDate.html');
                            
$output = $Tamplate_index ->template_show();

$var[]= 'CONTENT';
$con[]= $output;

$var[]= 'TITLE';
$con[]= $config['TITLE'];

$var[]= 'DESCRIPTION';
$con[]= '';

$var[]= 'KEYWORDS';
$con[]= '';

?>
