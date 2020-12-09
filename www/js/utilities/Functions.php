<?php
/**
 * Created by PhpStorm.
 * Company: EasyComp s.r.o.
 * User: Štěpán Nikolić <snikolic@easycomp.cz>
 * Date: 19.11.2020
 * Time: 10:37
 */

namespace Utilities;


class Functions {

    public function is_valid_csv($csv_row) {
        if (gettype($csv_row) !== 'array')
            return 'Špatný formát CSV, nebo špatný oddělovač.';
//        if (!is_numeric($csv_row[0]))
//            return 'Není číslo, špatný formát: ' . $csv_row[0];
        if (is_numeric($csv_row[1]))
            return 'Není text (jméno), špatný formát: ' . $csv_row[1];
        if (is_numeric($csv_row[2]))
            return 'Není text (příjmení), špatný formát: ' . $csv_row[2];
        if (is_numeric($csv_row[3]))
            return 'Není text (politická strana), špatný formát: ' . $csv_row[3];
        if (strtotime($csv_row[5]) === false)
            return 'Není datum, špatný formát: ' . $csv_row[5];
        if (strtotime($csv_row[6]) === false)
            return 'Není čas, špatný formát: ' . $csv_row[6];
        return true;
    }

}
