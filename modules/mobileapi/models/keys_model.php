<?php defined('BASEPATH') or exit('No direct script access allowed');

class keys_model extends BF_Model
{
    protected $table_name = "api_keys";
    protected $set_created	= FALSE;
    protected $set_modified = FALSE;

    public function __construct()
    {
        parent::__construct();
    }

    public function find_all()
    {
        $this->load->model('users_model');

        $records = parent::find_all();

        if($records)
        {
            foreach($records as &$record)
            {
                if($record->user_id == 0)
                {
                    $record->is_user_set = false;
                }
                else
                {
                    $record->is_user_set = true;
                    $user_details = $this->users_model->find($record->user_id);

                    if($user_details)
                    {
                        $record->user_name = $user_details->name;
                    }
                }

            }
        }

        return $records;
    }

    public function assign_key_to_user($key_id, $user_id)
    {
        return $this->update(array("id" => $key_id), array('user_id' => $user_id));
    }

    public function validate_user_key($key, $user_id)
    {
        $key = $this->find_by(array('user_id' => $user_id, 'key' => $key));

        return $key;
    }
}