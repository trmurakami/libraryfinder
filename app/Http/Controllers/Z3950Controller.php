<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Z3950Controller extends Controller

{
    // function to store file in 'upload' folder
    public function searchZ3950(Request $request)
    {

        //     static function query_z3950($query, $host, $host_name, $type)
        //     {


        // if ($query["isbn"]) {
        //     $query_data = '@attr 1=7 ' . $query["isbn"] . '';
        // }
        //  elseif ($type == "title") {
        //     if ((!empty($query[0])) && (!empty($query[1])) && (!empty($query[2]))) {
        //         $query_data = '@attrset gils @and @attr 1=4 @attr 2=3 ' . $query[0] . ' @attr 1=1003 @attr 2=3 ' . $query[1] . ' @attr 1=31 @attr 2=3 ' . $query[2] . '';
        //     } elseif ((!empty($query[0])) && (!empty($query[1]))) {
        //         $query_data = '@attrset gils @and @attr 1=4 @attr 2=3 ' . $query[0] . ' @attr 1=1003 @attr 2=3 ' . $query[1] . '';
        //     } elseif ((!empty($query[0])) && (!empty($query[2]))) {
        //         $query_data = '@attrset gils @and @attr 1=4 @attr 2=3 ' . $query[0] . ' @attr 1=31 @attr 2=3 ' . $query[2] . '';
        //     } elseif ((!empty($query[1])) && (!empty($query[2])) && (empty($query[0]))) {
        //         $query_data = '@attrset gils @and @attr 1=1003 @attr 2=3 ' . $query[1] . ' @attr 1=31 @attr 2=3 ' . $query[2] . '';
        //     } else {
        //         $query_data = '@attrset gils @attr 1=4 ' . $query[0] . '';
        //     }
        //     //print_r($query_data);
        // }

        $query_data = '@attr 1=7 '.$request->isbn.'';

        $id = yaz_connect($request->host);
        yaz_range($id, 1, 10);
        yaz_syntax($id, "usmarc");
        yaz_search($id, "rpn", $query_data);
        yaz_wait();

        $error = yaz_error($id);

        if (!empty($error)) {
            echo "Dedalus error: $error";
        } else {
            $hits = yaz_hits($id);
            //dd($hits);
            if ($hits >= 1) {
                for ($p = 1;
                $p <= $hits;
                $p++) {

                    $rec = yaz_record($id, $p, "string");
                    $result_record = self::parse_usmarc_string($rec);
                    $resultArray[] = $result_record;
                    //dd($result_record);
                }
            }
            return $resultArray;

        }
            // echo "<p>Dedalus - $hits resultado(s) </p>";

            // if ($hits >= 1) {

            //     for ($p = 1; $p <= $hits; $p++) {

            //         echo '<ul class="uk-subnav uk-subnav-pill" uk-switcher>
            // <li><a href="#">Resumo</a></li>
            // <li><a href="#">Registro completo</a></li>
            // </ul>         
            // <ul class="uk-switcher uk-margin">';

            //         echo '<li><table class="uk-table">
            //     <thead>
            //         <tr>
            //             <th>Fonte</th>
            //             <th>ISBN</th>
            //             <th>Título</th>
            //             <th>Autor</th>
            //             <th>Editora</th>
            //             <th>Local</th>
            //             <th>Ano</th>
            //             <th>Edição</th>
            //             <th>Descrição física</th>
            //             <th>Download</th>
            //         </tr>
            //     </thead>
            //     <tbody>    
            // ';



            //         $rec = yaz_record($id, $p, "string");
            //         //print_r($rec);
            //         //$result_record = self::parse_usmarc_string($rec);
            //         //print_r($result_record);
            //         $rec_download = yaz_record($id, $p, "raw");
            //         $rec_download = str_replace('"', '', $rec_download);
            //         echo '<tr>';
            //         echo '<th>Dedalus</th>';
            //         if (isset($result_record["isbn"])) {
            //             echo '<td>' . $result_record["isbn"] . '</td>';
            //         } else {
            //             echo '<td></td>';
            //         }
            //         echo '<td>' . $result_record["title"] . '</td>';

            //         if (!empty($result_record["author"])) {
            //             echo '<td>' . $result_record["author"] . '</td>';
            //         } else {
            //             echo '<td>Sem autor cadastrado</td>';
            //         }

            //         if (!empty($result_record["publisher"])) {
            //             echo '<td>' . $result_record["publisher"] . '</td>';
            //         } else {
            //             echo '<td>Sem editora cadastrada</td>';
            //         }

            //         if (!empty($result_record["pub_place"])) {
            //             echo '<td>' . $result_record["pub_place"] . '</td>';
            //         } else {
            //             echo '<td>Sem local</td>';
            //         }

            //         if (!empty($result_record["pub_date"])) {
            //             echo '<td>' . $result_record["pub_date"] . '</td>';
            //         } else {
            //             echo '<td>Sem data</td>';
            //         }

            //         if (isset($result_record["edition"])) {
            //             echo '<td>' . $result_record["edition"] . '</td>';
            //         } else {
            //             echo '<td></td>';
            //         }

            //         if (isset($result_record["extent"])) {
            //             echo '<td>' . $result_record["extent"] . '</td>';
            //         } else {
            //             echo '<td></td>';
            //         }
            //         echo '<td><button onclick="SaveAsFile(\'' . addslashes($rec_download) . '\',\'record.mrc\',\'text/plain;charset=CP1252\')">Baixar MARC</button></td>';
            //         echo '</tr>';
            //         echo '</tbody>
            // </table></li>';

            //         echo '<li>' . nl2br($rec) . '</li>';

            //         echo '</ul>';

            //        flush();
            //     }
            // }
        //}



    }

    static function parse_usmarc_string($record)
    {
        $ret = array();
        // there was a case where angle brackets interfered
        $record = str_replace(array("<", ">"), array("", ""), $record);
        //$record = utf8_decode($record);
        // split the returned fields at their separation character (newline)
        $record = explode("\n", $record);
        //examine each line for wanted information (see USMARC spec for details)
        foreach ($record as $category) {
            // subfield indicators are preceded by a $ sign
            $parts = explode("$", $category);
            // remove leading and trailing spaces
            array_walk($parts, "self::custom_trim");
            // the first value holds the field id,
            // depending on the desired info a certain subfield value is retrieved
            switch (substr($parts[0], 0, 3)) {
                case "008":
                    $ret["language"] = substr($parts[0], 39, 3);
                    break;
                case "020":
                    $ret["isbn"] = self::get_subfield_value($parts, "a");
                    break;
                case "022":
                    $ret["issn"] = self::get_subfield_value($parts, "a");
                    break;
                case "100":
                    $ret["author"] = self::get_subfield_value($parts, "a");
                    break;
                case "245":
                    $ret["title"] = self::get_subfield_value($parts, "a");
                    $ret["subtitle"] = self::get_subfield_value($parts, "b");
                    break;
                case "250":
                    $ret["edition"] = self::get_subfield_value($parts, "a");
                    break;
                case "260":
                    $ret["pub_date"] = self::get_subfield_value($parts, "c");
                    $ret["pub_place"] = self::get_subfield_value($parts, "a");
                    $ret["publisher"] = self::get_subfield_value($parts, "b");
                    break;
                case "300":
                    $ret["extent"] = self::get_subfield_value($parts, "a");
                    $ext_b = self::get_subfield_value($parts, "b");
                    $ret["extent"] .= ($ext_b != "") ? (" : " . $ext_b) : "";
                    break;
                case "490":
                    $ret["series"] = self::get_subfield_value($parts, "a");
                    break;
                case "502":
                    $ret["diss_note"] = self::get_subfield_value($parts, "a");
                    break;
                case "700":
                    $ret["editor"] = self::get_subfield_value($parts, "a");
                    break;
            }
        }
        return $ret;
    }

    // fetches the value of a certain subfield given its label
    static function get_subfield_value($parts, $subfield_label)
    {
        $ret = "";
        foreach ($parts as $subfield)
            if (substr($subfield, 0, 1) == $subfield_label)
                $ret = substr($subfield, 2);
        return $ret;
    }

    // wrapper function for trim to pass it to array_walk
    static function custom_trim(&$value, &$key)
    {
        $value = trim($value);
    }


}
