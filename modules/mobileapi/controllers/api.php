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

        // Models
        $this->load->model('users_model');
        $this->load->model('keys_model');
        $this->load->model('user_tokens_model');

        // Libraries
        $this->load->library('mobileapi_utils');

        if(ENVIRONMENT == PYRO_PRODUCTION)
        {
            if(strtolower(substr(current_url(), 4, 1)) != 's')
            {
                redirect(str_replace('http:', 'https:', current_url()));
            }
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
            $token = null;

            if(is_array($login_user_status))
                $token = $this->user_tokens_model->create_token($login_user_status[0]->id,
                    $this->mobileapi_utils->get_client_ip());
            else
                $token = $this->user_tokens_model->create_token($login_user_status->id,
                    $this->mobileapi_utils->get_client_ip());

            if($token != false)
            {
                if(is_array($login_user_status))
                    $this->response($this->success(array("user_id" => $login_user_status[0]->id, "token" => $token)));
                else
                    $this->response($this->success(array("user_id" => $login_user_status->id, "token" => $token)));
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

    /**
     * Logout
     */

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

        if($token != false && $user_id != false)
        {
            $system_token = $this->user_tokens_model->get_token($user_id, $this->mobileapi_utils->get_client_ip());

            if($system_token != false)
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

    /**
     * Create a new report
     */

    public function createreport_post()
    {
        $token = $this->input->post('token');
        $user_id = $this->input->post('user_id');

        $title = $this->input->post('title');
        $category = $this->input->post('category');
        $report_date = $this->input->post('report_date');
        $lat = $this->input->post('lat') != false ? $this->input->post('lat') : 0;
        $long = $this->input->post('long') != false ? $this->input->post('long') : 0;

        if($token != false && $user_id != false && $title != false && $category != false && $report_date != false)
        {
            $post_vars = $this->input->post();

            if(!isset($post_vars['lat']))
            {
                $post_vars['lat'] = $lat;
            }

            if(!isset($post_vars['long']))
            {
                $post_vars['long'] = $long;
            }

            $system_token = $this->user_tokens_model->get_token($user_id, $this->mobileapi_utils->get_client_ip());

            if($system_token == $token)
            {
                $this->response($this->success(Events::trigger('create_report', $post_vars, 'array')));
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

    /**
     * Update a current report
     */

    public function updatereport_post()
    {
        $token = $this->input->post('token');
        $user_id = $this->input->post('user_id');

        $report_id = $this->input->post('report_id');
        $title = $this->input->post('title');
        $category = $this->input->post('category');
        $report_date = $this->input->post('report_date');
        $lat = $this->input->post('lat') != false ? $this->input->post('lat') : 0;
        $long = $this->input->post('long') != false ? $this->input->post('long') : 0;

        if($token != false && $user_id != false && $title != false && $category != false &&
            $report_id != false && $report_date != false)
        {
            $post_vars = $this->input->post();

            if(!isset($post_vars['lat']))
            {
                $post_vars['lat'] = $lat;
            }

            if(!isset($post_vars['long']))
            {
                $post_vars['long'] = $long;
            }

            $system_token = $this->user_tokens_model->get_token($user_id, $this->mobileapi_utils->get_client_ip());

            if($system_token == $token)
            {
                $this->response($this->success(Events::trigger('update_report', $post_vars, 'array')));
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

    /**
     * Delete a report
     */

    public function deletereport_post()
    {
        $token = $this->input->post('token');
        $user_id = $this->input->post('user_id');

        $report_id = $this->input->post('report_id');

        if($token != false && $user_id != false && $report_id != false)
        {
            $post_vars = $this->input->post();

            $system_token = $this->user_tokens_model->get_token($user_id, $this->mobileapi_utils->get_client_ip());

            if($system_token == $token)
            {
                $this->response($this->success(Events::trigger('delete_report', $post_vars, 'array')));
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

    /**
     * Insert a new object
     */

    public function insertobject_post()
    {
        $token = $this->input->post('token');
        $user_id = $this->input->post('user_id');

        $sequence_number = $this->input->post("sequence");
        $object_type = $this->input->post("object_type");
        $report_id = $this->input->post("report_id");
        $object_id = $this->input->post("object_id");
        $narrative = $this->input->post("narrative");
        $report_date = $this->input->post('report_date');

        if($token != false && $user_id != false && $report_id != false && $sequence_number != false &&
            $object_type != false && $object_id != false && $narrative != false && $report_date != false)
        {
            $post_vars = $this->input->post();
            $post_vars['upload_path'] = $this->upload_path;

            $system_token = $this->user_tokens_model->get_token($user_id, $this->mobileapi_utils->get_client_ip());

            if($system_token == $token)
            {
                $this->response($this->success(Events::trigger('insert_object', $post_vars, 'array')));
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

    /**
     * Update an object
     */

    public function updateobject_post()
    {
        $token = $this->input->post('token');
        $user_id = $this->input->post('user_id');

        $sequence_number = $this->input->post("sequence");
        $object_type = $this->input->post("object_type");
        $report_id = $this->input->post("report_id");
        $object_id = $this->input->post("object_id");
        $narrative = $this->input->post("narrative");
        $report_date = $this->input->post('report_date');

        if($token != false && $user_id != false && $report_id != false && $sequence_number != false &&
            $object_type != false && $object_id != false && $narrative != false && $report_date != false)
        {
            $post_vars = $this->input->post();
            $post_vars['upload_path'] = $this->upload_path;

            $system_token = $this->user_tokens_model->get_token($user_id, $this->mobileapi_utils->get_client_ip());

            if($system_token == $token)
            {
                $this->response($this->success(Events::trigger('update_object', $post_vars, 'array')));
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

    /**
     * Delete an object
     */

    public function deleteobject_post()
    {
        $token = $this->input->post('token');
        $user_id = $this->input->post('user_id');

        $object_type = $this->input->post('object_type');
        $object_id = $this->input->post('object_id');

        if($token != false && $user_id != false && $object_type != false && $object_id != false)
        {
            $post_vars = $this->input->post();
            $post_vars['upload_path'] = $this->upload_path;

            $system_token = $this->user_tokens_model->get_token($user_id, $this->mobileapi_utils->get_client_ip());

            if($system_token == $token)
            {
                $this->response($this->success(Events::trigger('delete_object', $post_vars, 'array')));
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