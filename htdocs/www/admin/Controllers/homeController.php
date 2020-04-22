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

class home extends Controller
{


	public function init($params = null)
	{
		parent::init();
	}

	

	//PAGINA INICIAL
	public function index_action($params = null)
	{

		$this->view('index', $dados);
	}




	public function login($params = null)
	{
		$dados = '';

		if($_POST)
		{
			//DEFINE OS DADOS PARA O LOGIN
			$this -> _auth -> _user  	 = $_POST['username'];
			$this -> _auth -> _senha 	 = $_POST['password'];


			//RETORNA FALSE PARA ERRO E TRUE PARA ACERTO
			if(!$this -> _auth -> login())
				$dados['msg'] = 'Login ou senha incorreta';
			else
				parent::getRedir()->goToAction('index');
		}

		$this->_layout = 'login';
		$this->view('login', $dados);

	}



	public function logout($params = null)
	{
		$this -> _auth -> logout();
	}


	public function testeRedirect($parameters){
//	    echo RedirectHelper::getCurrentController();
//	    RedirectHelper::goToController('usuarios');
//	    RedirectHelper::goToControllerAction('usuarios', 'adicionar');
//	    RedirectHelper::goToControllerAction('usuarios', 'editar', '1');
//        echo RedirectHelper::getUrlParameters();
//        print_r($_GET);

    }

    public function testeModel(){

        echo "testeModel";
//        $userAdmin = Usuarios_ModelA::getBy("id_admin_users", "1", null, null, true);
//        print_r($userAdmin->email);
//
//
//        $users = Usuarios_ModelA::getAll();
//        print_r($users);

        $usersByName = Usuarios_Model::getAll();
        print_r($usersByName);



        $user = Usuarios_Model::getBy("id_admin_users", 3);





        echo "testeModel";
    }


    public function testeAuth(){
	    echo "testeAuth";

//	    AuthHelperA::signIn("abc", "password");
//        AuthHelperA::signUp("testeSignUp", "teste@a.com", "@123", "password");
	    AuthHelperA::registerSignIn();

        print_r($_SESSION);
    }

}