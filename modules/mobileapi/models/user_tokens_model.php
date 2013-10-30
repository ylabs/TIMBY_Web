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

        $current_date = new DateTime();
        $current_date->sub(new DateInterval("P{$hours}H"));

        if($user)
        {
            $result = $this->where('creation_time >=', $current_date->format('Y-m-d H:i:s'))
                ->where('ip_address', $ip_address)
                ->find_all();

            if($result)
            {
                return $result[0]->token;
            }

            return false;
        }
        else
        {
            return false;
        }
    }

    public function delete_tokens($user_id)
    {
        return $this->delete_where(array('user_id' => $user_id));
    }

    public function create_token($user_id, $ip_address)
    {
        $token = md5(time());
        $token_len = strlen($token);
        $token_half = ceil($token_len / 2);
        $token = substr($token, $token_half, $token_half - 2);

        $result = $this->insert(
            array(
                'user_id' => $user_id,
                'ip_address' => $ip_address,
                'token' => $token,
            )
        );

        if($result)
        {
            return $token;
        }

        return false;
    }

    public function clear_old_tokens()
    {
        $hours = Settings::get('mobileapi_token_validity_hours');

        $current_date = new DateTime();
        $current_date->sub(new DateInterval("P{$hours}H"));

        return $this->delete_where(array($this->where('creation_time <', $current_date->format('Y-m-d H:i:s'))));
    }
}