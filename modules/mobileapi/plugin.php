<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a plugin for the module MobileAPI
 */
class Plugin_MobileAPI extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 * 
	 * {{ sample:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /sample:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$limit = $this->attribute('limit');
		$order = $this->attribute('order');
		
		return $this->db->order_by('name', $order)
						->limit($limit)
						->get('sample_items')
						->result_array();
	}
}

/* End of file plugin.php */