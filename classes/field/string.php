<?php

namespace Admin;

class Field_String extends Adminfield {
	public function list_view() {
		return $this->item->{$this->field};
	}
	
	public function item_view() {
		$class = str_replace('\\','',$this->class);
		return html_tag('input', array(
			'name' => "item_field[$class][".($this->item->id?$this->item->id:'new')."][$this->field]",
			'type' => 'text',
			'value' => ($this->item->{$this->field} ? $this->item->{$this->field} : ''),
		));
	}
	
	public function update_item($post_data) {
		$class = str_replace('\\','',$this->class);
		$id = $this->item->id ? $this->item->id : 'new';
		$this->item->{$this->field} = $post_data[$class][$id][$this->field];
		return true;
	}
}