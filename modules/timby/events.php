<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Event handler for the TIMBY Module
 */
class Events_Timby {
    
    public function __construct()
    {
        ci()->load->library('timby/api_handlers');

        // Report events
        Events::register('create_report', array($this, 'create_report'));
        Events::register('update_report', array($this, 'update_report'));
        Events::register('delete_report', array($this, 'delete_report'));

        // Report data events
        Events::register('get_categories', array($this, 'get_categories'));
        Events::register('insert_object', array($this, 'insert_object'));
        Events::register('update_object', array($this, 'update_object'));
        Events::register('delete_object', array($this, 'delete_object'));

        // Media functions - useful for things like decryption
        Events::register('media_uploaded', array($this, 'media_uploaded'));
    }

    public function get_categories()
    {
        // Manage this event
        return ci()->api_handlers->get_categories();
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
}
/* End of file events.php */