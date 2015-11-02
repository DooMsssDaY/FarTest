<?php
/**
 * Класс для подключения к базе данных
 */	
Class Database
{
    /**
     * Получение подключениия к БД
     * @return object mysqli
     */
	public static function getConnection()
	{
	    $mysqli = new mysqli(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB_NAME);
	    $mysqli->query("SET NAMES 'utf8'");

	    return $mysqli;
	}
}
