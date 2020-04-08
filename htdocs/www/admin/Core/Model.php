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

class Model
{
	protected $db;
	public    $_tabela;
	public    $_fk;

	public function __construct()
	{
		if( in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ) {
            $database = DATABASE_CONFIG::$staging;
        } else {
            $database = DATABASE_CONFIG::$production;
        }

        extract($database);
		$this -> db = new PDO("mysql:host=$host;dbname=$banco", "$login", "$senha", array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'));
		if(!$this -> db) die('Erro ao conectar ao banco de dado');
	}

	public function insert( Array $dados, $debug = FALSE )
	{
		$fields = $this -> getTableField();

		foreach ($dados as $ind => $val)
		{
			if(in_array($ind, $fields))
			{
				$dadosBD[$ind] = $val;
			}
		}

		//INSERT DINAMICO BASEADO EM ARRAY DE DADOS
		$campos	  =  implode(",", array_keys($dadosBD));
		$valores  =  ":" . implode(",:", array_keys($dadosBD));


		//FAZ O DEBUG DA STRING SQL
		if($debug)
		{
			$valoresDebug = "'" . implode("','", $dadosBD) . "'";
			$this -> debug ( "INSERT INTO `{$this->_tabela}` ({$campos}) values ({$valoresDebug})" );
		}

		//TENTA INSERIR OS DADOS
		try
		{
			//PREPARA OS DADOS PARA INSERT USANDO PREPARED STATEMENTS
			$ps = $this -> db -> prepare("INSERT INTO `{$this->_tabela}` ({$campos}) values ({$valores})");

			//PASSA OS VALORES CORRETOS BASEADOS NO PARAMETROS DA STRING
			foreach ($dadosBD as $key => $value){
			 	$ps->bindValue(":$key", $value);
			}

			//EXECUTA A QUERY
			$executa = $ps->execute();

		} catch (PDOexception $e) {
			echo $e->getMessage();
		}

		return $this -> db ->lastInsertId();
	}

	public function read( $where = null , $limit = null , $offset = null , $orderby = null, $debug = FALSE )
	{
		$where    = ($where   != null ? "WHERE {$where}"      : "");
	 	$limit    = ($limit   != null ? "LIMIT {$limit}" 	  : "");
	 	$offset   = ($offset  != null ? "OFFSET {$offset}" 	  : "");
	 	$orderby  = ($orderby != null ? "ORDER BY {$orderby}" : "");

	 	if($debug)
	 		$this -> debug("SELECT * FROM `{$this->_tabela}` {$where} {$orderby} {$limit} {$offset}");

	 	$query = $this -> db -> prepare("SELECT * FROM `{$this->_tabela}` {$where} {$orderby} {$limit} {$offset}");
	 	$query -> execute();
	 	return $query -> fetchAll(PDO::FETCH_ASSOC);
	}

	public function readLine( $where = null , $limit = null , $offset = null , $orderby = null, $debug = FALSE )
	{
		$where    = ($where   != null ? "WHERE {$where}"      : "");


		if($debug)
	 		$this -> debug("SELECT * FROM `{$this->_tabela}` {$where}");


	 	$query = $this -> db -> prepare("SELECT * FROM `{$this->_tabela}` {$where}");
	 	$query -> execute();
	 	return $query -> fetch(PDO::FETCH_ASSOC);
	}



	public function update( Array $dados, $where, $debug = FALSE )
	{
		$fields = $this -> getTableField();

		foreach ($dados as $ind => $val)
		{
			if(in_array($ind, $fields))
			{
				$campos[] = "{$ind} = :{$ind}";
				$dadosBD[$ind] = $val;
			}
		}


		$campos = implode(",", $campos);



		//FAZ O DEBUG DA STRING SQL
		if($debug)
		{
			foreach ($dadosBD as $key => $value){ $camposDebug[] = "$key = '$value'"; }
			$camposDebug = implode(",", $camposDebug);
			$this -> debug ( "UPDATE `{$this->_tabela}` SET {$camposDebug} WHERE {$where}" );
		}


		try
		{
			//PREPARA OS DADOS PARA INSERT USANDO PREPARED STATEMENTS
			$ps = $this -> db -> prepare("UPDATE `{$this->_tabela}` SET {$campos} WHERE {$where}");

			//PASSA OS VALORES CORRETOS BASEADOS NO PARAMETROS DA STRING
			foreach ($dadosBD as $key => $value){
			 	$ps->bindValue(":$key", $value);
			}

			//EXECUTA A QUERY
			$executa = $ps->execute();

		} catch (PDOexception $e) {
			echo $e->getMessage();
		}

		return $ps->rowCount();
	}


	public function delete( $where )
	{
		try
		{
			$ps = $this -> db -> prepare("DELETE FROM `{$this->_tabela}` WHERE {$where} ");
			$executa = $ps -> execute();

		} catch (PDOexception $e) {
			echo $e->getMessage();
		}

		return $ps->rowCount();
	}


	private function getTableField()
    {

        $fields = array();
        $result = $this->db->query("DESCRIBE `{$this->_tabela}`")->fetchAll();

        foreach ($result as $r) {
            array_push($fields, $r['Field']);
        }

        return $fields;

    }


    /////////////////////////////////////////////
    ///
    ///
    ///
    ///
    ///
    ///

    public function consulta($sql, $debug = FALSE)
	{
		if($debug) $this -> debug($sql);
		return $this -> db -> query($sql) -> fetchAll(PDO::FETCH_ASSOC);
	}


	public function consultaLinha ( $sql , $debug = FALSE )
	{
		if($debug) $this -> debug($sql);

		$query = $this -> db -> prepare($sql);
		$query -> execute();

		return $query -> fetch(PDO::FETCH_ASSOC);
	}

	public function consultaValor ( $sql , $debug = FALSE )
	{
		// Consultando
		$resultado = $this -> consultaLinha ( $sql , $debug );

		// Se retornou um Array
		if ( is_array ( $resultado ) )
		{
			// Retornando a primeira coluna
			return array_shift ( $resultado );
		}
		else
		{
			// Retornando falso
			return FALSE;
		}
	}


	public function populateFK()
	{
		foreach ($this -> fk as $key => $value)
		{
			$order = (isset($this -> orderby[$key])) ? "ORDER BY " . $this -> orderby[$key] : "";
			$dados[$key] = $this -> consulta("SELECT * FROM `{$key}` {$order}");
		}

		return $dados;
	}


	private function debug ( $sql )
	{
		echo "<hr/><pre>$sql</pre><hr/>";
	}
}











