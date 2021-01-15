<?php

class Erro extends Controller
{
    protected $layout = "login";

	public function index_action($params = null)
	{
		$this->view('404');
	}

	public function pagina_nao_encontrada($params = null)
	{
		$this->view('404');
	}
}