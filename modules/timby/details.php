<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Timby extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'TIMBY Module'
			),
			'description' => array(
				'en' => 'This is the main TIMBY module.'
			),
			'frontend' => TRUE,
			'backend' => TRUE,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
			'sections' => array(
				'reports' => array(
					'name' 	=> 'timby:reports', // These are translated from your language file
					'uri' 	=> 'admin/timby',
				),
                'categories' => array(
                    'name' 	=> 'timby:categories', // These are translated from your language file
                    'uri' 	=> 'admin/timby/categories',
                    'shortcuts' => array(
                        'create' => array(
                            'name' 	=> 'timby:create',
                            'uri' 	=> 'admin/timby/categories/create',
                            'class' => 'add'
                        )
                    )
                )
            )
		);
	}

	public function install()
	{
        // Clean up first
		$this->uninstall();

		$reports = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '250'
            ),
            'slug' => array(
                'type' => 'VARCHAR',
                'constraint' => '250'
            ),
            'category' => array(
                'type' => 'INT',
                'default' => 0,
            ),
            'user_id' => array(
                'type' => 'INT',
            ),
            'lat' => array(
                'type' => 'float',
                'default' => 0,
            ),
            'long' => array(
                'type' => 'float',
                'default' => 0,
            ),
            'created_on' => array(
                'type' => 'datetime',
            ),
            'modified_on' => array(
                'type' => 'datetime',
            ),
        );

        $report_sequence = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'report_id' => array(
                'type' => 'BIGINT',
            ),
            'item_type' => array(
                'type' => 'INT',
            ),
            'item_id' => array(
                'type' => 'BIGINT',
            ),
            'sequence' => array(
                'type' => 'INT',
            ),
            'created_on' => array(
                'type' => 'datetime',
            ),
            'modified_on' => array(
                'type' => 'datetime',
            ),
        );

        $report_narratives = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'report_id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
            ),
            'narrative' => array(
                'type' => 'TEXT',
            ),
            'created_on' => array(
                'type' => 'datetime',
            ),
            'modified_on' => array(
                'type' => 'datetime',
            ),
        );

        $report_images = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'report_id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
            ),
            'image_path' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
            ),
            'image' => array(
                'type' => 'TEXT',
            ),
            'created_on' => array(
                'type' => 'datetime',
            ),
            'modified_on' => array(
                'type' => 'datetime',
            ),
        );

        $report_videos = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'report_id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
            ),
            'video_path' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
            ),
            'video' => array(
                'type' => 'TEXT',
            ),
            'created_on' => array(
                'type' => 'datetime',
            ),
            'modified_on' => array(
                'type' => 'datetime',
            ),
        );

        $report_categories = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'category' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
            ),
            'slug' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
            ),
        );

        // Create the upload paths
        is_dir($this->upload_path.'timby/images') OR @mkdir($this->upload_path.'timby/images',0777,TRUE);
        is_dir($this->upload_path.'timby/videos') OR @mkdir($this->upload_path.'timby/videos',0777,TRUE);

        // Create the tables

        $this->dbforge->add_field($reports);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('reports');

        $this->dbforge->add_field($report_sequence);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_sequence');

        $this->dbforge->add_field($report_videos);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_videos');

        $this->dbforge->add_field($report_images);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_images');

        $this->dbforge->add_field($report_narratives);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_narratives');

        $this->dbforge->add_field($report_categories);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_categories');

		return true;
	}

	public function uninstall()
	{
        // Remove tables
        $this->dbforge->drop_table('reports');
        $this->dbforge->drop_table('report_sequence');
        $this->dbforge->drop_table('report_narratives');
        $this->dbforge->drop_table('report_videos');
        $this->dbforge->drop_table('report_images');
        $this->dbforge->drop_table('report_categories');

        // Remove settings
        $this->db->delete('settings', array('module' => 'timby'));
	}


	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
	}
}
/* End of file details.php */
