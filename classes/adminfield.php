<?php
/*
	public function post_save() will be called after item is saved
*/
namespace Admin;

abstract class Adminfield {
	protected $item;
	//protected $data = array();
	protected $field = '';
	protected $class = '';
	
	public function __construct($class, &$item = null, $field = null) {
		$this->item = &$item;
		$this->class = $class;
		$this->field = $field;
	}
	
	public static function factory($class, &$item = null, $field = null) {
		return new static($class);
	}
	
	public abstract function list_view();
	public abstract function item_view();
	public abstract function update_item($post_data);
	
	public function def($field, $default = '') {
		$data = call_user_func($this->class.'::admin_config');
		return array_key_exists($field, $data[$this->field]) ? $data[$this->field][$field] : $default;
	}
	
	public static function do_search($class, $col, $val) {
		return array_map(
			create_function('$a', 'return $a["id"];'),
			\DB::select('id')
				->from($class::table())
				->where($col,'LIKE',"%$val%")
				->execute()
				->as_array()
		);
	}
	
	public static function get_search($class, $col, $val) {
		$params = array(
			'type' => 'text',
			'name' => 'list_search['.$col.']'
		);
		if($val) {
			$params['value'] = $val; 
		}
		return html_tag('input', $params);
	}
}