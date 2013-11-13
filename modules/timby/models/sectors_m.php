<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Main categories model
 */
class Sectors_m extends BF_Model {
    protected $table_name = "report_sectors";
    protected $set_created	= FALSE;
    protected $set_modified = FALSE;
    protected $date_format = 'datetime';

    public function __construct()
    {
        parent::__construct();

        // Libraries
        $this->load->library("timby/timby_utilities");
    }

    public function insert($data = NULL)
    {
        if(isset($data["sector"]))
        {
            $data["slug"] = $this->timby_utilities->get_slug($data["sector"]);
        }

        return parent::insert($data);
    }

    public function update($where = NULL, $data = NULL)
    {
        if(isset($data["sector"]))
        {
            $data["slug"] = $this->timby_utilities->get_slug($data["sector"]);
        }

        return parent::update($where, $data);
    }

    public function delete($id = NULL)
    {
        $this->load->model("reports_m");

        $status = parent::delete($id);

        if($status)
        {
            $reports = $this->reports_m->find_all_by(array("sector" => $id));

            if($reports != false)
            {
                foreach($reports as $report)
                {
                    $this->reports_m->delete($report->id);
                }
            }
        }

        return $status;
    }
}
