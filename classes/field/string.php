<?php

namespace Admin;

class Field_String extends Adminfield {
	public function list_view() {
		return $this->item->{$this->field};
	}
	
	public function item_view() {
		$class = str_replace('\\','',$this->class);
		$fieldname = "item_field[$class][".($this->item->id?$this->item->id:'new')."][$this->field]";
		$value = ($this->item->{$this->field} ? $this->item->{$this->field} : '');
		
		if($this->def('textarea')) {
			return html_tag('textarea', array(
				'name' => $fieldname, 
				'rows' => $this->def('rows', 20), 
				'cols' => $this->def('cols', 50)), 
			$value);
		}
		
		return html_tag('input', array(
			'name' => $fieldname,
			'type' => 'text',
			'value' => $value,
		));
	}
	
	public function update_item($post_data) {
		$class = str_replace('\\','',$this->class);
		$id = $this->item->id ? $this->item->id : 'new';
		$this->item->{$this->field} = $post_data[$class][$id][$this->field];
		return true;
	}
}