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

class usuarios extends Controller
{
    private   $output;

    public function init($params = null)
    {
        parent::init();
        $this->_dados[] = '';

        $m = new Usuarios_Model();
        parent::setModelController();
    }

    public function index_action($params = null)
    {
        $this->listar($params);
    }

    public function listar($params = null)
    {
        $this->output = parent::list($params);

        $this->view('index', $this->output);
    }

    public function adicionar($params = null)
    {
        if($_POST){
            parent::add($params);
        }

        $this->view('usuarios', $this->_dados);
    }

    public function editar($params = null)
    {
        if(!isset($params[0]))
            $this -> getRedir() -> goToControllerAction('usuarios', 'adicionar');

        $this -> output['dados'] = $this-> getModelController() -> readLine("id_admin_users=" . $params[0], true);

        if($_POST){
            parent::edit($params);
        }

        unset($this -> output['dados']['password']);
        $this->view('usuarios', $this->output);
    }

    public function delete($params = null){
        parent::del($params);
        $this -> getRedir()->goToControllerAction('usuarios', 'listar');
    }

    public function testeRedirect($parameters)
    {
        echo RedirectHelper::getUrlParameters();
        print_r($_GET);

    }

}



