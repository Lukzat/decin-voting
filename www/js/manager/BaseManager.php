<?php
/**
 * Created by PhpStorm.
 * User: zatlo_000
 * Date: 08.03.2017
 * Time: 10:29
 */

namespace Manager;


use Nette\Caching\Storages\MemoryStorage;
use Nette\Database\Connection;
use Nette\Database\Context;
use Nette\Database\Structure;
use Tracy\Debugger;

/**
 * Základní třída pro komunikaci s modelem
 * @package Manager
 */
abstract class BaseManager {

    /** @var  Connection */
    private $connection;
    /** @var  MemoryStorage */
    private $cacheMemoryStorage;
    /** @var  Structure */
    private $structure;
    /** @var  Context */
    protected $database;

    /**
     * BaseManager constructor */
    public function __construct() {
        Debugger::enable(Debugger::DEVELOPMENT, __DIR__ . '/../log'); // Zapnutí Laděnky
        $this->connection = new Connection('mysql:host=172.17.0.1;port=3600;dbname=voting_data;', 'user', 'test');    // Připojení k DB
        $this->cacheMemoryStorage = new MemoryStorage();
        $this->structure = new Structure($this->connection, $this->cacheMemoryStorage);
        $this->database = new Context($this->connection, $this->structure);
    }
}
