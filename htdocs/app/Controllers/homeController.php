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

	public function index_action($params = null)
	{

		$this->view('index', $dados);
	}

    public function submitForm($params = null)
    {
        if ($_POST) {


            $captcha = $_POST['g-recaptcha-response'];
            unset($_POST['g-recaptcha-response']);
            if ($captcha == '') {
                echo "0";
                exit;
            }
            $secretKey = "6Ldd5esZAAAAAH1TROhFVvyQHPFnKMQbQOkPaTM6";
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $captcha);

            $responseData = json_decode($verifyResponse);

//            print_r($verifyResponse);
//            if($responseData->success){
            $_POST["data"] = date("Y-m-d H:i:s");
            $contato = new Contato_Model($_POST);
            $contato->save();

            $menssage = "Nome:" . $_POST["nome"] . "<br>";
            $menssage .= "Email:" . $_POST["email"] . "<br>";
            $menssage .= "Telefone:" . $_POST["telefone"] . "<br>";
            $menssage .= "Mensagem:" . $_POST["mensagem"];




            echo "1";
//            }else{
//                echo "0";
//            }


        }
    }

    public function submitNewsletter($params = null)
    {
        if ($_POST) {


            $captcha = $_POST['g-recaptcha-response'];
            unset($_POST['g-recaptcha-response']);
            if ($captcha == '') {
                echo "0";
                exit;
            }
            $secretKey = "6Ldd5esZAAAAAH1TROhFVvyQHPFnKMQbQOkPaTM6";
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $captcha);

            $responseData = json_decode($verifyResponse);

//            print_r($verifyResponse);
//            if($responseData->success){
            $_POST["data"] = date("Y-m-d H:i:s");
            $_POST["assunto"] = "Newsletter";
            $contato = new Contato_Model($_POST);
            $contato->save();


            EmailHelper::make()
                ->addAddress($_POST["email"], ucfirst($_POST["name"]))
                ->setSubject("Contato Empecom")
                ->setAltMessage("Esta é uma mensagem da Empecom.")
                ->setMessageFromHtmlFile("ContactMessage.phtml", array("name" => ucfirst($_POST["nome"])))
                ->send();

            echo "1";
//            }else{
//                echo "0";
//            }


        }
    }


}