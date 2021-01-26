<?php
class contato extends Controller
{
    public function init($params = null)
    {
        AuthHelper::checkLogin();

        parent::init();
    }

    public function index_action($params = null)
    {
        $dados['contatos'] = Contato_Model::getAll();
        $this->view('index', $dados);
    }

    public function visualizar($params = null)
    {
        $dados['contato'] = Contato_Model::getBy('id_contato', $params[0]);
        $this->view('visualizar', $dados);
    }

}