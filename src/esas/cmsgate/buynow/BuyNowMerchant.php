<?php


namespace esas\cmsgate\buynow;


class BuyNowMerchant
{
    private $id;
    private $login;
    private $password;
    private $lastLogin;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return BuyNowMerchant
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * @param mixed $login
     * @return BuyNowMerchant
     */
    public function setLogin($login) {
        $this->login = $login;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return BuyNowMerchant
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastLogin() {
        return $this->lastLogin;
    }

    /**
     * @param mixed $lastLogin
     * @return BuyNowMerchant
     */
    public function setLastLogin($lastLogin) {
        $this->lastLogin = $lastLogin;
        return $this;
    }


}