<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Google Apps Login Demo</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
          integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <?php

    require '../vendor/autoload.php';

    ########## Google Settings.Client ID, Client Secret from https://console.developers.google.com #############

    $string = file_get_contents("../credentials.json");
    $credentials = json_decode($string, true);
    $client_id = $credentials['client_oauth_id'];
    $client_secret = $credentials['client_oauth_secret'];
    $redirect_uri = 'http://googleapps.dev';

    $login_hint = '';
    if (isset($_GET['username'])) {
        $login_hint = $_GET['username'] . '@gedu.demo.smartlab.me';
    }

    $client = new Google_Client();
    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->setRedirectUri($redirect_uri);
    $client->addScope("email");
    $client->addScope("profile");
    $client->setLoginHint($login_hint);

    $service = new Google_Service_Oauth2($client);

    if (isset($_GET['logout'])) {
        unset($_SESSION['access_token']);
    }

    //If code is empty, redirect user to google authentication page for code.
    //Code is required to aquire Access Token from google
    //Once we have access token, assign token to session variable
    //and we can redirect user back to page and login.
    if (isset($_GET['code'])) {
        $client->authenticate($_GET['code']);
        $_SESSION['access_token'] = $client->getAccessToken();
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        exit;
    }

    $authUrl = Null;
    //if we have access_token continue, or else get login URL for user
    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        $client->setAccessToken($_SESSION['access_token']);
    } else {
        $authUrl = $client->createAuthUrl();
        if ($login_hint) {
            header("location:" . $authUrl);
            exit;
        } else {
            header("location:login.php");
            exit;
        }
    }

    $user = Null;
    if (!isset($authUrl)) {
        $user = $service->userinfo->get(); //get user info
    }

    ?>

    <?php
    if (isset($user)) {
        ?>

        <div class="page-header">
            <h1>Google Apps Login Demo
                <small>Welcome back <?= $user->name ?></small>
            </h1>
        </div>

        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="http://www.gavinholabs.com.br">Gavinho Labs</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">Google Apps <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="https://mail.google.com/mail/">Gmail</a></li>
                                <li><a href="https://drive.google.com">Drive</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://googleapps.dev/?logout=1">Logout</a></li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
    <?php
    }
    ?>

</div>
</body>
</html>
