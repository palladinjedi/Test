<?php

if ((int) $config['admin_session'] < 1)
    $config['admin_session'] = 1;

$sess_endtime = time() + (int) $config['admin_session'] * 60;

if ($_POST['user'] == $config['admin_login'] AND md5($_POST['pass']) == $config['admin_pass']) {

    if ((int) $_SESSION['admin_session'] < time()) {

        $_SESSION['admin_token'] = md5($config['sitekey'] . $ip . $sess_endtime);

        $_SESSION['admin_session'] = $sess_endtime;
    }

    header('Location: ' . $config['path_www'] . 'admin/');
} elseif ($admin == TRUE AND (int) $_POST['del_lid'] > 0) {

    $legend_to_delete = $Legend->Get((int) $_POST['del_lid']);

    $user = $Auth->GetUserInfo((int) $legend_to_delete[0]['uid']);

    $Tamplate_mail_user_delete = new Templates;

    $Tamplate_mail_user_delete->template_open('modules/' . $page[0] . '/mail_user_delete.tpl');

    $Tamplate_mail_user_delete->template_set('FIRST_NAME', $user['first_name']);

    $Tamplate_mail_user_delete->template_set('LAST_NAME', $user['last_name']);

    $Tamplate_mail_user_delete->template_set('TITLE', $legend_to_delete[0]['title']);

    $mailheaders = "From: " . $config['email_from'] . "\n";

    $mailheaders .= "Reply-To: " . $config['admin_email'] . "\n";

    $mailheaders .= "Content-Type: text; charset=windows-1251\r\n\r\n";

    mail($user['email'], $config['email_subject_public'], $Tamplate_mail_user_delete->template_show(), $mailheaders);

    $Legend->doDelete((int) $_POST['del_lid']);

    header('Location: ' . $config['path_www'] . 'legend/AllByDate.html');
} elseif ($admin == TRUE AND (int) $_POST['unpub_lid'] > 0) {

    $Legend->doUnPublic((int) $_POST['unpub_lid']);

    header('Location: ' . $config['path_www'] . 'legend/legend_' . (int) $_POST['unpub_lid'] . '.html');
} elseif ($admin == TRUE AND (int) $_POST['pub_lid'] > 0) {

    $Legend->doPublic((int) $_POST['pub_lid']);

    $legend_to_public = $Legend->Get((int) $_POST['pub_lid']);

    $user = $Auth->GetUserInfo((int) $legend_to_public[0]['uid']);

    $Tamplate_mail_user_public = new Templates;

    $Tamplate_mail_user_public->template_open('modules/' . $page[0] . '/mail_user_public.tpl');

    $Tamplate_mail_user_public->template_set('FIRST_NAME', $user['first_name']);

    $Tamplate_mail_user_public->template_set('LAST_NAME', $user['last_name']);

    $Tamplate_mail_user_public->template_set('TEXT', $legend_to_public[0]['text']);

    $Tamplate_mail_user_public->template_set('TITLE', $legend_to_public[0]['title']);

    $Tamplate_mail_user_public->template_set('LINK', $config['path_www'] . 'legend/legend_' . $legend_to_public[0]['lid'] . '.html');

    $mailheaders = "From: " . $config['email_from'] . "\n";

    $mailheaders .= "Reply-To: " . $config['admin_email'] . "\n";

    $mailheaders .= "Content-Type: text; charset=windows-1251\r\n\r\n";

    mail($user['email'], $config['email_subject_public'], $Tamplate_mail_user_public->template_show(), $mailheaders);

    header('Location: ' . $config['path_www'] . 'legend/legend_' . (int) $_POST['pub_lid'] . '.html');
} elseif ($admin == TRUE AND (int) $_POST['recount_lid'] > 0) {

    $Legend->doRecount((int) $_POST['recount_lid']);

    header('Location: ' . $config['path_www'] . 'legend/legend_' . (int) $_POST['recount_lid'] . '.html');
} elseif ($admin == TRUE AND $_POST['recount_all'] == 'TRUE') {

    $Legend->doRecount();

    header('Location: ' . $config['path_www'] . 'legend/AllByDate.html');
} elseif ($admin == TRUE AND (int) $_POST['recount_state'] > 0) {

    $Legend->doRecount(FALSE, (int) $_POST['recount_state']);

    if ((int) $_POST['recount_state'] == 1) {

        header('Location: ' . $config['path_www'] . 'legend/DayByDate.html');
    } elseif ((int) $_POST['recount_state'] == 2) {

        header('Location: ' . $config['path_www'] . 'legend/NightByDate.html');
    } else {

        header('Location: ' . $config['path_www'] . 'legend/AllByDate.html');
    }
} elseif ($admin == TRUE AND (int) $_POST['userban'] > 0) {

    $Auth->doBan((int) $_POST['userban']);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
} elseif ($admin == TRUE AND (int) $_POST['userunban'] > 0) {

    $Auth->doUnBan((int) $_POST['userunban']);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
} elseif ($admin == TRUE AND (int) $_POST['lid'] > 0 AND isset($_POST['title']) AND isset($_POST['text']) AND (int) $_POST['state'] > 0) {

    $Legend->doSet((int) $_POST['lid'], iconv('UTF-8', 'WINDOWS-1251', $_POST['title']), iconv('UTF-8', 'WINDOWS-1251', $_POST['text']), (int) $_POST['state']);

    die('ok');
} elseif ($_POST['logout'] == 'TRUE') {

    $_SESSION['admin_token'] = FALSE;

    $_SESSION['admin_session'] = FALSE;

    header('Location: ' . $config['path_www']);
} elseif ($admin == TRUE) {

    $Tamplate_admin_logout = new Templates;

    $Tamplate_admin_logout->template_open('modules/' . $page[0] . '/logout.tpl');

    $Tamplate_admin_logout->template_set('LEGENDS_LINK_ALL', $config['path_www'] . 'legend/AllByDate.html');

    $Tamplate_admin_logout->template_set('LEGENDS_LINK_DAY', $config['path_www'] . 'legend/DayByDate.html');

    $Tamplate_admin_logout->template_set('LEGENDS_LINK_NIGHT', $config['path_www'] . 'legend/NightByDate.html');

    $output = $Tamplate_admin_logout->template_show();
} else {

    $_SESSION['admin_token'] = FALSE;

    $_SESSION['admin_session'] = FALSE;

    $Tamplate_admin_auth = new Templates;

    $Tamplate_admin_auth->template_open('modules/' . $page[0] . '/auth.tpl');

    $Tamplate_admin_auth->template_set('LEGENDS_LINK_ALL', $config['path_www'] . 'legend/AllByDate.html');

    $Tamplate_admin_auth->template_set('LEGENDS_LINK_DAY', $config['path_www'] . 'legend/DayByDate.html');

    $Tamplate_admin_auth->template_set('LEGENDS_LINK_NIGHT', $config['path_www'] . 'legend/NightByDate.html');

    $output = $Tamplate_admin_auth->template_show();
}

$var[] = 'CONTENT';
$con[] = $output;

$var[] = 'TITLE';
$con[] = 'Администратор - ' . $config['TITLE'];

$var[] = 'DESCRIPTION';
$con[] = '';

$var[] = 'KEYWORDS';
$con[] = '';
?>
