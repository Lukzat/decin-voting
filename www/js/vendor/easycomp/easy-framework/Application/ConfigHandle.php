<?php
/**
 * Created by PhpStorm.
 * User: Lukáš Zatloukal
 * Date: 25.07.2018
 * Time: 8:23
 */

namespace Application;


use Symfony\Component\Yaml\Yaml;

class ConfigHandle implements IConfig {

    /** @var string $config_path */
    private $config_path = __CONFIG__ . '/config.yaml';
    /** @var array $config_data */
    private $config_data = [];
    /** @var Yaml $yaml */
    private $yaml;

    /**
     * ConfigHandle constructor.
     * @param Yaml $yaml
     */
    public function __construct(Yaml $yaml) {
        $path = realpath($this->config_path);
        $this->yaml = $yaml;
        if (!file_exists($path)) {
            $trace = debug_backtrace();
            trigger_error('File ' . $this->config_path . ' not exist in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'], E_USER_ERROR);
        } else {
            $this->config_data = $this->getAll();
            if (isset($this->config_data['imports'])) {
                foreach ($this->config_data['imports'] as $import) {
                    $path = __CONFIG__ . '/' . $import['resource'];
                    if (!file_exists($path)) {
                        $trace = debug_backtrace();
                        trigger_error('File ' . $path . ' not exist in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'], E_USER_ERROR);
                        exit();
                    } else {
                        $this->config_data = array_merge($this->config_data, $this->yaml::parseFile($path));
                    }
                }
            }
        }
    }

    /**
     * Vrací celý konfugurační soubor.
     * @return array
     */
    public function getAll(): array {
        return $this->yaml::parseFile($this->config_path);
    }


    public function __get($name) {
        if (array_key_exists($name, $this->config_data)) {
            return $this->config_data[$name];
        } else {
            $trace = debug_backtrace();
            trigger_error('Undefined property via __get(): ' . $name . ' in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'], E_USER_NOTICE);
            return null;
        }
    }

    public function __isset($name) {
        return isset($this->config_data[$name]);
    }
}
