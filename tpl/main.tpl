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

            var oauth, options;

            options = {
            enablePrivilege: false,
            consumerKey: 'uG2BQMcu0VXTOJ7Exv7zA',
            consumerSecret: 'W9qN57LxvrKLvULDLW9CBZ1W2alCFeC42R4Cqdv4u8',
            requestTokenUrl: "https://api.twitter.com/oauth/request_token",
            authorizationUrl: "https://api.twitter.com/oauth/authorize",
            accessTokenUrl: "https://api.twitter.com/oauth/access_token",
            callbackUrl:"http://life.seazo.net/auth=twitter"
        };
        oauth.fetchRequestToken(openAuthoriseWindow, failureHandler);
        function openAuthoriseWindow(url)
        {
        var wnd = window.open(url, 'authorise');
        setTimeout(waitForPin, 100);

        function waitForPin()
            {
            if (wnd.closed)
                {
                    var pin = prompt("Please enter your PIN", "");
                oauth.setVerifier(pin);
                oauth.fetchAccessToken(getSomeData, failureHandler);
            }
            else
                {
                setTimeout(waitForPin, 100);
        }
    }
}

function getSomeData()
        {
oauth.get("https://api.example.com/oauth/something/?format=json", function (data) {
console.log(data.text);
}, failureHandler);
}

function failureHandler(data)
        {
console.error(data);
}


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

function fbauth(){
/** Перенаправляем на страницу авторизации Fb */
location.href = 'https://www.facebook.com/dialog/oauth?client_id=143371715757895&redirect_uri=http://life.seazo.net';
}

function vkauth(){
/** Перенаправляем на страницу авторизации ВКонтакте */
location.href = 'http://api.vk.com/oauth/authorize?client_id=3068957&redirect_uri=http://life.seazo.net&display=page';
}
function twauth(){
/** Авторизация через Twitter */



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

        <script type="text/javascript">

var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-28999005-8']);
_gaq.push(['_addOrganic', 'Rambler', 'query']);
_gaq.push(['_addOrganic', 'Mail', 'q']);
_gaq.push(['_addOrganic', 'Nigma', 'q']);
_gaq.push(['_addOrganic', 'Webalta', 'q']);
_gaq.push(['_addOrganic', 'Aport', 'r']);
_gaq.push(['_addOrganic', 'Gogo', 'q']);
_gaq.push(['_addOrganic', 'Bing', 'q']);
_gaq.push(['_addOrganic', 'QIP', 'query']);
_gaq.push(['_trackPageview']);

(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

        </script>

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
