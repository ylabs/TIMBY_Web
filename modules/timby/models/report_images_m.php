<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Main reports model
 */
class Report_images_m extends BF_Model {
    protected $table_name = "report_images";
    protected $set_created	= TRUE;
    protected $set_modified = TRUE;
    protected $date_format = 'datetime';

    public function __construct()
    {
        parent::__construct();
    }

    public function delete($id = null, $soft_delete = true)
    {
        if($soft_delete)
        {
            $this->update($id, array('deleted' => 1));
        }
        else
        {
            parent::delete($id);
        }
    }
}
