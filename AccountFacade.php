<?php
session_start();

require 'vendor/autoload.php';
require 'UserModel.php';

class AccountFacade
{
    private $GoogleServerAPIKey = '';
    private $client_id = '';
    private $service_account_name = ''; //Email Address
    private $key_file_location = ''; //key.p12
    private $user_to_impersonate = '';
    private $domain = '';
    private $developerKey = '';
    private $scopes = array(
        'https://www.googleapis.com/auth/admin.directory.user',
        'https://www.googleapis.com/auth/admin.directory.group'
    );

    private $client = Null;
    private $service = Null;


    private function parseCredentials()
    {
        $string = file_get_contents("credentials.json");
        $credentials = json_decode($string, true);
        $this->GoogleServerAPIKey = $credentials['GoogleServerAPIKey'];
        $this->client_id = $credentials['client_id'];
        $this->service_account_name = $credentials['service_account_name'];
        $this->key_file_location = $credentials['key_file_location'];
        $this->user_to_impersonate = $credentials['user_to_impersonate'];
        $this->domain = $credentials['domain'];
        $this->developerKey = $credentials['developerKey'];
    }

    public function __construct()
    {
        $this->parseCredentials();
        $this->client = new Google_Client();
        $this->client->setApplicationName("Smartlab");
        $this->client->setDeveloperKey($this->developerKey);
        if (isset($_SESSION['service_token'])) {
            $this->client->setAccessToken($_SESSION['service_token']);
        }
        $key = file_get_contents($this->key_file_location);
        $cred = new Google_Auth_AssertionCredentials(
            $this->service_account_name,
            $this->scopes,
            $key,
            'notasecret', // Default P12 password
            'http://oauth.net/grant_type/jwt/1.0/bearer', // Default grant type
            $this->user_to_impersonate
        );
        $this->client->setAssertionCredentials($cred);
        if ($this->client->getAuth()->isAccessTokenExpired()) {
            $this->client->getAuth()->refreshTokenWithAssertion($cred);
        }
        $_SESSION['service_token'] = $this->client->getAccessToken();
        $this->service = new Google_Service_Directory($this->client);
    }

    /**
     * @return Google_Service_Directory_Users
     *
     * Iterate the return $userList->getUsers() as $user
     * echo $user->name->fullName for example.
     *
     */
    public function getUsers()
    {
        $userList = $this->service->users->listUsers(array(
            'domain'        => $this->domain,
            'maxResults'    => 100,
            'orderBy'       => 'email',
            'sortOrder'     => 'ASCENDING'
        ));
        return $userList;
    }

    /**
     * @param UserModel $user
     * @return bool
     */
    public function addUser(UserModel $user)
    {
        try {
            $new_user = $this->service->users->insert($user->getGoogleUser());
        }
        catch (Google_Service_Exception $e) {
            return False;
        }
        return True;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function removeUserByEmail($email)
    {
        try {
            $result = $this->service->users->delete($email);
            if ($result != Null) {
                return False;
            }
        }
        catch (Google_Service_Exception $e) {
            return False;
        }
        return True;
    }

    /**
     * @param string $email
     * @return Google_Service_Directory_User
     */
    public function getUserByEmail($email)
    {
        $user = $this->service->users->get($email);
        return $user;
    }

    /**
     * @param UserModel $user
     * @return bool
     */
    public function updateUser(UserModel $user)
    {
        try {
            $updated = $this->service->users->patch($user->getPrimaryEmail(), $user->getGoogleUser());
        }
        catch (Google_Service_Exception $e) {
            return False;
        }
        return True;
    }
}