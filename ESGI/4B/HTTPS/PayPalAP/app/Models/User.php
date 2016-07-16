<?php

namespace App\Models;

use Core\Model as BaseModel;

class User extends BaseModel
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    public function __construct()
    {
        //connect to PDO here.
        //$this->_db = BaseModel\Database::get();
        parent::__construct();
    }


    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function insert($data)
    {
        return $this->db->insert('users', $data);
    }
    
    public function getUser($userId)
    {
        return $this->db->select('SELECT * FROM users WHERE userID = \'' . $userId . '\'');
    }

}
