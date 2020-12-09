<?php
/**
 * Created by PhpStorm.
 * Company: EasyComp s.r.o.
 * User: Vojtěch Heřmánek <vhermanek@easycomp.cz>
 * Date: 24.05.2019
 * Time: 9:05
 */

namespace TranslateService;


use Exception;

class TranslateOptions {

    const   PARAM_LANGUAGES = 'languages',
            PARAM_GLUE = 'glue',
            PARAM_FILTER_SECTIONS = 'filterSections',
            PARAM_APPEND = 'append';

    public $glue;
    public $filterSections;

    /** @var array Jazykový filtr */
    private $languages;
    /**
     * @var array
     */
    private $append;

    public function __construct() {
        $this->languages = [];
    }

    public function getArray() {
        return array_filter(get_object_vars($this), [$this, 'isNullOrEmpty']);
    }

    /**
     * Přidá zkratku vyžadované jazykové mutace
     * @param string|array $langAbbr
     * @return $this
     * @throws Exception pokud přijde něco jinýho než string nebo array
     */
    public function addLanguage($langAbbr) {
        $type = gettype($langAbbr);
        switch($type){
            case 'string':
                $this->languages[] = $langAbbr;
                break;
            case 'array':
                $this->languages = array_merge($this->languages, $langAbbr);
                break;
            default:
                throw new Exception('Jako vstup je považován string nebo array, předán ' .$type);
        }

        return $this;
    }

    /**
     * Nastaví pole id pojektů které chci připojit k hlavnímu projektu
     * <code> $options->setAppendIds([[4,1]]); <code>
     * @param array $append of arrays
     * @throws Exception
     */
    public function setAppendIds(array $append){
        foreach($append as $app){
            if(!is_array($app) || !isset($app[0]) || !isset($app[1])){
                throw new Exception('Předané pole obsahuje pár který nevyhovuje požadavkům');
            }
        }
        $this->append = $append;
    }

    private static function isNullOrEmpty($var) {
        return ($var !== null xor (is_array($var) && empty($var)));
    }

}
