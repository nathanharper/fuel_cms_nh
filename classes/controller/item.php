<?php

namespace Admin;

class Controller_Item extends Master {
	public function action_index() {
		$item_id = \Input::get('id', '');
		$save_item = \Input::post('save_item');
		$post_data = \Input::post('item_field');
		$new = \Input::get('new', '');
		
		if(!$this->data['table'] || !isset($this->data['tabs'][$this->data['table']])) {
			return $this->return_404();
		}
		
		$class = $this->data['tabs'][$this->data['table']];
		$this->data['item_config'] = $class::admin_config();
		
		if($new) {
			$item = $class::factory();
		}
		elseif($item_id) {
			$item = $class::find($item_id);	
		} 
		
		if(!$item) {
			return $this->return_404();
		}
		
		if(($id = $item->id) && \Input::post('delete')) {
			$item->delete();
			\Response::redirect(\Uri::base(false).'admin/list/'.$this->data['table'].'?delete='.$id);
		}
		
		// Load admin fields for the template or to save
		$post_data = \Input::post('item_field');
		$post_save = array();
		foreach($this->data['item_config'] as $field => $field_data) {
			//TODO: get the factory working properly so we can chain this muh-fuh
			$field_type = $field_data['type'];
			$new_field = new $field_type($class, $item, $field);
			$this->data['item_config'][$field]['type'] = $new_field;
			if($save_item) {
				$new_field->update_item($post_data);
				if(method_exists($field_type, 'post_save')) {
					// Create an list of Admin Fields with post-save callbacks.
					$post_save[] = $field;
				}
			}
		}
		if($save_item && $item->save()) {
			foreach($post_save as $field) {
				$this->data['item_config'][$field]['type']->post_save();
			}
			$this->data['success'] = 'The record has been saved.';
			if($new) {
				\Response::redirect(\Uri::base(false).'admin/item/'.$this->data['table']."?id=$item->id&save_success=1");
			}
		}
		if(\Input::get('save_success')) {
			$this->data['success'] = 'The record has been saved.';
		}
		
		$this->data['item'] = $item;
		$this->template->body = 'item.smarty';
	}
}