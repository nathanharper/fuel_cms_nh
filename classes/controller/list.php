<?php

namespace Admin;

class Controller_List extends Master {
	
	public function action_index() {
		$class = $this->data['tabs'][$this->data['table']];
		$admin_config = $class::admin_config();
		$msg = array();
		
		if($searches = \Input::post('list_search')) {
			// Add search query
			$models = $class::find();
			foreach($searches as $key => $val) {
				if($val) {
					$field_class = $admin_config[$key]['type'];
					if($id_arr = $field_class::do_search($class, $key, $val)) {
						$models = $models->where('id','IN',$id_arr);
						$search_active = true;
					}
				}
			}
			$this->data['search'] = $searches;
			$models = !empty($search_active) ? $models->get() : array();
		}
		else {
			$models = $class::find('all');
		}
		
		foreach($models as $i => $m) {
			foreach($admin_config as $field => $field_data) {
				$field_type = $field_data['type'];
				// Note: important to use $models[$i] here instead of $m
				// because we're passing a variable reference to the constructor
				// (as opposed to the value)
				$this->data['admin_fields'][$models[$i]->id][$field] = new $field_type($class, $models[$i], $field);
			}
		}
		if($delete_id = \Input::get('delete', '')) {
			$msg[] = "Item ID $delete_id successfully deleted.";
		}
		$this->data['msg'] = implode('<br />',$msg);
		$this->data['models'] = $models;
		$this->data['class'] = $class;
		$this->data['admin_config'] = $admin_config;
		$this->template->body = 'list.php';
	}
}