<?php

/**
* Class para redirecionamento
*/


class RedirectHelper
{

    public static function goTo($data)
	{	
//		 echo  "Location: " . URL . $data;
		header("Location: " . URL . $data);
		exit();
	}

	public static function getCurrentController()
	{
        global $core;
		return $core->getRoute()->getController();
	}

	public static function getCurrentAction()
	{
        global $core;
		return $core->_route->getAction();
	}

    public static function getUrlParameters(){
        global $core;
        return implode("/", $core->getRoute()->getParameters());
    }

    public static function goToController($controller)
	{
        self::goTo($controller . "/index");
	}

    public static function goToAction($action)
	{
	    self::goTo(self::getCurrentController() . "/" . $action);
	}


    public static function goToControllerAction($controller, $action, $parameters = null)
	{

		if(is_null($parameters)){
            self::goTo($controller . "/" . $action);
        }

        self::goTo($controller . "/" . $action . "/" . $parameters);

	}


}