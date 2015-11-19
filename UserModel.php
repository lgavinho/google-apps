<?php
/**
 * Created by PhpStorm.
 * User: lgavinho
 * Date: 17/11/15
 * Time: 11:51 PM
 */

class UserModel
{
    private $givenName;
    private $familyName;
    private $primaryEmail;
    private $password;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * @param mixed $familyName
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
    }

    /**
     * @return mixed
     */
    public function getPrimaryEmail()
    {
        return $this->primaryEmail;
    }

    /**
     * @param mixed $primaryEmail
     */
    public function setPrimaryEmail($primaryEmail)
    {
        $this->primaryEmail = $primaryEmail;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * @param mixed $givenName
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;
    }

    /**
     * @return mixed
     */
    public function getHashPassword()
    {
        return hash("sha1",$this->password);
    }

    public function getGoogleUser()
    {
        $name = new Google_Service_Directory_UserName();
        $name->setGivenName($this->givenName);
        $name->setFamilyName($this->familyName);

        $user = new Google_Service_Directory_User();
        $user->setName($name);
        $user->setHashFunction("SHA-1");
        $user->setPrimaryEmail($this->primaryEmail);
        $user->setPassword($this->getHashPassword());
        return $user;
    }
}