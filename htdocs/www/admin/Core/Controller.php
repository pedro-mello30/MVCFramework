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

    protected $_layout = 'default';
    protected $_redir = null;
    protected $_modelController = null;

	function __construct()
    {
        $this->_redir = new RedirectHelper();
    }

    public function init(){

//        //AUTENTICAÇÃO
//        $this -> _auth = new AuthHelper();
//
//        if($this->_redir->getCurrentController() != "home"
//            && $this->_redir->getCurrentAction() != "login")
//        {
//            if(!$this -> _auth -> verificaLogin())
//            {
//                $this -> _redir -> goToControllerAction('home' ,'login');
//            }
//        }
//



    }



    /**
     * @return current Layout of Controller
     */
    public function getLayout(): string
    {
        return $this->_layout;
    }


    /**
     * @param null $modelController
     */
    public function setModelController($modelController = null)
    {
//        echo "aqiu";
//        var_dump($this->_modelController);
        $this->_modelController = (isset($modelController)) ? $modelController : $this->getModelInstance();
    }

    private function getModelInstance(){
        $modelName =  ucfirst(RedirectHelper::getCurrentController()."_Model");
//        print_r("       ".$modelName);
        $modelFile = MODELS . $modelName . ".php";

        if ( !file_exists( $modelFile ) )
        {
            new ErrorHelper('model');
        }

        require_once( $modelFile );

        return new $modelName();
    }

    /**
     * @return RedirectHelper|null
     */
    public function getRedir(): RedirectHelper
    {
        return $this->_redir;
    }

    /**
     * @return null
     */
    public function getModelController() : Model
    {
        return $this->_modelController;
    }



    protected function view( $nome_pagina, $vars = null )
    {

        $builder = new ViewBuilder($this);

        $viewFile = $this->getViewPath() . "/" .$nome_pagina . ".phtml";
        $conteudo = $builder->buildView($viewFile, $vars);

        $tags = array( 'conteudo' => $conteudo);

        $builder->buildLayout($tags);

        echo $builder->display();
    }

    public function getViewPath(){
        $act = explode("-", RedirectHelper::getCurrentController());

        foreach ($act as $key => $value)
            $act[$key] = ucfirst($value);


        $pasta = implode("", $act);

        return VIEWS . $pasta;
    }




    protected function list($params = null)
    {


        $return = null;

        if($this->_modelController->getAll())
        {
            $key = $this -> _redir -> getCurrentController();

            $order = (isset($this->_modelController -> orderby[$key])) ? "ORDER BY " . $this->_modelController -> orderby[$key] : "";
            $return[$key] = $this->_modelController->consult("SELECT * FROM `{$this->_modelController->_tabela}` {$order}");

        }

        return $return;
    }

    protected function add($params = null)
    {

//        print_r($_FILES);
        //SE TENTAR ADD ARQUIVO ELE ENTRA AQUI
        if(!empty($_FILES))
        {
            //PEGO O NOME DA CHAVE DO ARRAY $_FILES
            $key = array_keys($_FILES);

            for ($i=0; $i < count($key); $i++)
            {
                $imageHelper = new ImageHelper();
                $_POST[$key[$i]] = $imageHelper -> saveCompressedFromUpload( $_FILES[ $key[$i] ]);
            }
        }

        //SE EXISTIR DADOS EM POST ADD AO BANCO
        if($_POST)
        {
//            var_dump($_this->_modelController);
            //SE EXISTIR CAMPO SENHA ELE CODIFICA
            if(isset($_POST['password']))
                $_POST['password'] = hash('sha512', $_POST['password']);

            if($this -> _modelController){
                if($this -> _modelController -> insert($_POST)){
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


        $where = 'id_' . $this->_modelController->_tabela . ' = ' . $params[0];


        if(!empty($_FILES))
        {
            //PEGO O NOME DA CHAVE DO ARRAY $_FILES
            $key = array_keys($_FILES);

            for ($i=0; $i < count($key); $i++)
            {
                $imageHelper = new ImageHelper();
                $_POST[$key[$i]] = $imageHelper -> saveCompressedFromUpload( $_FILES[ $key[$i] ]);
            }
        }


        //SE EXISTIR DADOS EM POST ADD AO BANCO
        if($_POST)
        {
            //SE EXISTIR CAMPO SENHA ELE CODIFICA
            if(isset($_POST['password']))
                $_POST['password'] = hash('sha512', $_POST['password']);

            if($this -> _modelController){
                if($this ->_modelController->update($_POST, $where ,true)){
                    return 1;
                }else{
                    return 0;

                }
            }

        }

    }

    protected function del($params = null)
    {
        //DELETA A LINHA
        $where = 'id_' . $this->_modelController->_tabela . ' = ' . $params[0];
        $this->_modelController->delete($where);

    }


}