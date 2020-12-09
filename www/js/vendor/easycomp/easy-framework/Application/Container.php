<?php
/**
 * Created by PhpStorm.
 * User: Lukáš Zatloukal
 * Date: 24.07.2018
 * Time: 14:50
 */

namespace Application;


class Container {

    private $objects = [];

    public function get($class_name) {
        if ($class_name === self::class) {
            return $this;
        }

        if (!array_key_exists($class_name, $this->objects)) {
            // Pokud není třída inicializovaná, natáhnu su všechny její závislosti
            $dependecies = $this->getDependecies($class_name);
            $this->objects[$class_name] = new $class_name(...$dependecies);
        }

        return $this->objects[$class_name];
    }

    private function getDependecies(string $class_name, string $method_name = '__construct'): array {
        $dependencies = [];

        $reflection_class = new \ReflectionClass($class_name);
        if (!$reflection_class->hasMethod($method_name)) {
            return $dependencies;
        }

        $parameters = $reflection_class->getMethod($method_name)->getParameters();
        foreach ($parameters as $parameter) {
            if ($parameter->getClass()) {
                $dependencies[] = $this->get($parameter->getClass()->name);
            } else {
                // Neodpovídá pravidlu, kdy v konstruktoru mají být pouze závislosti.
                throw new \Exception("Depencency Injection: All parameters of $class_name::$method_name must be a class.");
            }
        }

        return $dependencies;
    }
}