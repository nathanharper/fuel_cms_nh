<?php

namespace Admin;

class Field_Date extends Adminfield {
	public function list_view() {
		$value = $this->item->{$this->field};
		$format = $this->def('format', false);
		return 
			!is_null($value)
			? date(($format ? $format : \Config::get('date_format', 'm/d/Y')), $value)
			: $this->def('null_value', '(none)');
	}
	
	public function item_view() {
		$value = $this->item->{$this->field};
		$class = str_replace('\\', '', $this->class);
		$id = $this->item->id ? $this->item->id : 'new';
		$format = $this->def('format', false);
		return html_tag('input', array(
			'value' => ($value ? date(($format ? $format : \Config::get('date_format', 'm/d/Y')), $value) : ''),
			'type' => 'text',
			'class' => 'datepicker',
			'name' => "item_field[$class][$id][$this->field]",
		));
	}
	
	public function update_item($post_data) {
		$class = str_replace('\\', '', $this->class);
		$id = $this->item->id ? $this->item->id : 'new';
		if($timestamp = strtotime($post_data[$class][$id][$this->field])) {
			$this->item->{$this->field} = $timestamp;
		}
		else {
			$this->item->{$this->field} = null;
		}
	}
}