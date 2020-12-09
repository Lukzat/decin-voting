<?php
/**
 * Created by PhpStorm.
 * Company: EasyComp s.r.o.
 * User: Lukáš Zatloukal <lzatloukal@easycomp.cz>
 * Date: 12.09.2019
 * Time: 15:23
 */

namespace Application;


interface IAuthorization {

    /**
     * Setne ACL konfigurační soubor
     * @param string $path  Cesta k souboru.
     * @return mixed
     */
    public static function setAcl(string $path);

    /**
     * Zkontroluje zda je metoda $method_name v $controller_name nastavena a zda má uživatel práva.
     * Vrací stavový kod
     * 200 - úspěšně ověřeno
     * 403 - autorizace selhala, 403 kod bude následně odeslán pomocí metody setHeader()
     * @param string $controller_name
     * @param string $method_name
     * @return int
     */
    public function isAllowed(string $controller_name, string $method_name): int;
}
