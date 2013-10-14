<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_MobileAPI extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array (
				'en' => 'Mobile API'
			),
			'description' => array (
				'en' => 'Mobile API (Timby Mobile)'
			),
			'frontend' => TRUE,
			'backend' => TRUE,
			'menu' => 'TIMBY', // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
			'sections' => array (
				'users' => array (
					'name' 	=> 'mobileapi:users', // These are translated from your language file
					'uri' 	=> 'admin/mobileapi',
                    'shortcuts' => array(
                        'create' => array(
                            'name' 	=> 'mobileapi:create',
                            'uri' 	=> 'admin/mobileapi/create',
                            'class' => 'add'
                        )
                    )
                ),
                'keys' => array (
                    'name' 	=> 'mobileapi:keys', // These are translated from your language file
                    'uri' 	=> 'admin/mobileapi/keys',
                    'shortcuts' => array(
                        'create' => array(
                            'name' 	=> 'mobileapi:create',
                            'uri' 	=> 'admin/mobileapi/keys/create',
                            'class' => 'add'
                        )
                    )
                )
            )
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('api_users');
        $this->dbforge->drop_table('api_keys');

        $this->db->delete('settings', array('module' => 'mobileapi'));

		$users = array (
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '200'
            ),
            'active' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => 1,
            ),
        );

        $keys = array (
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'key' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'active' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => 1,
            ),
        );

        $user_keys = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'key_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
        );

        $user_tokens = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'token' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'creation_time' => array(
                'type' => 'TIMESTAMP',
            ),
        );

		// API Users
        $this->dbforge->add_field($users);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('api_users');

        // API Keys
        $this->dbforge->add_field($keys);
		$this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('api_keys');

        // API User Keys
        $this->dbforge->add_field($user_keys);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('api_user_keys');

        // API User Keys
        $this->dbforge->add_field($user_tokens);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('api_user_tokens');

        // Sample settings
        /*
        $sample_setting = array(
            'slug' => 'sample_setting',
            'title' => 'Sample Setting',
            'description' => 'A Yes or No option for the Sample module',
            '`default`' => '1',
            '`value`' => '1',
            'type' => 'select',
            '`options`' => '1=Yes|0=No',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'sample'
        );

        $this->db->insert('settings', $sample_setting);
        */

        return true;
	}

	public function uninstall()
	{
		$this->dbforge->drop_table('api_users');
        $this->dbforge->drop_table('api_keys');

        // Settings:
        // $this->db->delete('settings', array('module' => 'sample'));

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
