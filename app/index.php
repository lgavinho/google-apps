<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script>
        function onLoadCallback()
        {
            gapi.client.setApiKey(''); //set your API KEY
            gapi.client.load('plus', 'v1',function(){});//Load Google + API
        }

        function login()
        {
            var myParams = {
                'clientid' : '', //You need to set client id
                'cookiepolicy' : 'single_host_origin',
                'callback' : 'loginCallback', //callback function
                'approvalprompt':'auto',
                'scope' : 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
            };
            gapi.auth.signIn(myParams);
        }

        function loginCallback(result)
        {
            if(result['status']['signed_in'])
            {
                window.location = 'apps.html';
            }

        }
    </script>
</head>
<body>

<button onclick="login()">Login</button>


<script type="text/javascript">
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>
</body>
</html>
