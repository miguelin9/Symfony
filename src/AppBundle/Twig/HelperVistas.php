<?php

namespace AppBundle\Twig;

class HelperVistas extends \Twig_Extension{
    
    public function getFunctions() {
        return [
            'generateTable' => new \Twig_SimpleFunction(
                'generateTable', [$this, 'generateTable']
            ),
        ];
    }

    public function generateTable($num_columns, $num_rows) {
        $table = "<table class='table' border=1>";
        for ($i = 0; $i < $num_rows; $i++) {
            $table.="<tr>";
            for($j = 0; $j < $num_columns; $j++) {
                $table.="<td>F:$i C:$j </td>";
            }
            $table.="</tr>";
        }
        $table.="</table>";
        return $table;
    }

    public function getName() {
        return "app_bundle";
    }
    
}

