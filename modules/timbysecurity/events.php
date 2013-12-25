<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * TIMBY Security Events Class
 * 
 * @package        PyroCMS
 * @author        Team TIMBY
 * @website        http://timby.org
 */
class Events_TimbySecurity {
    
    public function __construct()
    {
        ci()->load->model('timbysecurity/security_log_m');

        // Report events
        Events::register('report_approved', array($this, 'report_approved'));
		Events::register('report_disapproved', array($this, 'report_disapproved'));
        Events::register('report_deleted', array($this, 'report_deleted'));
        Events::register('report_edited', array($this, 'report_edited'));
        Events::register('report_post_changed', array($this, 'report_post_changed'));
        Events::register('zip_files_extracted', array($this, 'zip_files_extracted'));

        // Report master events
        Events::register('create_report', array($this, 'create_report'));
        Events::register('update_report', array($this, 'update_report'));
        Events::register('delete_report', array($this, 'delete_report'));

        // Categories
        Events::register('report_category_created', array($this, 'report_category_created'));
        Events::register('report_category_edited', array($this, 'report_category_edited'));
        Events::register('report_category_deleted', array($this, 'report_category_deleted'));

        // Sectors
        Events::register('report_sector_created', array($this, 'report_sector_created'));
        Events::register('report_sector_edited', array($this, 'report_sector_edited'));
        Events::register('report_sector_deleted', array($this, 'report_sector_deleted'));
    }

    // Reports

    public function report_approved($ids)
    {
        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];
        $report_id = $ids["report_id"];

        $description = "Report {$report_id} approved by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->report_approved_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }

    public function report_disapproved($ids)
    {
        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];
        $report_id = $ids["report_id"];

        $description = "Report {$report_id} disapproved by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->report_disapproved_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }

    public function report_deleted($ids)
    {
        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];
        $report_id = $ids["report_id"];

        $description = "Report {$report_id} deleted by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->report_deleted_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }

    public function report_edited($ids)
    {
        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];
        $report_id = $ids["report_id"];

        $description = "Report {$report_id} edited by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->report_edited_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }

    public function report_post_changed($ids)
    {
        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];
        $report_id = $ids["report_id"];

        $description = "Report post for {$report_id} changed by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->report_post_changed_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }

    public function zip_files_extracted($ids)
    {
        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];

        $description = "Report zip filed extracted by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->zip_files_extracted_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }

    // Mobile events

    public function get_mobile_user_name($mobile_user_id)
    {
        ci()->load->model('mobileapi/users_model');
        $user = ci()->users_model->find_by('id', $mobile_user_id);

        if($user)
            return $user->name;

        return false;
    }

    public function create_report($post_vars)
    {
        $user_id = $post_vars["user_id"];
        $user_name = $this->get_mobile_user_name($user_id);

        $description = "Report created by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->create_report_id(),
            'description' => $description,
            'mobile_user_id' => $user_id,
        ));
    }

    public function update_report($post_vars)
    {
        $user_id = $post_vars["user_id"];
        $user_name = $this->get_mobile_user_name($user_id);
        $report_id = $post_vars["report_id"];

        $description = "Report {$report_id} updated by mobile user {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->update_report_id(),
            'description' => $description,
            'mobile_user_id' => $user_id,
        ));
    }

    public function delete_report($post_vars)
    {
        $user_id = $post_vars["user_id"];
        $user_name = $this->get_mobile_user_name($user_id);
        $report_id = $post_vars["report_id"];

        $description = "Report {$report_id} deleted by mobile user {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->delete_report_id(),
            'description' => $description,
            'mobile_user_id' => $user_id,
        ));
    }

    // Report categories

    public function report_category_created($ids)
    {

        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];
        $category_id = $ids["category_id"];

        $description = "Category {$category_id} created by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->report_category_created_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }

    public function report_category_edited($ids)
    {
        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];
        $category_id = $ids["category_id"];

        $description = "Category {$category_id} edited by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->report_category_edited_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }

    public function report_category_deleted($ids)
    {
        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];
        $category_id = $ids["category_id"];

        $description = "Category {$category_id} deleted by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->report_category_deleted_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }

    // Report sectors

    public function report_sector_created($ids)
    {
        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];
        $sector_id = $ids["sector_id"];

        $description = "Sector {$sector_id} created by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->report_sector_created_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }

    public function report_sector_edited($ids)
    {
        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];
        $sector_id = $ids["sector_id"];

        $description = "Sector {$sector_id} edited by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->report_sector_edited_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }

    public function report_sector_deleted($ids)
    {
        $user_id = $ids["user_id"];
        $user_name = $ids["user_name"];
        $sector_id = $ids["sector_id"];

        $description = "Sector {$sector_id} deleted by {$user_name}";

        ci()->security_log_m->insert(array(
            'activity_id' => ci()->security_log_m->report_sector_deleted_id(),
            'description' => $description,
            'web_user_id' => $user_id,
        ));
    }
}
/* End of file events.php */