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
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
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

		// Users
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

        // Keys
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
            'user_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => 0,
            ),
            'active' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => 1,
            ),
        );

        // Tokens
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
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'token' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'creation_time' => array(
                'type' => 'TIMESTAMP',
            ),
        );

        // Check and remove if possible
        if($this->db->table_exists('api_users'))
            $this->dbforge->drop_table('api_users');

        if($this->db->table_exists('api_keys'))
            $this->dbforge->drop_table('api_keys');

        if($this->db->table_exists('api_user_tokens'))
            $this->dbforge->drop_table('api_user_tokens');

		// API Users
        $this->dbforge->add_field($users);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('api_users');

        // API Keys
        $this->dbforge->add_field($keys);
		$this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('api_keys');

        // User tokens
        $this->dbforge->add_field($user_tokens);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('api_user_tokens');

        // Mobile API settings
        $token_validity_setting = array(
            'slug' => 'mobileapi_token_validity_hours',
            'title' => 'How many hours is a token valid for?',
            'description' => 'Number of hours it is valid for',
            '`default`' => '1',
            '`value`' => '1',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'mobileapi'
        );

        $this->db->insert('settings', $token_validity_setting);

        return true;
	}

	public function uninstall()
	{
        // Tables
        if($this->db->table_exists('api_users'))
		    $this->dbforge->drop_table('api_users');

        if($this->db->table_exists('api_keys'))
            $this->dbforge->drop_table('api_keys');

        if($this->db->table_exists('api_user_tokens'))
            $this->dbforge->drop_table('api_user_tokens');

        // Settings
        $this->db->delete('settings', array('module' => 'mobileapi'));

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
