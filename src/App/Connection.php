<?php

namespace App;

class Connection
{

	public static function getDb()
	{
		$host = getenv('DB_HOST') ?: 'localhost';
		try {

			$conn = new \PDO(
				"mysql:host=$host:3306;dbname=twitter_clone;charset=utf8",
				"root",
				"root"
			);

			return $conn;
		} catch (\PDOException $e) {
				echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
				echo 'Código do erro: ' . $e->getCode();
		}
	}
}
