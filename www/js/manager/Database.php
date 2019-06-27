<?php
/**
 * Created by PhpStorm.
 * User: Lukáš Zatloukal
 * Date: 02.02.2018
 * Time: 15:19
 */

namespace Manager;


use Nette\Caching\Storages\MemoryStorage;
use Nette\Database\Connection;
use Nette\Database\Context;
use Nette\Database\Structure;

class Database {

    protected static $instance;
    /** @var  Connection */
    protected static $connection;
    /** @var  MemoryStorage */
    private static $cacheMemoryStorage;
    /** @var  Structure */
    private static $structure;

    protected function __construct() {}

    /**
     * @return Context
     */
    public static function getInstance(){
        if (empty(self::$instance)) {

            /** @var object $_config    Konfigurační soubor s údaji pro připojení k databázi. */
            $_config = json_decode(file_get_contents(dirname(__FILE__) . '/../config/config.json'));

            self::$connection = new Connection('mysql:host='.$_config->database->host.';dbname='.$_config->database->name.';', $_config->database->user, $_config->database->password);    // Připojení k DB
            self::$cacheMemoryStorage = new MemoryStorage();
            self::$structure = new Structure(self::$connection, self::$cacheMemoryStorage);
            self::$instance = new Context(self::$connection, self::$structure);
        }

        return self::$instance;
    }
}