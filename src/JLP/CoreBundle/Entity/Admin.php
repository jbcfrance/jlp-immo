<?php

namespace JLP\CoreBundle\Entity;

/**
 * Admin
 */
class Admin
{
    /**
     * @var integer
     */
    private $adminId;

    /**
     * @var string
     */
    private $adminLogin;

    /**
     * @var string
     */
    private $adminPassword;


    /**
     * Get adminId
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->adminId;
    }

    /**
     * Set adminLogin
     *
     * @param string $adminLogin
     *
     * @return Admin
     */
    public function setAdminLogin($adminLogin)
    {
        $this->adminLogin = $adminLogin;

        return $this;
    }

    /**
     * Get adminLogin
     *
     * @return string
     */
    public function getAdminLogin()
    {
        return $this->adminLogin;
    }

    /**
     * Set adminPassword
     *
     * @param string $adminPassword
     *
     * @return Admin
     */
    public function setAdminPassword($adminPassword)
    {
        $this->adminPassword = $adminPassword;

        return $this;
    }

    /**
     * Get adminPassword
     *
     * @return string
     */
    public function getAdminPassword()
    {
        return $this->adminPassword;
    }
}

