<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * The Reports verification admin landing page
 */
class Admin extends Admin_Controller
{
	protected $section = 'reports';

	public function __construct()
	{
		parent::__construct();

        // Models
        $this->load->model('reports_m');
        $this->load->model('categories_m');

        // Language
        $this->lang->load('timby');
	}

	/**
	 * List all items
	 */
	public function index()
	{
        $total_rows = $this->reports_m
            ->where('deleted', 0)
            ->count_all();

        $pagination = create_pagination('admin/timby/index', $total_rows);

		// here we use MY_Model's get_all() method to fetch everything
		$items = $this->reports_m
            ->order_by('id', 'desc')
            ->where('deleted', 0)
            ->limit($pagination['limit'], $pagination['offset'])
            ->find_all();

		// Build the view with sample/views/admin/items.php
		$this->template
			->title($this->module_details['name'])
			->set('items', $items)
            ->set('pagination', $pagination)
			->build('admin/reports/index');
	}

    public function post($report_id)
    {
        // Has submit been done?

        $post_vars = $this->input->post();

        if($post_vars)
        {
            if(isset($post_vars['post']))
            {
                $post = $post_vars['post'];

                $result = $this->reports_m->posts()->update(array('report_id' => $report_id),
                    array('post' => $post));

                if($result)
                {
                    Events::trigger('report_post_changed', array('report_id' => $report_id,
                        'user_id' => $this->current_user->id,
                        'user_name' => $this->current_user->username), 'array');
                    redirect('admin/timby/');
                }
            }
        }

        // The report post
        $report_post = $this->reports_m->posts()->find_by(array('report_id' => $report_id));
        $post_text = "";

        if($report_post)
        {
            $post_text = $report_post->post;
        }

        $this->template
            ->append_js('module::tinymce/tinymce.min.js')
            ->title($this->module_details['name'])
            ->set('report_id', $report_id)
            ->set('post_text', $post_text)
            ->build('admin/reports/post');
    }

    public function approve($report_id)
    {
        $this->reports_m->update($report_id, array('approved' => 1));

        // Trigger event so that it will be handled
        Events::trigger('report_approved', array('report_id' => $report_id,
            'user_id' => $this->current_user->id,
            'user_name' => $this->current_user->username), 'array');
        redirect(site_url("admin/timby"));
    }

    public function disapprove($report_id)
    {
        $this->reports_m->update($report_id, array('approved' => 0));

        // Trigger event so that it will be handled
        Events::trigger('report_disapproved', array('report_id' => $report_id,
            'user_id' => $this->current_user->id,
            'user_name' => $this->current_user->username), 'array');
        redirect(site_url("admin/timby"));
    }

    public function delete($report_id)
    {
        $this->reports_m->delete($report_id);

        // Trigger event so that it will be handled
        Events::trigger('report_deleted', array('report_id' => $report_id,
            'user_id' => $this->current_user->id,
            'user_name' => $this->current_user->username), 'array');
        redirect(site_url("admin/timby"));
    }

    public function view($report_id)
    {
        // here we use MY_Model's get_all() method to fetch everything
        $item = $this->reports_m->get_full_report($report_id);

        // Build the view with sample/views/admin/items.php
        $this->template
            ->title($this->module_details['name'])
            ->append_js('module::leaflet/leaflet.js')
            ->append_css('module::leaflet.css')
            ->set('item', $item)
            ->set('report_id', $report_id)
            ->build('admin/reports/view');
    }

    public function edit($report_id)
    {
        $post_vars = $this->input->post();

        if($post_vars)
        {
            // Save the record
            $this->reports_m->update($report_id, $post_vars);

            // Trigger event so that it will be handled
            Events::trigger('report_edited', array('report_id' => $report_id,
                'user_id' => $this->current_user->id,
                'user_name' => $this->current_user->username), 'array');
            redirect(site_url("admin/timby"));
        }
        else
        {
            // here we use MY_Model's get_all() method to fetch everything
            $item = $this->reports_m->find($report_id);

            // Build the view with sample/views/admin/items.php
            $this->template
                ->title($this->module_details['name'])
                ->set('item', $item)
                ->set('report_id', $report_id)
                ->build('admin/reports/edit');
        }
    }

    public function extract_zips()
    {
        $this->load->library("file_upload");
        $this->file_upload->get_all_files(array(
            'user_id' => $this->current_user->id,
        ));

        Events::trigger('zip_files_extracted', array('user_id' => $this->current_user->id,
            'user_name' => $this->current_user->username), 'array');

        redirect('admin/timby');
    }
}
