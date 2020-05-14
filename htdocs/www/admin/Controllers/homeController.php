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
//	    echo "testeAuth";

//	    AuthHelperA::signIn("abc", "password");
//        AuthHelperA::signOut();
        AuthHelperA::signUp("testeSignUp", "pedro_mello@icloud.com", "@123", "password");

//        Teste para o init do controller
//        AuthHelperA::setActionsExceptions(array("adicionar"));
//        AuthHelperA::addActionExcept("editar");
//        AuthHelperA::checkLogin();

    }


    public function testeEmail(){
//        //PHPMailer Object
//        $mail = new PHPMailer;
//        $mail-> SetLanguage("br", HELPERS . "/Email/language/");
//
//
////Enable SMTP debugging.
//        $mail->SMTPDebug = 3;
////Set PHPMailer to use SMTP.
//        $mail->isSMTP();
////Set SMTP host name
//        $mail->Host = "mail.70e7.com";
////Set this to true if SMTP host requires authentication to send email
//        $mail->SMTPAuth = true;
////Provide username and password
//        $mail->Username = "no-reply@70e7.com";
//        $mail->Password = "A123456789b";
////If SMTP requires TLS encryption then set it
////$mail->SMTPSecure = "tls";
////Set TCP port to connect to
//        $mail->Port = 587;
//
//        $mail->From = "no-reply@70e7.com";
//        $mail->FromName = "Full Name";
//
//
//        $mail->addAddress("pedro_mello@icloud.com", "Recepient Name");
//
//        $mail->isHTML(true);
//
//        $mail->Subject = "Subject Text";
//        $mail->Body = "<i>Mail body in HTML</i>";
//        $mail->AltBody = "This is the plain text version of the email content";
//
//        if(!$mail->send())
//        {
//            echo "Mailer Error: " . $mail->ErrorInfo;
//        }
//        else
//        {
//            echo "Message has been sent successfully";
//        }
//

        EmailHelperA::make()
            ->addAddress("pedro_mello@icloud.com", "Pedro")
            ->setSubject("Contato")
            ->setAltMessage("Esta é uma mensagem da 70e7.")
            ->setMessageFromHtmlFile("ContactMessage.phtml", array("name" => "Test"))
            ->send();
    }


}