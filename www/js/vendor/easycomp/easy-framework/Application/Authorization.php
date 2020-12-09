<?php
/**
 * Created by PhpStorm.
 * Company: EasyComp s.r.o.
 * User: Lukáš Zatloukal <lzatloukal@easycomp.cz>
 * Date: 10.06.2019
 * Time: 12:45
 */

namespace Application;


use Authentication\JSONWT;
use Symfony\Component\Yaml\Yaml;

class Authorization implements IAuthorization {

    private static $acl;

    private $JSONWT;

    /**
     * Authorization constructor.
     * @param IConfig $configHandle
     */
    public function __construct(IConfig $configHandle) {
        $this->JSONWT = new JSONWT($configHandle);
    }


    public static function setAcl(string $path) {
        $path = realpath($path);
        if (!file_exists($path)) {
            $trace = debug_backtrace();
            trigger_error('File ' . $path . ' not exist in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'], E_USER_ERROR);
        } else {
            self::$acl = Yaml::parseFile($path);
        }
    }

    public function isAllowed(string $controller_name, string $method_name): int {
        if (is_null(self::$acl)) {
            trigger_error('ACL není nastaveno v index.php. Použijte funkci: Authorization::setAcl()', E_USER_ERROR);
            exit();
        }
        $permisions = self::$acl['admin'];
        if (!isset($permisions[$controller_name])) {
            trigger_error('Sekce: ' . $controller_name . ' není nastavena', E_USER_ERROR);
            exit();
        }
        $permisions = $permisions[$controller_name];
        if (!$this->JSONWT->isLogged()) {
            return 200;
        }
        $role = $this->JSONWT->getRole();
        if (isset($permisions[$method_name])) {
            $search = array_key_exists($role, $permisions[$method_name]);
            $global = $permisions['global'];
            $global_search = array_key_exists($role, $permisions['global']);
            if (empty($permisions[$method_name]) && empty($global)) {
//                dump(1);
                return 200;
            } else {
                if (!$search) {
                    if (empty($global) || !$global_search) {
//                        dump(2);
                        return 200;
                    } else if ($permisions['global'][$global_search] === 1) {
//                        dump(3);
                        return 200;
                    } else {
//                        dump(4);
                        return 403;
                    }
                } else if ($permisions[$method_name][$search] === 1) {
//                    dump(5);
                    return 200;
                } else {
//                    dump(6);
                    return 403;
                }
            }
        } else {
            trigger_error('Metoda: ' . $method_name . ' v sekci ' . $controller_name . ' není nastavena', E_USER_ERROR);
            exit();
        }
    }
}
