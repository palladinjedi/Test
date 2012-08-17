<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
        <title>{_TITLE}</title>
        <link rel="stylesheet" type="text/css" href="{_PATH}css/all.css" />
        <link rel="stylesheet" href="{_PATH}css/form.css" type="text/css" />
        <link rel="stylesheet" type="text/css" href="{_PATH}css/opacity.css" />
        <script type="text/javascript" src="{_PATH}js/autoscaling-menu.js"></script>
        <!-- <script type="text/javascript" src="{_PATH}js/clear-form-feilds.js"></script> -->
        <script type="text/javascript" src="{_PATH}js/form.js"></script>
        <script type="text/javascript" src="{_PATH}js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="{_PATH}js/jquery.main.js"></script>
        <script type="text/javascript" src="http://cloud.github.com/downloads/bytespider/jsOAuth/jsOAuth-1.3.4.min.js"></script>
        <script type="text/javascript" src="http://userapi.com/js/api/openapi.js?49"></script>

        {_AJAX_SCRIPT_FORM_ADD}
        {_AJAX_SCRIPT_FORM_EDIT}

        <script type="text/javascript">
            VK.init({apiId: 3021382, onlyWidgets: true});
        </script>
        <script>


function dump(obj) {
var out = "";
if(obj && typeof(obj) == "object"){
for (var i in obj) {
out += i + ": " + obj[i] + "\n";
}
} else {
out = obj;
}
alert(out);
}


        </script>


        <div id="fb-root"></div>
        <script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

        

    </head>
    <body>
        <div class="w1">
            <div class="w2">
                <div class="wrapper-holder">
                    <div id="wrapper">
                        <div id="header">
                            <div class="top-panel">


                            </div>
                            <div class="promo">
                                <div class="left-box">

                                    <a href="#" class="btn-link{_GUEST}"><span>Добавить идею</span></a>
                                    <!--<a href="{_PATH}about/index.html" class="text-about">О Проекте</a> <a href="{_PATH}legend/AllByDate.html" class="text-about">Все легенды</a>-->
                                </div>




                                {_CONTENT}


                                <div id="footer">
                                    <div class="img-warning"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {_POPUP_SUCCESS}
                {_POPUP_AUTH}
                {_POPUP_ADD}
                </body>
                </html>
