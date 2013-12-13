<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Event handler for the TIMBY Module
 */
class Events_Timby {

    const tag_type_blog = 'blog';

    public function __construct()
    {
        ci()->load->library('timby/api_handlers');

        // Report events
        Events::register('create_report', array($this, 'create_report'));
        Events::register('update_report', array($this, 'update_report'));
        Events::register('delete_report', array($this, 'delete_report'));

        // Report data events
        Events::register('get_categories', array($this, 'get_categories'));
        Events::register('get_sectors', array($this, 'get_sectors'));
        Events::register('insert_object', array($this, 'insert_object'));
        Events::register('update_object', array($this, 'update_object'));
        Events::register('delete_object', array($this, 'delete_object'));

        // Blog data events
        Events::register('post_updated', array($this, 'post_updated'));
        Events::register('post_created', array($this, 'post_created'));
        Events::register('post_deleted', array($this, 'post_deleted'));

        // Media functions - useful for things like decryption
        Events::register('media_uploaded', array($this, 'media_uploaded'));
    }

    public function get_categories()
    {
        // Manage this event
        return ci()->api_handlers->get_categories();
    }

    public function get_sectors()
    {
        // Manage this event
        return ci()->api_handlers->get_sectors();
    }
    
    public function create_report($post_vars)
    {
        // Manage this event
        return ci()->api_handlers->create_report($post_vars);
    }

    public function update_report($post_vars)
    {
        // Manage this event
        return ci()->api_handlers->update_report($post_vars['report_id'], $post_vars);
    }

    public function delete_report($post_vars)
    {
        // Manage this event
        return ci()->api_handlers->delete_report($post_vars);
    }

    public function insert_object($post_vars)
    {
        // Manage this event
        return ci()->api_handlers->insert_report_object($post_vars['upload_path'], $post_vars);
    }

    public function update_object($post_vars)
    {
        // Manage this event
        return ci()->api_handlers->update_report_object($post_vars['upload_path'], $post_vars);
    }

    public function delete_object($post_vars)
    {
        // Manage this event
        return ci()->api_handlers->delete_report_object($post_vars['upload_path'], $post_vars);
    }

    public function media_uploaded($upload_data)
    {
        // Decrypt media
        $file_name = $upload_data["file_name"];
    }

    private function load_tags_models()
    {
        ci()->load->model('timby/tagged_reports_m');
    }

    public function post_updated($id)
    {
        // Update streams data
        $post_vars = ci()->input->post();

        if($post_vars)
        {
            if(isset($post_vars["stream_reporttag"]))
            {
                $this->load_tags_models();
                ci()->tagged_reports_m->delete_where(array('object_id' => $id, 'tag_type' => self::tag_type_blog));

                foreach($post_vars["stream_reporttag"] as $report_id)
                {
                    ci()->tagged_reports_m->insert(array('object_id' => $id, 'tag_type' => self::tag_type_blog,
                        'report_id' => $report_id));
                }
            }
        }
    }

    public function post_created($id)
    {
        // Update streams data
        $post_vars = ci()->input->post();

        if($post_vars)
        {
            if(isset($post_vars["stream_reporttag"]))
            {
                $this->load_tags_models();

                foreach($post_vars["stream_reporttag"] as $report_id)
                {
                    ci()->tagged_reports_m->insert(array('object_id' => $id, 'tag_type' => self::tag_type_blog,
                        'report_id' => $report_id));
                }
            }
        }
    }

    public function post_deleted($ids)
    {
        // Delete report tags (cleanup)
        $post_vars = ci()->input->post();

        if($post_vars)
        {
            if(isset($post_vars["stream_reporttag"]))
            {
                $this->load_tags_models();

                foreach($ids as $id)
                {
                    ci()->tagged_reports_m->delete_where(array('object_id' => $id, 'tag_type' => self::tag_type_blog));
                }
            }
        }
    }

    public function post_published($id)
    {
        // Push to cartodb
        $account = Settings::get('cartodb_user_name');
        $api_key = Settings::get('cartodb_api_key');

        ci()->load->model('blog/blog_m');
        ci()->load->model('timby/reports_m');
        $this->load_tags_models();

        $blog_entry = ci()->blog_m->get($id);
        $tagged_reports = ci()->tagged_reports_m->find_by('object_id', $id);

        if(!$tagged_reports || !$blog_entry)
            return false;

        $report = ci()->reports_m->find_by('id', $tagged_reports->report_id);

        if(!$report)
            return false;

        $timestamp = strtotime($blog_entry->created);

        $the_geom = '{"type":"Point","coordinates":['.$report->lat.','.$report->long.']}';
        $category = $report->category;
        $image_url = "";
        $sector = $report->sector;
        $item_date = $blog_entry->created;
        $timby_id = $id;
        $title = $blog_entry->title;
        $url = site_url("blog/".date("Y/m", $timestamp)."/".$blog_entry->slug);

        $statement = "INSERT INTO dashboard(the_geom, category, image_url, item_date, sector, timby_id,
            title, url) VALUES ('{$the_geom}', {$$category}, '{$image_url}', '{$item_date}',
            {$sector}, {$timby_id}, '{$title}', '{$url}')";

        ci()->load->library('timby/cartodb');
        $json_result = ci()->cartodb->call_sql_api($account, $api_key, $statement);

        $result = json_decode($json_result);

        return $result;
    }

    public function post_unpublished($id)
    {
        // Remove from cartodb
        $statement = "DELETE FROM dashboard WHERE id = {$id}";

        $account = Settings::get('cartodb_user_name');
        $api_key = Settings::get('cartodb_api_key');

        ci()->load->library('timby/cartodb');
        $json_result = ci()->cartodb->call_sql_api($account, $api_key, $statement);

        $result = json_decode($json_result);

        return $result;
    }
}
/* End of file events.php */