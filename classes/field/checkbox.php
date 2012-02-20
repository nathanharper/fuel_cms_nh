<?php
// Checkbox columns should be tinyint with value of 1 or 0
namespace Admin;

class Field_Checkbox extends Adminfield {
	public function list_view() {
		return $this->item->{$this->field} ? 'yes' : 'no';
	}
	
	public function item_view() {
		$class = str_replace('\\', '', $this->class);
		$id = $this->item->id ? $this->item->id : 'new';
		$args = array(
			'type' => 'checkbox',
			'name' => "item_field[$class][$id][$this->field]"
		);
		
		if($this->item->{$this->field}) {
			$args['checked'] = '1';
		}
		
		return html_tag('input', $args);
	}
	
	public function update_item($post_data) {
		$class = str_replace('\\', '', $this->class);
		$id = $this->item->id ? $this->item->id : 'new';
		$this->item->{$this->field} = !empty($post_data[$class][$id][$this->field]) ? 1 : 0;
		return true;
	}
	
	public static function get_search($class, $col, $val) {
		$html = '<select name="list_search['.$col.']">';
		$html .= '<option value="">Select...</option>';
		$html .= '<option value="yes"'.($val == 'yes' ? ' selected' : '').'>Yes</option>';
		$html .= '<option value="no"'.($val == 'no' ? ' selected' : '').'>No</option>';
		$html .= '</select>';
		return $html;
	}
	
	public static function do_search($class, $col, $val) {
		return array_map(
			create_function('$a', 'return $a["id"];'),
			\DB::select('id')
				->from($class::table())
				->where($col, ($val == 'yes' ? 1 : 0))
				->execute()
				->as_array()
		);
	}
}