<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_TimbySecurity extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'TIMBY Security Log'
			),
			'description' => array(
				'en' => 'Posts and displays all activity log on the backend.'
			),
			'frontend' => FALSE,
			'backend' => TRUE,
			'menu' => 'content',
			'sections' => array(
				'dashboard' => array(
					'name' 	=> 'timbysecurity:dashboard',
					'uri' 	=> 'admin/timbysecurity',
				)
            )
		);
	}

	public function install()
	{
        $this->uninstall();

        $log = array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'activity_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => false,
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => false,
            ),
            'web_user_id' => array(
                'type' => 'INT',
                'default' => 0,
                'null' => true,
            ),
            'mobile_user_id' => array(
                'type' => 'INT',
                'default' => 0,
                'null' => true,
            ),
            'created_on' => array(
                'type' => 'datetime',
            ),
            'modified_on' => array(
                'type' => 'datetime',
                'null' => true,
            ),
        );

        $this->dbforge->add_field($log);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('security_log');

		return true;
	}

	public function uninstall()
	{
        $this->dbforge->drop_table('security_log');

		return true;
	}


	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return true;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
	}
}
/* End of file details.php */
