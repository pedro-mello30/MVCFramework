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
 * Copyright (c) 2020 Pedro Mello.
 *
 */

class EmailHelper
{

    private $mailer = null;

    public static function make() : ?object
    {
        $instance = new Self();
        return $instance;
    }

    public function getInstanceMailer()
    {
        if(is_null($this->mailer)){
            $this->mailer = new PHPMailer();
            return $this->mailer;
        }
        return $this->mailer;
    }
    public function __construct()
    {

        $this->mailer = new PHPMailer();
        $this->mailer->SetLanguage("br", HELPERS . "/Email/language/");

        $this->mailer->SMTPDebug = 3;
        $this->mailer->isSMTP();
        $this->mailer->SMTPAuth = true;
        $this->mailer->isHTML(true);

        $this->setHost("mail.70e7.com");
        $this->setUsername("no-reply@70e7.com");
        $this->setPassword("A123456789b");
        $this->setPort(587);

        $this->setFrom("no-reply@70e7.com");
        $this->setFromName("70e7");


    }


    public function send()
    {
        $this->mailer->send();
        return $this;
    }

    public function setHost($host)
    {
        $this->mailer->Host = $host;
        return $this;
    }

    public function setUsername($username)
    {
        $this->mailer->Username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->mailer->Password = $password;
        return $this;
    }

    public function setPort($port)
    {
        $this->mailer->Port = $port;
        return $this;
    }

    public function setFrom($from)
    {
        $this->mailer->From = $from;
        return $this;
    }

    public function setFromName($fromName)
    {
        $this->mailer->FromName = $fromName;
        return $this;
    }

    public function addAddress($emailAddress, $recipientName)
    {
        $this->mailer->AddAddress($emailAddress, $recipientName);
        return $this;
    }

    public function setSubject($subject)
    {
        $this->mailer->Subject = $subject;
        return $this;
    }

    public function AddAttachment($attachment)
    {
        $this->mailer->AddAttachment($attachment);
        return $this;
    }

    public function setMessage($message)
    {
        $this->mailer->Body = $message;
        return $this;
    }

    public function setAltMessage($altMessage)
    {
        $this->mailer->AltBody = $altMessage;
        return $this;
    }

    public function setMessageFromHtmlFile($file, $vars = array())
    {
        $path = HELPERS."Email/Messages/";

        if(count($vars) > 0)
            extract($vars);

        ob_start();
            include($path.$file);
            $content  =  ob_get_contents();
        ob_end_clean();
        $this->setMessage($content);

        return $this;
    }

}