<?php

class Oauth extends Login {

    public function doLoginTwitter() {

        $settings = new Settings();
        $db = &$GLOBALS['db'];

        require 'oauth/twitter/twitteroauth.php';
        require 'oauth/config/twconfig.php';

        $twitteroauth = new TwitterOAuth(YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET);
        // Requesting authentication tokens, the parameter is the URL we will be redirected to
        $request_token = $twitteroauth->getRequestToken($settings->siteurl . '/en/login/getTwitterData/');

        // Saving them into the session

        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

        // If everything goes well..
        if ($twitteroauth->http_code == 200) {
            // Let's generate the URL and redirect
            $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
            //echo '<h3>Redireccionando a twitter para login...</h3><meta http-equiv="refresh" content="2;url=' . $url . '">';
            header('Location: ' . $url);
        } else {
            echo $twitteroauth->http_code;
            // It's a bad idea to kill the script, but we've got to know when there's an error.
            die('Something wrong happened.');
        }
    }

    public function doLoginFacebook() {

        $settings = new Settings();
        $db = &$GLOBALS['db'];

        require 'oauth/facebook/facebook.php';
        require 'oauth/config/fbconfig.php';

        $facebook = new Facebook(array(
                    'appId' => APP_ID,
                    'secret' => APP_SECRET,
                    'cookie' => true
                ));

        $session = $facebook->getSession();


        if (!empty($session)) {
            # Active session, let's try getting the user id (getUser()) and user info (api->('/me'))
            try {
                $uid = $facebook->getUser();
                $user = $facebook->api('/me');
            } catch (Exception $e) {
                
            }
            if (!empty($user)) {
                # User info ok? Let's print it (Here we will be adding the login and registering routines)
                /* echo '<pre>';
                  print_r($user);
                  echo '</pre><br/>'; */

                $username = $user['name'];
                $userdata = $this->checkUser($uid, 'facebook', $username);
                if (!empty($userdata)) {

                    $_SESSION['id'] = $userdata['id'];
                    $_SESSION['oauth_id'] = $uid;
                    $_SESSION['username'] = $userdata['username'];
                    $_SESSION['oauth_provider'] = $userdata['oauth_provider'];
                    $_SESSION['userid'] = $userdata['id'];
                    $_SESSION['role'] = $userdata['role'];
                    $_SESSION['username'] = $userdata['username'];
                    $_SESSION['lastlogin'] = date('Y-m-d H:i:s', time());
                    $_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
                    $_SESSION['token'] = @md5($userdata['password'] . $_SESSION['lastlogin'] . $_SESSION['IP']);

                    $db = &$GLOBALS['db'];

                    $db->query('UPDATE users
                        SET lastlogin = \'' . $_SESSION['lastlogin'] . '\', IP = \'' . $_SESSION['IP'] . '\'
                        WHERE 
                        username=\'' . $_SESSION['username'] . '\'');

                    //echo '<h3>Bienvenido</h3><meta http-equiv="refresh" content="1;url='. $settings->siteurl . '"/en/client">';   
                    $chat = new Chat();
                    $chat->updateDB();
                    
                    header("Location: " . $settings->siteurl . "/en/client");
                }
                else
                    echo "USUARIO NO ENCONTRADO";
            } else {
                # For testing purposes, if there was an error, let's kill the script
                die("There was an error.");
            }
        } else {
            # There's no active session, let's generate one
            $login_url = $facebook->getLoginUrl();
            //echo '<h3>Redireccionando a facebook para login...</h3><meta http-equiv="refresh" content="2;url='.$login_url.'">';
            header("Location: " . $login_url);
        }
    }

    public function getTwitterData() {

        $settings = new Settings();
        $db = &$GLOBALS['db'];

        require 'oauth/twitter/twitteroauth.php';
        require 'oauth/config/twconfig.php';

        if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {
            // We've got everything we need
            $twitteroauth = new TwitterOAuth(YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
            // Let's request the access token
            $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
            // Save it in a session var
            $_SESSION['access_token'] = $access_token;
            // Let's get the user's info
            $user_info = $twitteroauth->get('account/verify_credentials');
            // Print user's info
            /* echo '<pre>';
              print_r($user_info);
              echo '</pre><br/>'; */
            if (isset($user_info->error)) {
                header("Location: " . $settings->siteurl . "/web.xinxat.com/en/client");
                //echo "ERROR";
            } else {
                $uid = $user_info->id;
                $username = $user_info->name;

                $userdata = $this->checkUser($uid, 'twitter', $username);

                if (!empty($userdata)) {

                    $_SESSION['id'] = $userdata['id'];
                    $_SESSION['oauth_id'] = $uid;
                    $_SESSION['username'] = $userdata['username'];
                    $_SESSION['oauth_provider'] = $userdata['oauth_provider'];
                    $_SESSION['userid'] = $userdata['id'];
                    $_SESSION['role'] = $userdata['role'];
                    $_SESSION['username'] = $userdata['username'];
                    $_SESSION['lastlogin'] = date('Y-m-d H:i:s', time());
                    $_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
                    $_SESSION['token'] = @md5($userdata['password'] . $_SESSION['lastlogin'] . $_SESSION['IP']);

                    $db->query('UPDATE users
                        SET lastlogin = \'' . $_SESSION['lastlogin'] . '\', IP = \'' . $_SESSION['IP'] . '\'
                        WHERE 
                        username=\'' . $_SESSION['username'] . '\'');
                    //echo '<h3>Bienvenido</h3><meta http-equiv="refresh" content="2;url='. $settings->siteurl . '"/en/client">';
                    
                    $chat = new Chat();
                    $chat->updateDB();
            
                    header("Location: " . $settings->siteurl . "/web.xinxat.com/en/client");
                } else {
                    echo $_SESSION['register'] . "<br>";
                    echo "USUARIO NO ENCONTRADO";
                }
            }
        } else {
            // Something's missing, go back to square 1
            //echo '<h3>Bienvenido</h3><meta http-equiv="refresh" content="5;url='. $settings->siteurl . '"/en/login/twitter/">';  
            header("Location: " . $settings->siteurl . "/web.xinxat.com/en/login/twitter");
        }
    }

}