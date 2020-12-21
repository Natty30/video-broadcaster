<?php

Class Connection
{

	private static $instance;
	private $pdo;

	private function __construct()
	{

		try {

    		$this->pdo = new PDO(
    			"mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD, [
        			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        			PDO::ATTR_EMULATE_PREPARES => false
        		]
    		);

    	} catch (Exception $e) {

			throw new Exception('Échec de la connexion à la base de donnée : '.$e->getMessage().'.', 1);
    		
    	}

	}

	public final static function getInstance()
	{

		if (!isset(self::$instance)) {
			self::$instance = new Connection();
		}

		return self::$instance;

	}

	public function getConnection()
	{

		return $this->pdo;

	}

}