<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Timby extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'TIMBY'
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
                ),
                'sectors' => array(
                    'name' 	=> 'timby:sectors', // These are translated from your language file
                    'uri' 	=> 'admin/timby/sectors',
                    'shortcuts' => array(
                        'create' => array(
                            'name' 	=> 'timby:create',
                            'uri' 	=> 'admin/timby/sectors/create',
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
            'sector' => array(
                'type' => 'INT',
                'default' => 0,
            ),
            'company' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
                'default' => '',
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
            'approved' => array(
                'type' => 'INT',
                'default' => 0,
            ),
            'deleted' => array(
                'type' => 'INT',
                'default' => 0,
            ),
            'carto_db_id' => array(
                'type' => 'BIGINT',
                'default' => 0,
            ),
            'report_date' => array(
                'type' => 'datetime',
            ),
            'created_on' => array(
                'type' => 'datetime',
            ),
            'modified_on' => array(
                'type' => 'datetime',
                'null' => true,
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
                'null' => true,
            ),
        );

        $report_narratives = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '250'
            ),
            'report_id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
            ),
            'narrative' => array(
                'type' => 'TEXT',
            ),
            'deleted' => array(
                'type' => 'INT',
                'default' => 0,
            ),
            'created_on' => array(
                'type' => 'datetime',
            ),
            'modified_on' => array(
                'type' => 'datetime',
                'null' => true,
            ),
        );

        $report_images = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '250'
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
            'deleted' => array(
                'type' => 'INT',
                'default' => 0,
            ),
            'created_on' => array(
                'type' => 'datetime',
            ),
            'modified_on' => array(
                'type' => 'datetime',
                'null' => true,
            ),
        );

        $report_videos = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '250'
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
            'deleted' => array(
                'type' => 'INT',
                'default' => 0,
            ),
            'created_on' => array(
                'type' => 'datetime',
            ),
            'modified_on' => array(
                'type' => 'datetime',
                'null' => true,
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

        $report_sectors = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'sector' => array(
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

        $this->dbforge->add_field($report_sectors);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_sectors');

        // Module settings
        $youtube_api_key = array(
            'slug' => 'youtube_api_key',
            'title' => 'YouTube API Key',
            'description' => 'API Key used to manage videos on YouTube',
            '`default`' => 'YAPIKey',
            '`value`' => 'YAPIKey',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $youtube_api_key);

        $cartodb_user_name = array(
            'slug' => 'cartodb_user_name',
            'title' => 'CartoDB User Name',
            'description' => 'CartoDB User name',
            '`default`' => 'username',
            '`value`' => 'username',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $cartodb_user_name);

        $cartodb_api_key = array(
            'slug' => 'cartodb_api_key',
            'title' => 'CartoDB API Key',
            'description' => 'CartoDB API Key',
            '`default`' => 'CAPIKey',
            '`value`' => 'CAPIKey',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $cartodb_api_key);

        $decryption_key = array(
            'slug' => 'timby_decription_key',
            'title' => 'Decryption Key',
            'description' => 'Decryption Key',
            '`default`' => 'KEY',
            '`value`' => 'KEY',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $decryption_key);

        $report_soft_delete = array(
            'slug' => 'report_soft_delete',
            'title' => 'Soft delete reports and objects?',
            'description' => 'Soft delete reports and objects?',
            '`default`' => 'true',
            '`value`' => 'true',
            'type' => 'radio',
            '`options`' => 'true=Yes|false=No',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $report_soft_delete);

        // Return status

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
        $this->dbforge->drop_table('report_sectors');

        // Remove settings
        $this->db->delete('settings', array('module' => 'timby'));

        return true;
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
