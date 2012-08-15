<?php

            if((int)$_POST['lid']>0 AND (int)$_SESSION['uid']>0) $user = $Auth ->GetUserInfo((int)$_SESSION['uid']);
            
                    if($user==TRUE AND $user['ban']==FALSE) {
                    
                    $Legend ->doVote((int)$user['uid'], (int)$_POST['lid']);
                    
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    
                    }else{
                    
                    $_SESSION['from'] = 'vote';
                    
                    header('Location: ' . $_SERVER['HTTP_REFERER'] . '#login');
                    
                    }
            
$var[]= 'CONTENT';
$con[]= $output;

$var[]= 'TITLE';
$con[]= '';

$var[]= 'DESCRIPTION';
$con[]= '';

$var[]= 'KEYWORDS';
$con[]= '';

?>
