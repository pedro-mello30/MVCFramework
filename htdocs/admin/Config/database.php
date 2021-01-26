<?php

class DATABASE_CONFIG
{

    # local database
    public static $staging = array(
        'host' => 'localhost',
        'login' => 'root',
        'senha' => '',
        'banco' => 'default',
    );


    # production database
    public static $production = array(
        'host' => 'db',
        'login' => 'root',
        'senha' => 'root',
        'banco' => 'default',
    );

}