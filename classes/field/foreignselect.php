<?php
/*
	@fdesc - the display column of the foreign table
	@fval - the value column of the foreign table
	@fmodel - the class name corresponding to the foreign table
	@multiple - boolean true for a multiple select
	@select_order - how to order items in select (ASC or DESC)
*/
namespace Admin;

class Field_Foreignselect extends Adminfield {
	
	public function list_view() {
		$fdesc = $this->def('fdesc');
		
		if($this->def('multiple')) {
			return implode(
				', ',
				array_map(
					create_function('$a', 'return $a->'.$fdesc.';'),
					$this->item->{$this->field}
				)
			);
		}
		else {
			return $this->item->{$this->field}->$fdesc;
		}
	}
	
	public function item_view() {
		$class = str_replace('\\','',$this->class);
		$mult = $this->def('multiple');
		$val = $this->def('fval');
		$desc = $this->def('fdesc');
		$the_id = $this->item->id ? $this->item->id : 'new';
		$result = '<select name="item_field['.$class.']['.$the_id.']['.$this->field.']'.($mult?'[]" multiple ':'"').'>';
		$result .= $mult ? '' : '<option value="">Select...</option>';
		
		$model_class = $this->def('fmodel');
		$all_models = $model_class::find()
			->order_by($desc, $this->def('select_order', 'ASC'))
			->get();
			
		foreach($all_models as $model) { 
			$result .= '<option value="'.$model->$val.'"';
			if($mult) {
				if(array_key_exists($model->id, $this->item->{$this->field})) {
					$result .= ' selected';
				}
			}
			elseif($this->item->{$this->field} && $model->id == $this->item->{$this->field}->id) {
				$result .= ' selected';
			}
			$result .= '>'.$model->$desc.'</option>';
		}
		$result .= '</select>';
		return $result;
	}
	
	public function update_item($post_data) {
		$class_table = call_user_func($this->class.'::table');
 		$class = str_replace('\\','',$this->class);
		$item_id = $this->item->id ? $this->item->id : 'new';
		if(!empty($post_data[$class][$item_id][$this->field])) {
			$model = $this->def('fmodel');
			if($this->def('multiple')) {
				$delete = array_diff_key(
					$this->item->{$this->field}, 
					array_flip($post_data[$class][$item_id][$this->field])
				);
				foreach($delete as $delete_id => $delete_val) {
					// this works...
					unset($this->item->{$this->field}[$delete_id]);
				}
				foreach($post_data[$class][$item_id][$this->field] as $id) {
					if($itm = $model::find($id)) {
						$this->item->{$this->field}[] = $itm;
					}
				}
			}
			else {
				//TODO: FUUUUUUUUUUUCK!!!!!!
				if($itm = $model::find($post_data[$class][$item_id][$this->field])) {
					$this->item->{$this->field} = $itm;
				}
				else {
					unset($this->item->{$this->field});
				}
			}
		}
		elseif($this->def('multiple')) {
			foreach($this->item->{$this->field} as $id => $val) {
				unset($this->item->{$this->field}[$id]);
			}
		}
		else {
//			unset($this->item->{$this->field});
			//TODO: STILL NOT UNSETTING!!!!!!!
			$this->item->{$this->field} = null;
		}
		return true;
	}
	
	public static function get_search($class, $col, $val) {
		$props = $class::admin_config();
		$props = $props[$col];
		$result = $props['fmodel']::find()->order_by($props['fdesc'])->get();
		$opts = '';
		foreach($result as $r) {
			$attr = array('value' => $r->{$props['fval']});
			($val == $r->{$props['fval']}) and ($attr['selected'] = 'selected');
			$opts .= html_tag('option', $attr, $r->{$props['fdesc']});
		}
		return html_tag(
			'select', 
			array('name' => "list_search[$col]"), 
			$opts
		);
	}
	
	public static function do_search($class, $col, $val) {
		$props = $class::admin_config();
		$props = $props[$col];
		$table = $class::table();
		
		if(!empty($props['multiple'])) {
			$ftable = $props['fmodel']::table();
			$table_singled = \Inflector::singularize($table);
			$rel_table = strcmp($table, $ftable) ? $ftable.'_'.$table : $table.'_'.$ftable;
			
			return array_map(
				create_function('$a', 'return $a["'.$table_singled.'_id"];'),
				\DB::select($table_singled . '_id')
					->from($rel_table)
					->where(\Inflector::singularize($ftable) . '_id', $val)
					->execute()
					->as_array()
			);
		}
		else {
			return array_map(
				create_function('$a', 'return $a["id"]'),
				\DB::select('id')
					->from($table)
					->where(\Inflector::singularize($props['fmodel']::table()) . '_id', $val)
					->execute()
					->as_array()
			);
		}
	}
}