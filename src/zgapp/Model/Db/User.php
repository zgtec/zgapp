<?php

namespace ZgApp\Model\Db;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Model\Db\User
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class User extends Entity
{
    protected $dbtable = 'users';
    public $id;
    public $clientid;
    public $email;
    public $password;
    public $salt;
    public $role;
    public $superuser;
    public $permissions;
    public $status;
    public $registered;
    public $lastlogin;
    public $ipaddress;
    public $password1;
    public $password2;
    public $forgotpass;
    public $forgottime;
    public $lockcount;
    public $locktime;
    public $hashes;

    /**
     * Function getColumns
     *
     *
     *
     * @return array
     */
    public function getColumns()
    {
        return array(
            "id",
            "clientid",
            "email",
            "password",
            "salt",
            "role",
            "superuser",
            "permissions",
            "status",
            "lastlogin",
            "registered",
            "ipaddress",
            "forgotpass",
            "forgottime",
            "lockcount",
            "locktime",
            "hashes",
        );
    }

}