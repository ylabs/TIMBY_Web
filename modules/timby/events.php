<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Event handler for the TIMBY Module
 */
class Events_Timby {
    
    protected $ci;
    
    public function __construct()
    {
        $this->ci =& get_instance();
        
        //register the public_controller event
        Events::register('public_controller', array($this, 'run'));
		
		//register a second event that can be called any time.
		// To execute the "run" method below you would use: Events::trigger('sample_event');
		// in any php file within PyroCMS, even another module.
		Events::register('sample_event', array($this, 'run'));
    }
    
    public function run()
    {
        // Run logic?
    }
    
}
/* End of file events.php */