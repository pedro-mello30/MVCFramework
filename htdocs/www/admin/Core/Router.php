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

class Router {

    private $request;
    private $explode;
    public  $controller;
    public  $action;
    public  $parameters;

    function __construct($newRequest)
    {
        $this -> request = $newRequest;
        $this->setExplode();
        $this->setController();
        $this->setAction();
        $this->setParameters();
        $this->setGets();
    }

    private function setExplode()
    {
        // echo "setExplode\n";
        //cria um array com os valores passados pela url entre oa barras "/"
        $this->explode = explode("/", $this->request->getUrl());
    }


    private function setController()
    {
        // echo "setController\n";
        //Defino qual controller a ser usado
        $this-> controller = $this -> sanitizeString($this -> explode[0]);
    }


    private function setAction()
    {
        //defino a action que será usada
        $newAction = (!isset($this->explode[1]) || $this->explode[1] == null || $this->explode[1] == 'index') ? 'index_action' : $this->explode[1];

        $this-> action = $this -> sanitizeString($newAction);

    }


    public function setParameters()
    {
        $this->parameters = $this->explode;
        unset($this->parameters[0], $this->parameters[1]);

        //retira ultimo valor, se for vazio
        if(end($this->parameters ) == null)
            array_pop($this->parameters);

    }


    public function setGets()
    {


        $explodeGet = explode("?", $_SERVER['REQUEST_URI']);

        if(isset($explodeGet[1]))
        {
            $urlGetParams = explode("&", $explodeGet[1]);

            foreach ($urlGetParams as $g)
            {
                $get = explode("=", $g);
                $_GET[$get[0]] = $get[1];
            }
        }
    }


    /**
     * @return Controller
     */
    public function getController()
    {
        return $this-> controller;
    }

    /**
     * @return Action
     */
    public function getAction()
    {
        return $this-> action;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this-> parameters;
    }

    private function sanitizeString($string)
    {
        // echo "sanitizeString\n";
        $exp = explode("-", $string);
        for ($i=1; $i < count($exp); $i++) {
            $exp[$i] = ucfirst($exp[$i]);

        }

        return implode("", $exp);
    }
}



?>