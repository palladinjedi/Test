<?php

$configuration_file = 'engine/conf.php'; // Path to configuration file

/////////// CORE LOAD START ////////////////////////////////////////////////////////////////////////////////////////

if($configuration_file AND file_exists($configuration_file)) require_once $configuration_file;
else die('The configuration file is not defined or does not exist!');
unset($configuration_file);

if($config['dbdriver'] AND file_exists($config['dbdriver'])) require_once $config['dbdriver'];
else die('The DB driver is not defined or does not exist!');

if($config['template_core'] AND file_exists($config['template_core'])) require_once $config['template_core'];
else die('The template core is not defined or does not exist!');

if($config['legend_core'] AND file_exists($config['legend_core'])) require_once $config['legend_core'];
else die('The legend core is not defined or does not exist!');

if($config['auth_core'] AND file_exists($config['auth_core'])) require_once $config['auth_core'];
else die('The auth core is not defined or does not exist!');

/////////// CORE LOAD DONE //////////////////////////////////////////////////////////////////////////////////////////

$DB               = new DB_CORE;
$TEMPLATES_MAIN   = new Templates;
$Legend           = new Legend;
$Auth             = new Auth;

$DB               ->db_init();
$TEMPLATES_MAIN   ->template_open($config['path_phys'] . $config['template_path'] . '/' . $config['template_main_file']);

date_default_timezone_set($config['default_timezone']);

$var = array(); 
$con = array();

if(@getenv('HTTP_X_FORWARDED_FOR')) $ip=@getenv('HTTP_X_FORWARDED_FOR'); 
else $ip=@getenv('REMOTE_ADDR');
            
session_start();

$user = $Auth ->GetUserInfo($_SESSION['uid']);

if($_SESSION['admin_token'] == md5($config['sitekey'].$ip.$_SESSION['admin_session']) AND (int)$_SESSION['admin_session']>time()) $admin = TRUE;
else $admin = FALSE;

$page = htmlentities (trim (strip_tags ($_GET['page'])), ENT_QUOTES | ENT_IGNORE);

$page = explode('/', $page);

if((!isset($page[0]) OR $page[0]=='' OR $page[0]=='index.html') AND file_exists($config['modules_dir'] . '/' . $config['modules_index'] . '/' . $config['modules_runtime'] . '.php')) require $config['modules_dir'] . '/' . $config['modules_index'] . '/' . $config['modules_runtime'] . '.php';
elseif(file_exists($config['modules_dir'] . '/' . $page[0] . '/' . $config['modules_runtime'] . '.php')) require $config['modules_dir'] . '/' . $page[0] . '/' . $config['modules_runtime'] . '.php';
else die('404');

$c = count($var);

for($i=0; $c>$i; $i++) $TEMPLATES_MAIN->template_set($var[$i], $con[$i]);

//////////////////////////////////////////////

if((int)$score_day==0) $score_day = $Legend ->GetScoreByState(1);
if((int)$score_night==0) $score_night = $Legend ->GetScoreByState(2);

