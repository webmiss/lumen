<?php

namespace App\Http\Controllers\Admin;

class WelcomeController extends ControllerBase{

	/* 首页 */
	function index(){
		return $this->redirect('Desktop');
	}
}