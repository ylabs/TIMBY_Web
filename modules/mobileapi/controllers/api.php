<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * API Controller
 */
class API extends REST_Controller
{
    const msg_missing_parameters_id = "001";
    const msg_missing_parameters_text = "API Parameters missing";

    const msg_system_error_id = "002";
    const msg_system_error_text = "System error";

    const msg_login_invalid_id = "100";
    const msg_login_invalid_text = "Invalid login credentials";

    const msg_token_invalid_id = "101";
    const msg_token_invalid_text = "Token is invalid";

	public function __construct()
	{
		parent::__construct();

        $this->load->model('users_model');
        $this->load->model('keys_model');
        $this->load->model('user_tokens_model');

        $this->load->library('mobileapi_utils');

        if(strtolower(substr(current_url(), 4, 1)) != 's')
        {
            redirect(str_replace('http:', 'https:', current_url()));
        }
	}

    private function error($error_code, $message)
    {
        return array("status" => "NOK", "error" => $error_code, "message" => $message);
    }

    private function success($message)
    {
        return array("status" => "OK", "message" => $message);
    }

	/**
	 * Login
	 */

	public function login_post()
	{
        $user_name = $this->input->post('user_name');
        $password = $this->input->post('password');

        if($user_name == false || $password == false)
        {
            $this->response($this->error(self::msg_missing_parameters_id, self::msg_missing_parameters_text));
            exit;
        }

        $login_user_status = $this->users_model->login_user($user_name, $password);

        if($login_user_status)
        {
            $token = $this->user_tokens_model->create_token($login_user_status[0]->id,
                $this->mobileapi_utils->get_client_ip());

            if($token !== false)
            {
                $this->response($this->success(array("user_id" => $login_user_status[0]->id, "token" => $token)));
                exit;
            }
            else
            {
                $this->response($this->error(self::msg_system_error_id, self::msg_system_error_text));
                exit;
            }
        }
        else
        {
            $this->response($this->error(self::msg_login_invalid_id, self::msg_login_invalid_text));
            exit;
        }
	}

    public function logout_post()
    {
        $user_name = $this->input->post('user_name');
        $password = $this->input->post('password');

        if($user_name == false || $password == false)
        {
            $this->response($this->error(self::msg_missing_parameters_id, self::msg_missing_parameters_text));
            exit;
        }

        $login_user_status = $this->users_model->login_status($user_name, $password);

        if($login_user_status)
        {
            $this->user_tokens_model->delete_tokens($login_user_status[0]->id);
            $this->response($this->success("Logout success"));
            exit;
        }
        else
        {
            $this->response($this->error(self::msg_login_invalid_id, self::msg_login_invalid_text));
            exit;
        }
    }

    /**
     * Check to see validity of a token
     */

    public function tokencheck_post()
    {
        $token = $this->input->post('token');
        $user_id = $this->input->post('user_id');

        if($token !== false && $user_id !== false)
        {
            $system_token = $this->user_tokens_model->get_token($user_id, $token);

            if($system_token !== false)
            {
                $this->response($this->success($system_token));
                exit;
            }
            else
            {
                $this->response($this->error(self::msg_token_invalid_id, self::msg_token_invalid_text));
                exit;
            }
        }
        else
        {
            $this->response($this->error(self::msg_missing_parameters_id, self::msg_missing_parameters_text));
            exit;
        }
    }
}