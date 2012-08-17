<?php

if (!isset($configuration_file)) {
    $configuration_file = 'engine/conf.php'; // Path to configuration file
/////////// CORE LOAD START ////////////////////////////////////////////////////////////////////////////////////////

    if ($configuration_file AND file_exists($configuration_file))
        require_once $configuration_file;
    else
        die('The configuration file is not defined or does not exist!');
    unset($configuration_file);

    if ($config['dbdriver'] AND file_exists($config['dbdriver']))
        require_once $config['dbdriver'];
    else
        die('The DB driver is not defined or does not exist!');

    if ($config['template_core'] AND file_exists($config['template_core']))
        require_once $config['template_core'];
    else
        die('The template core is not defined or does not exist!');

    if ($config['legend_core'] AND file_exists($config['legend_core']))
        require_once $config['legend_core'];
    else
        die('The legend core is not defined or does not exist!');

    if ($config['auth_core'] AND file_exists($config['auth_core']))
        require_once $config['auth_core'];
    else
        die('The auth core is not defined or does not exist!');

/////////// CORE LOAD DONE //////////////////////////////////////////////////////////////////////////////////////////

    $DB = new DB_CORE;
    $TEMPLATES_MAIN = new Templates;
    $Legend = new Legend;
    $Auth = new Auth;

    $DB->db_init();
    $TEMPLATES_MAIN->template_open($config['path_phys'] . $config['template_path'] . '/' . $config['template_main_file']);

    date_default_timezone_set($config['default_timezone']);

    $var = array();
    $con = array();

    if (@getenv('HTTP_X_FORWARDED_FOR'))
        $ip = @getenv('HTTP_X_FORWARDED_FOR');
    else
        $ip = @getenv('REMOTE_ADDR');

    session_start();

    $user = $Auth->GetUserInfo($_SESSION['uid']);

    if ($_SESSION['admin_token'] == md5($config['sitekey'] . $ip . $_SESSION['admin_session']) AND (int) $_SESSION['admin_session'] > time())
        $admin = TRUE;
    else
        $admin = FALSE;

    $page = htmlentities(trim(strip_tags($_GET['page'])), ENT_QUOTES | ENT_IGNORE);

    $page = explode('/', $page);
}





/* if($_POST['token']!='') $uid = $Auth ->AuthULogin($_POST['token']);
  else die('no token'); */

//echo $_GET['network'];
//var_dump($_GET);


