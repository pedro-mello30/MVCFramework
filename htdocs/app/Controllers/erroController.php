<?php

class Erro extends Controller
{
    protected $layout = "default";

	public function index_action($params = null)
	{
		$this->view('404');
	}

}