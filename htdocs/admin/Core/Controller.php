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

abstract class Controller
{
    protected $layout = 'default';
    protected $redir = null;
    protected $modelController = null;

	function __construct()
    {
//        $this->redir = new RedirectHelper();
    }

    public function init()
    {
//        echo "init";
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

        $tags = array('conteudo' => $conteudo);

        $builder->buildLayout($tags);

        echo $builder->display();
        exit();
    }

    public function getViewPath()
    {
        $act = explode("-", RedirectHelper::getCurrentController());

        foreach ($act as $key => $value)
            $act[$key] = ucfirst($value);

        $pasta = implode("", $act);

        return VIEWS . $pasta;
    }

}