switch ($_GET['network']) {

    /* Авторизация Facebook */
    case "facebook":
        //$uid = $Auth -> FbAuth();


        $app_id = "143371715757895";
        $app_secret = "4703039bf1fc3786a303347b2f5fb3c1";
        $my_url = "http://life.seazo.net/auth?network=facebook";

        $code = $_REQUEST["code"];

        if (empty($code)) {
            $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
            $dialog_url = "https://www.facebook.com/dialog/oauth?client_id="
                    . $app_id . "&redirect_uri=" . urlencode($my_url) . "scope=email,user_about_me&state="
                    . $_SESSION['state'];

            echo("<script> top.location.href='" . $dialog_url . "'</script>");
        }

        if ($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
            $token_url = "https://graph.facebook.com/oauth/access_token?"
                    . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
                    . "&client_secret=" . $app_secret . "&code=" . $code;

            $response = file_get_contents($token_url);
            $params = null;
            parse_str($response, $params);

            $graph_url = "https://graph.facebook.com/me?access_token="
                    . $params['access_token'];

            $user = json_decode(file_get_contents($graph_url));
            //echo("Hello " . $user->name);

            var_dump($user);

            /* Проверка пользователя в базе */
            $identity = $user->link;
            $user_network = "facebook";

            $uid = $DB->GetValues($config['dbprefix'] . 'user', 'uid, ban', "identity='" . $identity . "' AND network='" . $user_network . "'");

            if (count($uid) == 0) {
                $timest = time();

                $user->first_name = iconv("UTF-8", "WINDOWS-1251", trim(strip_tags($user->first_name)));

                $user->last_name = iconv("UTF-8", "WINDOWS-1251", trim(strip_tags($user->last_name)));

                if ($user->first_name != '' AND $user->last_name != '')
                    $DB->Insert($config['dbprefix'] . 'user', "'', '" . $user_network . "', '" . $identity . "', '" . $user->email . "', '" . $user->first_name . "', '" . $user->last_name . "', 0, " . $timest . ", '" . $ip . "'");
                else
                    die('<script>alert("Произошла ошибка. Проверьте правильность введённых данных (Имя и Фамилия)."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');

                $uid = $DB->GetValues($config['dbprefix'] . 'user', 'uid', "identity='" . $identity . "' AND network='" . $user_network . "' AND timest='" . (int) $timest . "' AND last_ip='" . $ip . "'", '', 'uid', 'DESC');

                if ((int) $uid[0]['uid'] == 0)
                    die('<script>alert("Произошла ошибка. Мы не смогли создать вашу учетную запись."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');
            }
            else {

                $DB->Update($config['dbprefix'] . 'user', "last_ip='" . $ip . "'", "uid=" . (int) $uid[0]['uid']);

                /* if ($uid[0]['ban'] == TRUE)
                  return FALSE; */

                if ((int) $uid[0]['uid'] == 0)
                    die('<script>alert("Произошла ошибка. Мы не смогли получить данные вашей учетной записи."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');
                else {
                    //return (int) $uid[0]['uid'];

                    $_SESSION['uid'] = (int) $uid[0]['uid'];
                }

                /* ----------------------------------------------- */
                unset($user);

                header("Location: /index.html#add");
            }
        } else {
            //echo("The state does not match. You may be a victim of CSRF.");
        }

        break;


    /* Авторизация через Twitter */
    case "twitter":

        require_once('./modules/auth/twitteroauth/twitteroauth.php');
        require_once('./modules/auth/twitteroauth/tw-config.php');

        function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {

            $connection = new TwitterOAuth('uG2BQMcu0VXTOJ7Exv7zA', 'W9qN57LxvrKLvULDLW9CBZ1W2alCFeC42R4Cqdv4u8', $oauth_token, $oauth_token_secret);

            return $connection;
        }

        $connection = getConnectionWithAccessToken("112480025-IsmRWTt9RXDMJucB1TVjACiwUfRnBIJx9vMq9mo3", "tcQnpuYUXC3q5LaY0QKRLWcUHv2AE8P7Qp4JbcNwg");

        $content = $connection->get('account/verify_credentials');

        var_dump($content);

        $user_network = "twitter";

        $identity = "https://twitter.com/" . $content->screen_name;

        $user = explode(" ", iconv("UTF-8", "WINDOWS-1251", trim(strip_tags($content->name))));

        $user['first_name'] = $user[0];
        $user['last_name'] = $user[1];

        /* Проверка пользователя в базе */

        $uid = $DB->GetValues($config['dbprefix'] . 'user', 'uid, ban', "identity='" . $identity . "' AND network='" . $user_network . "'");

        if (count($uid) == 0) {
            $timest = time();



            if ($user['first_name'] != '' AND $user['last_name'] != '')
                $DB->Insert($config['dbprefix'] . 'user', "'', '" . $user_network . "', '" . $identity . "', '" . $user->email . "', '" . $user['first_name'] . "', '" . $user['last_name'] . "', 0, " . $timest . ", '" . $ip . "'");
            else
                die('<script>alert("Произошла ошибка. Проверьте правильность введённых данных (Имя и Фамилия)."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');

            $uid = $DB->GetValues($config['dbprefix'] . 'user', 'uid', "identity='" . $identity . "' AND network='" . $user_network . "' AND timest='" . (int) $timest . "' AND last_ip='" . $ip . "'", '', 'uid', 'DESC');

            if ((int) $uid[0]['uid'] == 0)
                die('<script>alert("Произошла ошибка. Мы не смогли создать вашу учетную запись."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');
        }
        else {

            $DB->Update($config['dbprefix'] . 'user', "last_ip='" . $ip . "'", "uid=" . (int) $uid[0]['uid']);



            /* if ($uid[0]['ban'] == TRUE)
              return FALSE; */

            if ((int) $uid[0]['uid'] == 0)
                die('<script>alert("Произошла ошибка. Мы не смогли получить данные вашей учетной записи."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');
            else {
                //return (int) $uid[0]['uid'];
                $_SESSION['uid'] = (int) $uid[0]['uid'];
            }
        }
        /* ----------------------------------------------- */


        break;

    /* Авторизация через Вконтакте */
    case "vk":

        $redirect_url = "http://life.seazo.net/auth?network=vk";
        if ($_GET['error'] != "") {
            var_dump($_GET);
        }
        if ($_GET['code'] != "") {

            $code = $_GET['code'];

            $url = "https://api.vkontakte.ru/oauth/access_token?client_id=3068957&client_secret=I9w2idSw5G7gczlEGS7M&code=" . $code;
            $response = json_decode(@file_get_contents($url));
            if ($response->error) {
                die('обработка ошибки');
            }
            $arrResponse = json_decode(@file_get_contents("https://api.vkontakte.ru/method/getProfiles?uid={$response->user_id}&access_token={$response->access_token}&fields=nickname,screen_name,contacts"))->response;

            var_dump($arrResponse);

            /* Проверка пользователя в базе */

            $user_network = "vkontakte";

            $identity = "http://vk.com/" . $arrResponse[0]->screen_name;

            //var_dump($arrResponse[0]->screen_name);



            $user['first_name'] = iconv("UTF-8", "WINDOWS-1251", trim(strip_tags($arrResponse[0]->first_name)));
            $user['last_name'] = iconv("UTF-8", "WINDOWS-1251", trim(strip_tags($arrResponse[0]->last_name)));

            
            $uid = $DB->GetValues($config['dbprefix'] . 'user', 'uid, ban', "identity='" . $identity . "' AND network='" . $user_network . "'");

            if (count($uid) == 0) {
                $timest = time();



                if ($user['first_name'] != '' AND $user['last_name'] != '')
                    $DB->Insert($config['dbprefix'] . 'user', "'', '" . $user_network . "', '" . $identity . "', '" . $user->email . "', '" . $user['first_name'] . "', '" . $user['last_name'] . "', 0, " . $timest . ", '" . $ip . "'");
                else
                    die('<script>alert("Произошла ошибка. Проверьте правильность введённых данных (Имя и Фамилия)."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');

                $uid = $DB->GetValues($config['dbprefix'] . 'user', 'uid', "identity='" . $identity . "' AND network='" . $user_network . "' AND timest='" . (int) $timest . "' AND last_ip='" . $ip . "'", '', 'uid', 'DESC');

                if ((int) $uid[0]['uid'] == 0)
                    die('<script>alert("Произошла ошибка. Мы не смогли создать вашу учетную запись."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');
            }
            else {

                $DB->Update($config['dbprefix'] . 'user', "last_ip='" . $ip . "'", "uid=" . (int) $uid[0]['uid']);
            }


                /* if ($uid[0]['ban'] == TRUE)
                  return FALSE; */

                if ((int) $uid[0]['uid'] == 0)
                    die('<script>alert("Произошла ошибка. Мы не смогли получить данные вашей учетной записи."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');
                else {
                    //return (int) $uid[0]['uid'];
                    $_SESSION['uid'] = (int) $uid[0]['uid'];
                }
                /* -------------------------------------- */
            } else {
                header("Location: http://oauth.vk.com/authorize?client_id=3068957&scope=&redirect_uri=" . $redirect_url . "&response_type=code");
                die;
            }

            /* ----------------------------------------------- */
            break;

            /* Авторизация через Одноклассники */
            case "ok":

//После помещения на рабочий сервер и когда приложение пройдёт модерацию ввести данные

            $AUTH['client_id'] = 'ID ПРИЛОЖЕНИЯ';
            $AUTH['client_secret'] = 'СЕКРЕТ ПРИЛОЖЕНИЯ';
            $AUTH['application_key'] = 'КЛЮЧ ПРИЛОЖЕНИЯ';
            if (isset($_GET['code'])) {
                $curl = curl_init('http://api.odnoklassniki.ru/oauth/token.do');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, 'code=' . $_GET['code'] . '&redirect_uri=' . urlencode("http://life.seazo.net/auth?network=ok") . '&grant_type=authorization_code&client_id=' . $AUTH['client_id'] . '&client_secret=' . $AUTH['client_secret']);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $s = curl_exec($curl);
                curl_close($curl);
                $auth = json_decode($s, true);
                $curl = curl_init('http://api.odnoklassniki.ru/fb.do?access_token=' . $auth['access_token'] . '&application_key=' . $AUTH['application_key'] . '&method=users.getCurrentUser&sig=' . md5('application_key=' . $AUTH['application_key'] . 'method=users.getCurrentUser' . md5($auth['access_token'] . $AUTH['client_secret'])));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $s = curl_exec($curl);
                curl_close($curl);
                $user = json_decode($s, true);
                /*
                  Массив $user содержит следующие поля:
                  uid - уникальный номер пользователя
                  first_name - имя пользователя
                  last_name - фамилия пользователя
                  birthday - дата рождения пользователя
                  gender - пол пользователя
                  pic_1 - маленькое фото
                  pic_2 - большое фото
                 */

                /*
                  ...
                  Записываем полученные данные в базу, устанавливаем cookies
                  ...
                 */

                header('Location: /index.html#add'); // редиректим после авторизации на главную страницу добавления
            } else {
                header('Location: http://www.odnoklassniki.ru/oauth/authorize?client_id=' . $AUTH['client_id'] . '&scope=VALUABLE ACCESS&response_type=code&redirect_uri=' . urlencode($HOST . 'auth.php?name=odnoklassniki'));
            }


            break;

            default:
            break;
        }
//var_dump($array_user);
//$user = $Auth->GetUserInfo((int) $uid);

        if (!isset($_SESSION['uid'])) {
            $_SESSION['uid'] = $user['uid'];
        }

        if ($page[1] == '' OR $page[1] == 'index.html') {

            header('Location: ' . $config['path_www'] . 'index.html#add');
        } elseif ($page[1] == 'about') {

            header('Location: ' . $config['path_www'] . 'about/index.html#add');
        } elseif ($page[1] == 'legend' AND $_SESSION['from'] != 'vote') {

            header('Location: ' . $config['path_www'] . 'legend/' . $page[2] . '#add');
        } elseif ($page[1] == 'legend' AND $_SESSION['from'] == 'vote') {

            header('Location: ' . $config['path_www'] . 'legend/' . $page[2]);
        } elseif ($page[1] == 'admin') {

            header('Location: ' . $config['path_www'] . 'admin/#add');
        } else {

            header('Location: ' . $config['path_www'] . 'index.html#add');
        }




        $var[] = 'CONTENT';
        $con[] = $output;

        $var[] = 'TITLE';
        $con[] = '';

        $var[] = 'DESCRIPTION';
        $con[] = '';

        $var[] = 'KEYWORDS';
        $con[] = '';
?>
