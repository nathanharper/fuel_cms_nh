<?php

namespace Admin;

class Field_Select extends Adminfield {
	
	public function list_view() {
		$array = $this->def('array');
		return array_key_exists($this->item->{$this->field}, $array) ? $array[$this->item->{$this->field}] : '';
	}
	
	public function item_view() {
		$class = str_replace('\\','',$this->class);
		$array = $this->def('array');
		$the_id = $this->item->id ? $this->item->id : 'new';
		
		return static::generate_select(
			"item_field[$class][$the_id][$this->field]", 
			$this->item->{$this->field}, 
			$this->def('array')
		);
	}
	
	public function update_item($post_data) {
 		$class = str_replace('\\','',$this->class);
		$id = $this->item->id ? $this->item->id : 'new';
		$this->item->{$this->field} = $post_data[$class][$id][$this->field];
		return true;
	}
	
	public static function get_search($class, $col, $val) {
		$props = $class::admin_config();
		return static::generate_select("list_search[$col]", $val, $props[$col]['array']);
	}
	
	public static function generate_select($select_name, $val, $array) {
		$html = '<select name="'.$select_name.'">';
		$html .= '<option value="">Select...</option>';
		
		foreach($array as $opt_val => $name) {
			$selected = ($val == $opt_val) ? 'selected' : '';
			$html .= '<option value="'.$opt_val.'" '.$selected.'>'.$name.'</option>';
		}
		
		$html .= '</select>';
		return $html;
	}
	
	public static function do_search($class, $col, $val) {
		return array_map(
			create_function('$a', 'return $a["id"];'),
			\DB::select('id')
				->from($class::table())
				->where($col, $val)
				->execute()
				->as_array()
		);
	}
}