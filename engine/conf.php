<?php

$config = array(

'dbdriver'                              => 'engine/drivers/db/mysql.php',
'dbhost'                                => 'localhost',
'dbuname'                               => 'zub_life',
'dbpass'                                => 'OQJL6smY',
'dbname'                                => 'zub_life',
'dbprefix'                              => 'sr_',
'path_www'                              => '/',
'path_phys'                             => '',
'modules_dir'                           => 'modules',
'modules_index'                         => 'index',
'modules_runtime'                       => 'index',
'template_core'                         => 'engine/classes/templates.php',
'template_path'                         => 'tpl',
'template_main_file'                    => 'main.tpl',
'default_timezone'                      => 'Europe/Moscow',

'legend_core'                           => 'engine/classes/legend.php',
'auth_core'                             => 'engine/classes/auth.php',
'maxtitle'                              => 50,
'maxtext'                               => 900,
'mintitle'                              => 1,
'mintext'                               => 1,
'legendsinlist'                         => 4,
'maxtextinlist'                         => 143,
'maxtextinindex'                        => 90,
'email_subject_add'                     => 'Ваша идея отправлена на рассмотрение',
'email_subject_public'                  => 'Ваша идея была рассмотрена и опубликована',
'email_from'                            => 'Банк Лайф <contest@life.ru>',
'vote_end_after'                        => 604800, //60 * 60 * 24 * 7

'tmpl_index_dynbg_min'                  => 28,
'tmpl_index_dynbg_max'                  => 49,
'tmpl_index_dynbg_avg'                  => 38.5,
'tmpl_index_dynbg_step'                 => 5,

'month'                                 => array(1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'),
'LEGEND_ITEM_STATE_DAY'                 => 'ЛЕГЕНДА ДНЯ',
'LEGEND_ITEM_STATE_NIGHT'               => 'ЛЕГЕНДА НОЧИ',
'LEGEND_ITEM_STATE_DIVCLASS_DAY'        => 'legend-day',
'LEGEND_ITEM_STATE_DIVCLASS_NIGHT'      => 'legend-day legend-night',
'LEGEND_LIST_SORT_CURRENT_CLASS'        => 'class="inner"',
'TITLE'                                 => 'Новые идеи банка Life',


'admin_login'                   => 'admin',
'admin_pass'                    => '21232f297a57a5a743894a0e4a801fc3',
'admin_email'                   => 'admin@sr.ru',
'admin_session'                 => 50,
'sitekey'                       => "k5rb5mB2x0Oli3FSpRE0kjsdhfkjsdhfA0siinx14p85e"
);

?>
