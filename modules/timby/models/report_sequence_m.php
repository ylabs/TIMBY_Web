<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Main reports model
 */
class Report_sequence_m extends BF_Model {
    protected $table_name = "report_sequence";
    protected $set_created	= TRUE;
    protected $set_modified = TRUE;
    protected $date_format = 'datetime';

    public function __construct()
    {
        parent::__construct();
    }

    public function insert($data = null)
    {
        $all_after = $this->where(array("sequence >=" => $data["sequence"], "report_id" => $data["report_id"]))
            ->find_all();

        if($all_after != false)
        {
            foreach($all_after as $after_item)
            {
                parent::update($after_item->id, array("sequence" => ($after_item->sequence) + 1));
            }
        }

        return parent::insert($data);
    }

    public function update($where = null, $data = null)
    {
        return parent::update($where, $data);
    }
}
