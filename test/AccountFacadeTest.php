<?php

require 'AccountFacade.php';

class AccountFacadeTest extends PHPUnit_Framework_TestCase
{
    private $domain = 'gedu.demo.smartlab.me';
    private $user1 = Null;
    private $account = Null;

    protected function setUp()
    {
        $email = 'test1@' . $this->domain;
        $user1 = new UserModel();
        $user1->setGivenName('Tester');
        $user1->setPrimaryEmail($email);
        $user1->setFamilyName('Testerton');
        $user1->setPassword('123');
        $this->user1 = $user1;

        $this->account = new AccountFacade();
    }

    public function testAddUserSuccess()
    {
        $newUser = $this->account->addUser($this->user1);
        $this->assertTrue($newUser);

        $googleUser = $this->account->getUserByEmail($this->user1->getPrimaryEmail());
        $this->assertInstanceOf('Google_Service_Directory_User', $googleUser);
    }

    public function testRemoveInvalidUser()
    {
        $result = $this->account->removeUserByEmail('invaliduser@' . $this->domain);
        $this->assertFalse($result);
    }

    public function testUpdateUserSuccess()
    {
        $user = clone $this->user1;
        $user->setPassword('matrix');
        $result = $this->account->updateUser($user);
        $this->assertTrue($result);
    }

    public function testListOfUsersSuccess()
    {
        $list = $this->account->getUsers();
        $this->assertInstanceOf('Google_Service_Directory_Users',$list);
        echo PHP_EOL;
        foreach ($list->getUsers() as $user) {
            echo $user->name->fullName . PHP_EOL;
        }
    }

    public function testRemoveUserSuccess()
    {
        $result = $this->account->removeUserByEmail($this->user1->getPrimaryEmail());
        $this->assertTrue($result);
    }

}