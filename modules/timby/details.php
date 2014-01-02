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
                    'shortcuts' => array(
                        'create' => array(
                            'name' 	=> 'timby:extract_zip',
                            'uri' 	=> 'admin/timby/extract_zips',
                            'class' => 'add'
                        )
                    )
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
                'constraint' => '250',
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
            'slug' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
            ),
            'category' => array(
                'type' => 'INT',
                'default' => 0,
            ),
            'sector' => array(
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

        $report_posts = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'report_id' => array(
                'type' => 'BIGINT',
            ),
            'post' => array(
                'type' => 'TEXT',
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

        $report_multimedia = array(
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
            'multimedia_path' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
            ),
            'multimedia' => array(
                'type' => 'TEXT',
            ),
            'type' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'video',
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

        $report_entities = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'entity' => array(
                'type' => 'VARCHAR',
                'constraint' => '250'
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '250'
            ),
            'report_id' => array(
                'type' => 'BIGINT',
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

        $tagged_reports = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'tag_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'object_id' => array(
                'type' => 'BIGINT',
            ),
            'report_id' => array(
                'type' => 'BIGINT',
            )
        );

        // Create the upload paths
        is_dir($this->upload_path.'timby/images') OR @mkdir($this->upload_path.'timby/images',0777,TRUE);
        is_dir($this->upload_path.'timby/multimedia') OR @mkdir($this->upload_path.'timby/multimedia',0777,TRUE);

        // Create the tables

        $this->dbforge->add_field($reports);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('reports');

        $this->dbforge->add_field($report_sequence);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_sequence');

        $this->dbforge->add_field($report_multimedia);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_multimedia');

        $this->dbforge->add_field($report_posts);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_posts');

        $this->dbforge->add_field($report_images);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_images');

        $this->dbforge->add_field($report_narratives);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_narratives');

        $this->dbforge->add_field($report_entities);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_entities');

        $this->dbforge->add_field($report_categories);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_categories');

        $this->dbforge->add_field($report_sectors);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('report_sectors');

        $this->dbforge->add_field($tagged_reports);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tagged_reports');

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

        // YouTube Secret

        $youtube_secret = array(
            'slug' => 'youtube_secret',
            'title' => 'YouTube Secret Key',
            'description' => 'Secret Key used to manage videos on YouTube',
            '`default`' => 'YAPIKey',
            '`value`' => 'YAPIKey',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $youtube_secret);

        // YouTube Algorithm

        $youtube_algorithm = array(
            'slug' => 'youtube_algorithm',
            'title' => 'YouTube Algorithm',
            'description' => 'YouTube Algorithm',
            '`default`' => 'Algorithm',
            '`value`' => 'Algorithm',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $youtube_algorithm);

        // YouTube Access Token

        $youtube_access_token = array(
            'slug' => 'youtube_access_token',
            'title' => 'YouTube Access Token',
            'description' => 'YouTube Access Token',
            '`default`' => 'YAccessToken',
            '`value`' => 'YAccessToken',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $youtube_access_token);

        // YouTube Enabled

        $youtube_enabled = array(
            'slug' => 'youtube_enabled',
            'title' => 'YouTube Enabled',
            'description' => 'Is YouTube Enabled?',
            '`default`' => 'false',
            '`value`' => 'false',
            'type' => 'radio',
            '`options`' => 'true=Yes|false=No',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $youtube_enabled);

        // CartoDB user name

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

        // CartoDB api key

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

        // Enable Vimeo?

        $enable_vimeo = array(
            'slug' => 'enable_vimeo',
            'title' => 'Enable Vimeo',
            'description' => 'Enable Vimeo API?',
            '`default`' => 'false',
            '`value`' => 'false',
            'type' => 'radio',
            '`options`' => 'true=Yes|false=No',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $enable_vimeo);

        // Vimeo Consumer Key

        $vimeo_consumer_key = array(
            'slug' => 'vimeo_consumer_key',
            'title' => 'Consumer Key',
            'description' => 'Vimeo Consumer Key',
            '`default`' => 'VCKey',
            '`value`' => 'VCKey',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $vimeo_consumer_key);

        // Vimeo Consumer Secret

        $vimeo_consumer_secret = array(
            'slug' => 'vimeo_consumer_secret',
            'title' => 'Consumer Secret',
            'description' => 'Vimeo Consumer Secret',
            '`default`' => 'VCSecret',
            '`value`' => 'VCSecret',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $vimeo_consumer_secret);

        // Number of reports on display in the blog tagging

        $timby_num_reports_blog = array(
            'slug' => 'timby_num_reports_blog',
            'title' => 'Number of reports to show in the blog section',
            'description' => 'Number of reports to show in the blog section',
            '`default`' => '50',
            '`value`' => '50',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $timby_num_reports_blog);

        // Enable decryption

        $enable_decryption = array(
            'slug' => 'timby_enable_decryption',
            'title' => 'Enable Decryption',
            'description' => 'Enable Decryption',
            '`default`' => 'false',
            '`value`' => 'false',
            'type' => 'radio',
            '`options`' => 'true=Yes|false=No',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $enable_decryption);

        // Overall decryption key

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

        // Report soft delete?

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

        // Make submitted reports public?

        $submitted_reports_public = array(
            'slug' => 'reports_are_public',
            'title' => 'Make submitted reports public on approval?',
            'description' => 'Allows the front end to view approved reports',
            '`default`' => 'false',
            '`value`' => 'false',
            'type' => 'radio',
            '`options`' => 'true=Yes|false=No',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'timby'
        );

        $this->db->insert('settings', $submitted_reports_public);

        // Return status

		return true;
	}

	public function uninstall()
	{
        // Remove tables
        $this->dbforge->drop_table('reports');
        $this->dbforge->drop_table('report_sequence');
        $this->dbforge->drop_table('report_posts');
        $this->dbforge->drop_table('report_narratives');
        $this->dbforge->drop_table('report_multimedia');
        $this->dbforge->drop_table('report_images');
        $this->dbforge->drop_table('report_categories');
        $this->dbforge->drop_table('report_entities');
        $this->dbforge->drop_table('report_sectors');
        $this->dbforge->drop_table('tagged_reports');

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
