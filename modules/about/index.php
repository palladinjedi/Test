<?php

        if($page[1]!='index.html'){
        
        header('HTTP/1.1 404 Not Found');
        header('Location: ' . $config['path_www']);
        
        die;
        
        }

$Tamplate_about = new Templates;
                 
$Tamplate_about ->template_open('modules/' . $page[0] . '/about.tpl');

$Tamplate_about ->template_set('LEGENDS_LINK_ALL', $config['path_www'] . 'legend/AllByDate.html');
        
$Tamplate_about ->template_set('LEGENDS_LINK_DAY', $config['path_www'] . 'legend/DayByDate.html');
        
$Tamplate_about ->template_set('LEGENDS_LINK_NIGHT', $config['path_www'] . 'legend/NightByDate.html');
        
$Tamplate_about ->template_set('LEGENDS_DIV_STATE_ALL', '');
        
$Tamplate_about ->template_set('LEGENDS_DIV_STATE_DAY', '');
        
$Tamplate_about ->template_set('LEGENDS_DIV_STATE_NIGHT', '');

$output = $Tamplate_about ->template_show();
        
$var[]= 'CONTENT';
$con[]= $output;

$var[]= 'TITLE';
$con[]= 'О проекте - ' . $config['TITLE'];

$var[]= 'DESCRIPTION';
$con[]= '';

$var[]= 'KEYWORDS';
$con[]= '';

?>
