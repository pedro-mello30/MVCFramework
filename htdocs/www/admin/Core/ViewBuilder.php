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

class ViewBuilder
{
    private $output;
    private $_controller;

    //construtor faz a carga do template
    function __construct($controller)
    {
        $this->_controller = $controller;
        $layoutFile =  LAYOUT . $this->_controller->getLayout().".phtml";

//        if(file_exists($layoutFile)){
//            $this->output = $this->parseFile($layoutFile);
//        }else{
//            new ErrorHelper("layout");
//        }
//
//        //// ALERTA AQUI KKKK
        (file_exists($layoutFile)) ? $this->output = $this->parseFile($layoutFile) : new ErrorHelper("layout");
    }


    function buildLayout($tags = array()){
        if(count($tags) > 0)
        {
            foreach($tags as $tag =>$data)
            {

                $data  =  (file_exists($data)) ? $this->parseFile($data) : $data;
                $this->output  =  str_replace('{'.$tag.'}',$data, $this->output);
            }
        }
    }

    function buildView($file, $vars = array()){
        if(count($vars) > 0)
            extract($vars);
        //Ativar o buffer de saída.
        ob_start();
        include($file);
        //O conteúdo deste buffer interno é copiado na variável $content
        $content  =  ob_get_contents();
        //descartar o conteúdo do buffer.
        ob_end_clean();
        return $content;

//        return (file_exists($viewFile)) ? $this->parseFile($viewFile) : $viewFile;

    }

    //Enquanto o buffer de saída estiver ativo, não é enviada a saída do script
    function parseFile($file)
    {
        //Ativar o buffer de saída.
        ob_start();
        include($file);
        //O conteúdo deste buffer interno é copiado na variável $content
        $content  =  ob_get_contents();
        //descartar o conteúdo do buffer.
        ob_end_clean();
        return $content;
    }

    //Exibe o tempalte
    function display()
    {
        return $this->output;
    }

}