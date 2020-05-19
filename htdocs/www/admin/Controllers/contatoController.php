<?php
class contato extends Controller implements CRUD
{
    public function init($params = null)
    {
        parent::init();
    }

    public function index_action($params = null)
    {
        $this->view('index');
    }

    public function lista($params = null)
    {
        parent::list($params);
    }

    public function adicionar($params = null)
    {
        $this->add($params);
    }

    public function editar($params = null)
    {
        $this->edit($params);
    }

    public function deletar($params = null)
    {
        $this->del($params);
    }
}