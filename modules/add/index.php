<?php

            if((int)$_SESSION['uid']>0 AND isset($_POST['title']) AND isset($_POST['text']) AND (int)$_POST['state']>0) $user = $Auth ->GetUserInfo((int)$_SESSION['uid']);
            
                    if($user==TRUE AND $user['ban']==FALSE){
                    
                    $Legend ->doAdd($user['uid'], iconv('UTF-8', 'WINDOWS-1251', $_POST['title']), iconv('UTF-8', 'WINDOWS-1251', $_POST['text']), (int)$_POST['state']);
                                            
                    $Tamplate_mail_user = new Templates; 
                
                    $Tamplate_mail_user ->template_open('modules/' . $page[0] . '/mail_user.tpl');
                
                    $Tamplate_mail_user ->template_set('FIRST_NAME', $user['first_name']);
                
                    $Tamplate_mail_user ->template_set('LAST_NAME', $user['last_name']);
                
                    $mailheaders = "From: " . $config['email_from'] . "\n";
                
                    $mailheaders .= "Reply-To: " . $config['admin_email'] . "\n";
                
                    $mailheaders .= "Content-Type: text; charset=windows-1251\r\n\r\n";
                
                    mail($user['email'], $config['email_subject_add'], $Tamplate_mail_user ->template_show(), $mailheaders);
                    
                    }
                                                              
            die('ok');
            
$var[]= 'CONTENT';
$con[]= $output;

$var[]= 'TITLE';
$con[]= '';

$var[]= 'DESCRIPTION';
$con[]= '';

$var[]= 'KEYWORDS';
$con[]= '';

?>