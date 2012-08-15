<?php

class Auth {

    function AuthULogin($token = FALSE) {
        global $DB, $config, $ip;

        if ($token != FALSE)
            $token = htmlentities(trim(strip_tags($token)), ENT_QUOTES | ENT_IGNORE);
        else
            return FALSE;

        $s = file_get_contents('http://ulogin.ru/token.php?token=' . $token . '&host=' . $_SERVER['HTTP_HOST']);
        $user = json_decode($s, true);

        $user['identity'] = htmlentities(trim(strip_tags($user['identity'])), ENT_QUOTES);

        $user['network'] = htmlentities(trim(strip_tags($user['network'])), ENT_QUOTES);

        if ($user['identity'] != '' AND $user['network'] != '')
            $uid = $DB->GetValues($config['dbprefix'] . 'user', 'uid, ban', "identity='" . $user['identity'] . "' AND network='" . $user['network'] . "'");
        else
            die('<script>alert("Произошла ошибка. Мы не смогли получить данные вашей учетной записи."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');

        if (count($uid) == 0) {

            if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/", $user['email']))
                die('<script>alert("Произошла ошибка. Проверьте правильность введённого email."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');

            $timest = time();

            $user['first_name'] = iconv("UTF-8", "WINDOWS-1251", trim(strip_tags($user['first_name'])));

            $user['last_name'] = iconv("UTF-8", "WINDOWS-1251", trim(strip_tags($user['last_name'])));

            if ($user['first_name'] != '' AND $user['last_name'] != '')
                $DB->Insert($config['dbprefix'] . 'user', "'', '" . $user['network'] . "', '" . $user['identity'] . "', '" . $user['email'] . "', '" . $user['first_name'] . "', '" . $user['last_name'] . "', 0, " . $timest . ", '" . $ip . "'");
            else
                die('<script>alert("Произошла ошибка. Проверьте правильность введённых данных (Имя и Фамилия)."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');

            $uid = $DB->GetValues($config['dbprefix'] . 'user', 'uid', "identity='" . $user['identity'] . "' AND network='" . $user['network'] . "' AND timest='" . (int) $timest . "' AND last_ip='" . $ip . "'", '', 'uid', 'DESC');

            if ((int) $uid[0]['uid'] == 0)
                die('<script>alert("Произошла ошибка. Мы не смогли создать вашу учетную запись."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');
            else
                return (int) $uid[0]['uid'];
        }
        else {

            $DB->Update($config['dbprefix'] . 'user', "last_ip='" . $ip . "'", "uid=" . (int) $uid[0]['uid']);

            if ($uid[0]['ban'] == TRUE)
                return FALSE;

            if ((int) $uid[0]['uid'] == 0)
                die('<script>alert("Произошла ошибка. Мы не смогли получить данные вашей учетной записи."); window.location = "' . $config['path_www'] . 'legend/AllByDate.html#login";</script>');
            else
                return (int) $uid[0]['uid'];
        }

        return FALSE;
    }

    function OAuth() {
        global $DB, $config, $ip;
        
    }

    function GetUserInfo($uid = FALSE) {
        global $DB, $config;

        if ($uid == FALSE)
            return FALSE;

        $user = $DB->GetValues($config['dbprefix'] . 'user', '*', "uid=" . (int) $uid);

        if (count($user) == 1)
            return $user[0];
        else
            return FALSE;
    }

    function doBan($uid = FALSE) {
        global $DB, $config;

        if ($uid == FALSE)
            return FALSE;

        $DB->Update($config['dbprefix'] . 'user', "ban=1", "uid=" . (int) $uid);

        return TRUE;
    }

    function doUnBan($uid = FALSE) {
        global $DB, $config;

        if ($uid == FALSE)
            return FALSE;

        $DB->Update($config['dbprefix'] . 'user', "ban=0", "uid=" . (int) $uid);

        return TRUE;
    }

}

?>
