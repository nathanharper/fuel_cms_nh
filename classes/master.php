<?php

namespace Admin;

define('ADMINPATH', APPPATH . 'modules/admin/');

abstract class Master extends \Controller_Template {
	public $template = 'template.smarty';
	public $data = array();
	
	public function before() {
		parent::before();
		\Config::load('admin::admin');
		
		$auth = \Auth::instance('SimpleAuth');
		if(\Input::get('logout')) {
			$auth->logout();
			\Response::redirect(\Uri::base(false).'admin/login');
		}
		
		$uri = explode('/', \Uri::string());
		if($auth->check()) {
			if(count($uri) < 3 && (empty($uri[1]) || $uri[1] == 'login')) {
				\Response::redirect(\Uri::base(false).'admin/list');
			}
			// Load admin Config for List View and default to first tab
			$this->data['tabs'] = $this->template->tabs = \Config::get('admin_tabs');
			$this->data['table'] = $this->param('item', ''); // get item from URI
			if(!$this->data['table']) {
				list($this->data['table']) = array_slice(array_keys($this->data['tabs']),0,1);
			}
			$this->template->table = $this->data['table'];
		}
		elseif(count($uri) > 1 && $uri[1] != 'login') {
			\Response::redirect(\Uri::base(false).'admin/login');
		}
		
		if($this->auto_render === true) {
			// set up defaults
			$this->template->body = '';
			$this->template->css = \Config::get('admin_css');
			$this->template->js = \Config::get('admin_js');
			$this->template->admin_js = ADMINPATH . 'assets/js/admin_js.smarty';
			$this->template->admin_css = ADMINPATH . 'assets/css/admin_css.smarty';
		}
		
		return true;
	}
	
	public function after() {
		if($this->template->body && is_string($this->template->body)) {
			$this->template->body = \View::factory($this->template->body, $this->data, false); 
		}
		parent::after();
	}
	
	public function return_404() {
		$this->response->status = 404;
		$this->template = \View::factory('404_template.smarty');
		$this->template->body = '404.smarty';
	}
}