<?php
/**
 * Created by PhpStorm.
 * User: Lukáš Zatloukal
 * Date: 23.07.2018
 * Time: 20:02
 */

namespace Application;


class Controller {

    /** @var ResultHandle $resultHandle */
    private $resultHandle;

    /**
     * Controller constructor.
     */
    public function __construct() {
        $this->resultHandle = new ResultHandle();
    }

    /**
     * Zakóduje pole/string/Selection do JSONu.
     * @param $data
     * @return string
     */
    protected function encodeJson($data): string {
        return $this->resultHandle->encodeJson($data);
    }

    /**
     * Dekóduje JSON string, vrací asociované pole.
     * @param string $json
     * @return array
     */
    protected function decodeJson(string $json): array {
        return $this->resultHandle->decodeJson($json);
    }

    /**
     * Nastaví HTTP hlavičku
     * @param mixed|null $body              Tělo hlavičky - data která se mají poslat Angular interceptoru.
     * @param int $code                     HTTP stavový kód.
     * @param string|null $message          Zpráva - zobrazí se jako flashmessage na klienstké části, 200 jako success, 4xx jako chybová hláška.
     * @param bool $modifyHeader            Přepínač pro odeslání HTTP hlavičky.
     */
    public function setHeader($body = null, int $code = 200, string $message = null, bool $modifyHeader = true) {
        $this->resultHandle->setHeader($body, $code, $message, $modifyHeader);
    }

    /**
     * Vytvoří z Array hashe asociativní pole.
     * @param array $data
     * @return array
     */
    public function iteratorToArray(array $data): array {
        return $this->resultHandle->iteratorToArray($data);
    }
}