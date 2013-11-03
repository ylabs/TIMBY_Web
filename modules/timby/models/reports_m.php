<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Main reports model
 */
class Reports_m extends BF_Model {
    protected $table_name = "reports";
    protected $set_created	= TRUE;
    protected $set_modified = TRUE;
    protected $date_format = 'datetime';

	public function __construct()
	{		
		parent::__construct();

        // Loading related models
        $this->load->model('timby/report_sequence_m');
        $this->load->model('timby/report_narratives_m');
        $this->load->model('timby/report_videos_m');
        $this->load->model('timby/report_images_m');

        // Libraries
        $this->load->library("timby/timby_utilities");
	}

    public function insert($data = NULL)
    {
        if(isset($data["category"]))
        {
            $data["slug"] = $this->timby_utilities->get_slug($data["category"]);
        }

        return parent::insert($data);
    }

    public function update($where = NULL, $data = NULL)
    {
        if(isset($data["category"]))
        {
            $data["slug"] = $this->timby_utilities->get_slug($data["category"]);
        }

        return parent::update($where, $data);
    }

    public function delete($id = NULL)
    {
        $result = parent::delete($id);

        if($result)
        {
            $this->sequence()->delete_where(array("report_id" => $id));
            $this->sequence()->delete_where(array("report_id" => $id));
            $this->narratives()->delete_where(array("report_id" => $id));
            $this->images()->delete_where(array("report_id" => $id));
            $this->videos()->delete_where(array("report_id" => $id));
        }

        return $result;
    }

    public function sequence()
    {
        return $this->report_sequence_m;
    }

    public function narratives()
    {
        return $this->report_narratives_m;
    }

    public function images()
    {
        return $this->report_images_m;
    }

    public function videos()
    {
        return $this->report_videos_m;
    }
}
