<?php

namespace Admin;

class Controller_404 extends \Controller_Template {
	$template = 'template.smarty';
	public function action_index() {
		$this->response->status = 404;
		$this->template->body = \View::factory('404.smarty');
	}
}