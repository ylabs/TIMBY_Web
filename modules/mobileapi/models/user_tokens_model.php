<?php defined('BASEPATH') or exit('No direct script access allowed');

class user_tokens_model extends BF_Model
{
    protected $table_name = "api_user_tokens";
    protected $set_created	= FALSE;
    protected $set_modified = FALSE;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('users_model');
    }

    public function get_token($user_id , $ip_address)
    {
        $user = $this->users_model->find($user_id);
        $hours = Settings::get('mobileapi_token_validity_hours');

        if($user)
        {
        }
        else
        {
            return false;
        }
    }
}