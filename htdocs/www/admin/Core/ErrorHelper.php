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
 *                                                                                    Copyright (c) 2019 Pedro Mello.
 *
 */


class ErrorHelper
{
    private $error = true;
    private $debug = true;

    function __construct($type)
    {

        if($this->error)
        {
            switch ($type)
            {
                case 'controller':
                    if($this->debug)
                        die("O Controller não foi encontrada.");
                    else
                        $this->redirectErrorPage();
                    break;
                case 'action':
                    if($this->debug)
                        die("A Action não foi encontrada.");
                    else
                        $this->redirectErrorPage();
                    break;
                case 'layout':
                    if($this->debug)
                        die("O Layout não foi encontrado.");
                    else
                        $this->redirectErrorPage();
                    break;
                case 'model':
                    if($this->debug)
                        die("A Model não foi encontrada.");
                    else
                        $this->redirectErrorPage();
                    break;
                default:
                    $this->redirectErrorPage();
                    break;
            }
        }
        else
        {
            $this->redirectErrorPage();
        }
    }

    function redirectErrorPage(){
        error_reporting(0);
        header('Location:' . URL_ADMIN . 'erro/pagina_nao_encontrada');
    }
}