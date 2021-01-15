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
    private $controller;

    function __construct($controller)
    {
        $this->controller = $controller;
        $layoutFile =  LAYOUT . $this->controller->getLayout().".phtml";


         //// ALERT HERE KKKK
        (file_exists($layoutFile)) ? $this->output = $this->parseFile($layoutFile) : new ErrorHelper("layout");
    }

    function buildLayout($tags = array())
    {
        if(count($tags) > 0)
        {
            foreach($tags as $tag =>$data)
            {
                $data  =  (file_exists($data)) ? $this->parseFile($data) : $data;
                $this->output  =  str_replace('{'.$tag.'}',$data, $this->output);
            }
        }
    }

    function buildView($file, $vars = array())
    {

        if(count($vars) > 0)
            extract($vars);

        ob_start();
            include($file);
            $content  =  ob_get_contents();
        ob_end_clean();

        return $content;
    }

    function parseFile($file)
    {
        ob_start();
            include($file);
            $content  =  ob_get_contents();
        ob_end_clean();

        return $content;
    }

    function display()
    {
        return $this->output;
    }

}