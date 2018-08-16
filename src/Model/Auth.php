<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Model;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

/**
 *
 * Class ZgApp\Model\Auth
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Auth extends AuthenticationService
{

    protected $staticSalt = "4lMxF0uDzc";
    protected $hashesNum = 5;
    protected $lockCount = 5;
    protected $lockTime = 1800;
    protected $authAdapter;
    protected $dbTable;

    /**
     * Function setDbAdapter
     *
     *
     *
     * @param $adapter
     * @param $entity
     */
    public function setDbAdapter($adapter, $entity)
    {
        $dbTable = new \ZgApp\Model\Db\Table($adapter);
        $dbTable->init($entity);
        $this->dbTable = $dbTable;

        $adapter = new AuthAdapter($adapter);
        $adapter->setTableName($entity->getTable())
            ->setIdentityColumn('email')
            ->setCredentialColumn('password')
            ->setCredentialTreatment('SHA1(CONCAT("' . $this->staticSalt . '", ? , salt))');
        $this->authAdapter = $adapter;
    }

    /**
     * Function preparePassword
     *
     *
     *
     * @param $password
     * @param $salt
     * @return string
     */
    public function preparePassword($password, $salt)
    {
        $password = sha1($this->staticSalt . $password . $salt);
        return $password;
    }

    /**
     * Function setStaticSalt
     *
     *
     *
     * @param $staticSalt
     */
    public function setStaticSalt($staticSalt)
    {
        $this->staticSalt = $staticSalt;
        $this->getIdentity();
    }

    /**
     * Function dynamicSalt
     *
     *
     *
     * @return string
     */
    public function dynamicSalt()
    {
        $salt = "";
        for ($i = 0; $i < 50; $i++) {
            $salt .= chr(rand(48, 90));
        }
        return $salt;
    }

    public function registerUser()
    {
        $userInfo = $this->authAdapter->getResultRowObject(null, 'password');
        $this->getStorage()->write($userInfo);
    }

    /**
     * Function isValid
     *
     *
     *
     * @param $identity
     * @param $credential
     * @return mixed
     */
    public function isValid($identity, $credential)
    {
        $this->authAdapter->setIdentity($identity);
        $this->authAdapter->setCredential($credential);
        $authResult = $this->authAdapter->authenticate();
        if ($authResult->isValid()) {
            $this->registerUser();
        } elseif ($this->dbTable) {
            $this->addLock($identity);
        }
        return $authResult->isValid();
    }

    /**
     * Function addLock
     *
     *
     *
     * @param $identity
     */
    public function addLock($identity)
    {
        $user = $this->dbTable->fetchRow(array('email =?' => $identity));
        if ($user) {
            $user->lockcount++;
            $user->locktime = time();
            $this->dbTable->init($user);
            $this->dbTable->saveRow();
        }
    }

    /**
     * Function getIdentity
     *
     *
     *
     * @return mixed
     */
    public function getIdentity()
    {
        $result = parent::getIdentity();
        if (!isset($result->role)) {
            $result = $this->getStorage();
            $result->role = 'guest';
        }

        return $result;
    }

    /**
     * Function generateHashes
     *
     *
     *
     * @param Db\User $admin
     * @return mixed
     */
    public function generateHashes(\ZgApp\Model\Db\User $admin)
    {
        $hashes = $this->getReader()->fromString($admin->hashes);
        if (!is_array($hashes)) {
            $hashes = array();
        }

        // Adding hash to hashes history
        $hashes[] = array('salt' => $admin->salt, 'hash' => $admin->password);

        // Cleaning old hashes
        $pop = count($hashes) - $this->hashesNum;
        if ($pop > 0) {
            for ($i = 0; $i < $pop; $i++) {
                array_shift($hashes);
            }
        }

        return $this->getWriter()->toString($hashes);
    }

    /**
     * Function checkHashes
     *
     *
     *
     * @param Db\User $admin
     * @param $password
     * @return bool
     */
    public function checkHashes(\ZgApp\Model\Db\User $admin, $password)
    {
        $hashes = $this->getReader()->fromString($admin->hashes);
        if (!is_array($hashes)) {
            return true;
        }

        foreach ($hashes as $hash) {
            if ($this->preparePassword($password, $hash['salt']) === $hash['hash'])
                return false;
        }
        return true;
    }


    /**
     * Function getLockCount
     *
     *
     *
     * @return int
     */
    public function getLockCount()
    {
        return $this->lockCount;
    }

    /**
     * Function getLockTime
     *
     *
     *
     * @return int
     */
    public function getLockTime()
    {
        return $this->lockTime;
    }

    /**
     * Function getHashesNum
     *
     *
     *
     * @return int
     */
    public function getHashesNum()
    {
        return $this->hashesNum;
    }


    /**
     * getReader(), getWriter() - returns reader and writer
     */
    protected function getReader()
    {
        return new \Zend\Config\Reader\Json();
    }

    /**
     * Function getWriter
     *
     *
     *
     * @return \Zend\Config\Writer\Json
     */
    protected function getWriter()
    {
        return new \Zend\Config\Writer\Json();
    }

}

