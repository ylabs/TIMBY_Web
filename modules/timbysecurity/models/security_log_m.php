<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Security Log Model
 *
 * @author 		Team TIMBY
 * @website		http://timby.org
 * @package 	PyroCMS
 */
class Security_log_m extends BF_Model {
    protected $table_name = "security_log";
    protected $set_created	= TRUE;
    protected $set_modified = TRUE;
    protected $date_format = 'datetime';

    // Constants

    public function report_approved_id()
    {
        return "001";
    }

    public function report_disapproved_id()
    {
        return "002";
    }

    public function report_deleted_id()
    {
        return "003";
    }

    public function report_edited_id()
    {
        return "004";
    }

    public function report_post_changed_id()
    {
        return "005";
    }

    public function zip_files_extracted_id()
    {
        return "006";
    }

    public function create_report_id()
    {
        return "007";
    }

    public function update_report_id()
    {
        return "008";
    }

    public function delete_report_id()
    {
        return "009";
    }

    public function report_category_created_id()
    {
        return "010";
    }

    public function report_category_edited_id()
    {
        return "011";
    }

    public function report_category_deleted_id()
    {
        return "012";
    }

    public function report_sector_created_id()
    {
        return "013";
    }

    public function report_sector_edited_id()
    {
        return "014";
    }

    public function report_sector_deleted_id()
    {
        return "015";
    }
}
