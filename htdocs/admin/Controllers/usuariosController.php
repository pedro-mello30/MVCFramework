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
        $this->output['usuarios'] = Usuarios_Model::getAll();

        $this->view('index', $this->output);
    }

    public function adicionar($params = null)
    {
        if($_POST){
            $auth = new AuthHelper();
            if($auth->signUp($_POST['name'], $_POST['email'], $_POST['username'], $_POST['password']))
                RedirectHelper::goToController("usuarios");
        }

        $this->view('usuarios');
    }

    public function editar($params = null)
    {
        if(!isset($params[0]))
            $this->getRedir()->goToControllerAction('usuarios', 'adicionar');

        $idName = Usuarios_Model::getIDName();
        $this->output['usuario'] = Usuarios_Model::getBy($idName, $params[0]);

        if($_POST){
            $_POST[$idName] = $params[0];

            foreach ($_POST as $key => $d)
            {
//                If in case the _post form have passwords fields ## Use the AuthHelper
//                if($key !== "c_password" || "password" || "currentpassword")
                    $data[$key] = $d;
            }
            $user = new Usuarios_Model($data);
            $user->save();
            RedirectHelper::goToController("usuarios");
        }

        $this->view('usuarios', $this->output);
    }

    public function delete($params = null){
        $idName = Usuarios_Model::getIDName();
        $user = new Usuarios_Model(array($idName => $params[0]));
        $user->delete();

        $this -> getRedir()->goToControllerAction('usuarios', 'listar');
    }

    public function testeRedirect($parameters)
    {
        echo RedirectHelper::getUrlParameters();
        print_r($_GET);

    }

}



