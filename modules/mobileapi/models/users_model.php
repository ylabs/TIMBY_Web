<?php defined('BASEPATH') or exit('No direct script access allowed');

class users_model extends BF_Model
{
    protected $table_name = "api_users";
    protected $set_created	= FALSE;
    protected $set_modified = FALSE;

    public function __construct()
    {
        parent::__construct();
    }

    public function create_user($user_name, $password)
    {
        $user = $this->user_exists($user_name);

        if(!$user)
        {
            return parent::insert(array('name' => $user_name, 'password' => md5($password)));
        }

        return false;
    }

    private function user_exists($user_name)
    {
        $user = $user = $this->find_by(array('name' => $user_name));
        return $user;
    }

    public function login_user($user_name, $password)
    {
        $user = $this->find_by(array('name' => $user_name, 'password' => md5($password)));
        return $user;
    }
}