$TEMPLATES_MAIN->template_set('SUM_SCORE_DAY', (int)$score_day);
$TEMPLATES_MAIN->template_set('SUM_SCORE_NIGHT', (int)$score_night);

        if($user==FALSE AND $admin==FALSE){
        
        $TEMPLATES_MAIN->template_set('GUEST', '-guest'); 
        
        $TEMPLATES_MAIN->template_set('POPUP_ADD', '');
        
        $TEMPLATES_MAIN->template_set('POPUP_SUCCESS', '');
        
        $TEMPLATES_POPUP_AUTH = new Templates;
        
        $TEMPLATES_POPUP_AUTH ->template_open($config['path_phys'] . $config['template_path'] . '/popup_auth.tpl');
        
                if($page[0]=='' OR $page[0]=='index.html'){ 
                
                $TEMPLATES_POPUP_AUTH ->template_set('FROM', '');
                
                }elseif($page[0]=='about'){
                
                $TEMPLATES_POPUP_AUTH ->template_set('FROM', 'about');
                
                }elseif($page[0]=='legend'){
                
                $TEMPLATES_POPUP_AUTH ->template_set('FROM', 'legend%2F' . $page[1]);
                
                }elseif($page[0]=='admin'){
                
                $TEMPLATES_POPUP_AUTH ->template_set('FROM', 'admin');
                
                }else{
                
                $TEMPLATES_POPUP_AUTH ->template_set('FROM', '');
                
                } 
        
        $TEMPLATES_MAIN->template_set('POPUP_AUTH', $TEMPLATES_POPUP_AUTH ->template_show());
        
        $TEMPLATES_MAIN->template_set('AJAX_SCRIPT_FORM_ADD', '');
        
        $TEMPLATES_MAIN->template_set('AJAX_SCRIPT_FORM_EDIT', '');
        
        }elseif($user==TRUE AND $admin==FALSE){
        
        $TEMPLATES_MAIN->template_set('GUEST', '');
        
        $TEMPLATES_MAIN->template_set('POPUP_AUTH', '');
        
                if($Legend ->LegendExist((int)$user['uid'])==FALSE){
                
                $TEMPLATES_POPUP_ADD = new Templates;
                
                $TEMPLATES_POPUP_ADD ->template_open($config['path_phys'] . $config['template_path'] . '/popup_add.tpl');
                
                $TEMPLATES_POPUP_ADD ->template_set('', '');
                
                $TEMPLATES_MAIN->template_set('POPUP_ADD', $TEMPLATES_POPUP_ADD ->template_show());
                
                $TEMPLATES_POPUP_SUCCESS = new Templates;
                
                $TEMPLATES_POPUP_SUCCESS ->template_open($config['path_phys'] . $config['template_path'] . '/popup_success.tpl');
                
                $TEMPLATES_POPUP_SUCCESS ->template_set('', '');
                
                $TEMPLATES_MAIN->template_set('POPUP_SUCCESS', $TEMPLATES_POPUP_SUCCESS ->template_show());
                
                }else{
                
                $TEMPLATES_POPUP_ADD = new Templates;
                
                $TEMPLATES_POPUP_ADD ->template_open($config['path_phys'] . $config['template_path'] . '/popup_disabled.tpl');
                
                $TEMPLATES_POPUP_ADD ->template_set('', '');
                
                $TEMPLATES_MAIN->template_set('POPUP_ADD', $TEMPLATES_POPUP_ADD ->template_show());
                
                $TEMPLATES_MAIN->template_set('POPUP_SUCCESS', '');
                
                }
        
        $TEMPLATES_AJAX_FORM_ADD = new Templates;
        
        $TEMPLATES_AJAX_FORM_ADD ->template_open($config['path_phys'] . $config['template_path'] . '/ajax_add.tpl');
        
        $TEMPLATES_AJAX_FORM_ADD ->template_set('', '');
        
        $TEMPLATES_MAIN->template_set('AJAX_SCRIPT_FORM_ADD', $TEMPLATES_AJAX_FORM_ADD ->template_show());
        
        $TEMPLATES_MAIN->template_set('AJAX_SCRIPT_FORM_EDIT', '');
        
        }elseif($admin==TRUE){
        
        $TEMPLATES_MAIN->template_set('POPUP_ADD', '');
                
        $TEMPLATES_MAIN->template_set('POPUP_SUCCESS', '');
        
        $TEMPLATES_MAIN->template_set('GUEST', '');
        
        $TEMPLATES_MAIN->template_set('POPUP_AUTH', '');
        
        $TEMPLATES_AJAX_FORM_EDIT = new Templates;
        
        $TEMPLATES_AJAX_FORM_EDIT ->template_open($config['path_phys'] . $config['template_path'] . '/ajax_edit.tpl');
        
        $TEMPLATES_AJAX_FORM_EDIT ->template_set('', '');
        
        $TEMPLATES_MAIN->template_set('AJAX_SCRIPT_FORM_EDIT', $TEMPLATES_AJAX_FORM_EDIT ->template_show());
        
        $TEMPLATES_MAIN->template_set('AJAX_SCRIPT_FORM_ADD', '');
        
        }
//////////////////////////////////////////////

$TEMPLATES_MAIN->template_set('PATH', $config['path_www']);

var_dump($_SESSION);
var_dump($user);
var_dump($uid);

print $TEMPLATES_MAIN->template_show();             

?>
