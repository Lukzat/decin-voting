<?php
/**
 * Created by PhpStorm.
 * User: Lukáš Zatloukal
 * Date: 25.07.2018
 * Time: 9:08
 */

namespace Application;


interface IConfig {

    public function getAll(): array;

    public function __get($name);

    public function __isset($name);
}