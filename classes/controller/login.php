<?php

namespace Admin;

class Controller_Login extends Master {
	
	public function before() {
		parent::before();
		
		if(\Input::post('submit_login')) {
			if(\Auth::instance('SimpleAuth')->login()) {
				\Response::redirect(\Uri::base(false).'admin/list');
			}
			else {
				$this->data['msg'] = 'Incorrect login.';
			}
		}
		return true;
	}
	
	public function action_index() {
		$this->template->body = 'login.smarty';
	}
}