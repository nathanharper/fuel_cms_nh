<?php
/*
	@upload_type - type of upload ('audio', 'image', etc.)
	@secure - boolean true to save to a secure filepath
	@dimension - array of dimension to crop the upload with imagemaagick (upload_type == 'image' only)
	@truncate - int length to cut audio with ffmpeg (upload_type == 'audio' only)
*/
namespace Admin;

class Field_Upload extends Adminfield {
	public function list_view() {
		return $this->item->{$this->field};
	}
	
	public function item_view() {
		$class = str_replace('\\','',$this->class);
		$val = $this->item->{$this->field} ? $this->item->{$this->field} : '';
		$result = html_tag('input', array(
			'name' => "$class-".($this->item->id?$this->item->id:'new')."-$this->field",
			'type' => 'file',
		));
		if($val) {
			$upload_type = $this->def('upload_type', 'image');
			if($upload_type == 'image') {
				$result .= \Html::br(2);
				if($this->def('secure')) {
					$result .= $this->item->{$this->field};
				}
				else {
					$result .= html_tag('image', array(
						'src' => $this->item->get_image(array('field' => $this->field))
					));
				}
			}
			elseif($upload_type == 'audio') {
				$result .= \Html::nbs(3) . $this->item->{$this->field};
			}
		}
		return $result;
	}
	
	public function update_item($post_data) {
		$upload_type = $this->def('upload_type', 'image');
		$upload_dir = \Config::get($upload_type.'_dir', 'files');
		$files = \Upload::get_files();
		$clean_class = str_replace('\\','',$this->class);
		foreach($files as $key => $params) {
			if($params['field'] == $clean_class.'-'.($this->item->id ? $this->item->id : 'new')."-$this->field") {
				$idx = $key;
				break;
			}
		}
		if(isset($idx)) {
			\Upload::save(
				// ids to save
				array($idx),
				//path to save to
				($this->def('secure')
					? realpath(\Config::get('secure_dir','secure') . $upload_dir)
					: DOCROOT . $upload_dir)
			);
			$errors = \Upload::get_errors();
			if(!isset($errors[$idx])) {
				$files = \Upload::get_files();
				$name = $files[$idx]['saved_as'];
				$path = $files[$idx]['saved_to'];
				if($upload_type == 'image') {
					if($dimensions = $this->def('dimension')) {
						// resize image
						$image = \Image::load($path.$name);
						foreach($dimensions as $dim) {
							if(preg_match("/^(?P<width>[0-9]+)x(?P<height>[0-9]+)$/i", $dim, $matches)) {
								$image
									->resize($matches['width'], $matches['height'])
									->save_pa(null, strtolower("_$dim"));
							}
						}
					}
				}
				elseif($upload_type == 'audio') {
					if($lengths = $this->def('truncate')) {
						if($ffmpeg = \Config::get('ffmpeg')) {
							foreach($lengths as $len) {
								// truncate audio track
								$sample = preg_replace("/^(.+)\.([^\.]+)$/",'$1_sample_'.$len.'.$2',$name);

								// TODO: make an ffmpeg wrapper class
								shell_exec(
									"$ffmpeg -i ".
									escapeshellarg($path.$name).
									" -t $length -acodec copy ".
									escapeshellarg(DOCROOT.$upload_dir.DS.$sample));
							}
						}
						else {
							error_log("could not truncate audio: ffmpeg not configured.");
						}
					}
				}
				$this->item->{$this->field} = $name;
			}
			else {
				error_log(print_r($errors,true));
				return array('upload_error' => $this->def('desc').' failed to save. Error No. '.$errors[$idx]['error']);
			}
		}
		return true;
	}
}