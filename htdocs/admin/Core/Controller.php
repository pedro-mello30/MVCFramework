<?php
/**
 *
 *
 *                                              ........................
 *                                           .....',;,,;;;;;,,''...........
 *                                         ..'..........'',;;;;,'........''..
 *                                        .'..................,,,,'.......';,.
 *                                      .''..'''.','..''........',;,,'.....';;'.
 *                                     .,,';;;;;,;;;;;;;;,''......';;'......,;;'.
 *                                   .,;:;;;,''..'...''',,,,,,'.....;;,'.....;;'''.
 *                                 .':;;,.'.....'.'''''......',,,...',;,.....,;,..'.
 *                                .;:'''....'',;;;,'......     ..''..';;'....';,'...'.
 *                               .','....'',;;;,'..              .....';,'...';,.'...'.
 *                              .'.'..'.';:;,'....                  ..';,....';,'..'..'.
 *                              '' .'.',:;,..''..                     .,'....,;''...'.'.
 *                              .'..'';:;'..','.                      .''...,;,..'..',,.
 *                              .'..,,c;...';;.                       .'...';,....'.':,.
 *                               .'.,c;...',:,.                       ....';,'....',;:.
 *                               .',;c,...':;...                      ..',,,......,:;,.
 *                                .;c;....,:;..'.                    .',,,'.....,,:,,.
 *                                .;c;....,:;..',.                 ..',''.....';;;'''.
 *                                 ,c,....,:;...,;'...          ..........'',;;,,'.'.
 *                                 .:;...'';:,...,;,..................',,,;,,,..'..'.
 *                                  ';'...',::'..',;;,...'''',,,,,,,,,;,,,'''..'. ''
 *                                  .'....'',::...'',;;,'......''..''......'..'...'.
 *                                   ..'....';::'....',;;;,''................,,.'..
 *                                     .....'',:c;'......',,;;,;;;,,,,,,;;;;;'...
 *                                         ....';c:,..........',;;;,,;:;;,'..
 *                                             ...';:;'...........''....
 *                                                  .',,,,..........
 *                                                      ........
 *
 * Copyright (c) 2019 Pedro Mello.
 *
 */

class Controller
{
    protected $layout = 'default';
    protected $redir = null;
    protected $modelController = null;

	function __construct()
    {
        $this->redir = new RedirectHelper();
    }

    public function init()
    {

    }

    public function getLayout() : string
    {
        return $this->layout;
    }

    public function setModelController($modelController = null)
    {
        $this->modelController = (isset($modelController)) ? $modelController : $this->getModelInstance();
    }

    private function getModelInstance()
    {
        $modelName =  ucfirst(RedirectHelper::getCurrentController()."_Model");
        $modelFile = MODELS . $modelName . ".php";

        if ( !file_exists( $modelFile ) )
        {
            new ErrorHelper('model');
        }

        require_once( $modelFile );

        return new $modelName();
    }

    public function getRedir() : RedirectHelper
    {
        return $this->redir;
    }

    public function getModelController() : Model
    {
        return $this->modelController;
    }

    protected function view($nome_pagina, $vars = null )
    {

        $builder = new ViewBuilder($this);

        $viewFile = $this->getViewPath() . "/" .$nome_pagina . ".phtml";
        $conteudo = $builder->buildView($viewFile, $vars);

        $tags = array( 'conteudo' => $conteudo);

        $builder->buildLayout($tags);

        echo $builder->display();
    }

    public function getViewPath()
    {
        $act = explode("-", RedirectHelper::getCurrentController());

        foreach ($act as $key => $value)
            $act[$key] = ucfirst($value);

        $pasta = implode("", $act);

        return VIEWS . $pasta;
    }

    //// CRUD ////

    protected function list($params = null)
    {
        $return = null;

        if($this->modelController->getAll())
        {
            $key = $this -> redir -> getCurrentController();

            $order = (isset($this->modelController -> orderby[$key])) ? "ORDER BY " . $this->modelController -> orderby[$key] : "";
            $return[$key] = $this->modelController->consult("SELECT * FROM `{$this->modelController->_tabela}` {$order}");

        }

        return $return;
    }

    protected function add($params = null)
    {
        if(!empty($_FILES))
        {
            $key = array_keys($_FILES);

            for ($i=0; $i < count($key); $i++)
            {
                $imageHelper = new ImageHelper();
                $_POST[$key[$i]] = $imageHelper -> saveCompressedFromUpload( $_FILES[ $key[$i] ]);
            }
        }

        if($_POST)
        {
            if(isset($_POST['password']))
                $_POST['password'] = hash('sha512', $_POST['password']);

            if($this -> modelController){
                if($this -> modelController -> insert($_POST)){
                    return 1;
                }else{
                    return 0;
                }
            }
        }
    }

    protected function edit($params = null)
    {
        if(!isset($params[0]))
            return false;

        $where = 'id_' . $this->modelController->_tabela . ' = ' . $params[0];

        if(!empty($_FILES))
        {
            $key = array_keys($_FILES);

            for ($i=0; $i < count($key); $i++)
            {
                $imageHelper = new ImageHelper();
                $_POST[$key[$i]] = $imageHelper -> saveCompressedFromUpload( $_FILES[ $key[$i] ]);
            }
        }

        if($_POST)
        {
            if(isset($_POST['password']))
                $_POST['password'] = hash('sha512', $_POST['password']);

            if($this -> modelController){
                if($this ->modelController->update($_POST, $where ,true)){
                    return 1;
                }else{
                    return 0;

                }
            }
        }
    }

    protected function del($params = null)
    {
        $where = 'id_' . $this->modelController->_tabela . ' = ' . $params[0];
        $this->modelController->delete($where);
    }
}