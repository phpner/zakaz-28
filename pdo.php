<?php

$param = parse_ini_file('congig.ini');

var_dump($param);

$user = "root";
$pass = "";
$dbh = new PDO('mysql:host=localhost;dbname=zakaz-28', $user, $pass);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$fromPost = json_decode(file_get_contents('php://input'));

$item = $fromPost->item;
$diam = $fromPost->diam;
$diamParameter = $fromPost->diamParameter;
$tableFilter = $fromPost->tableFilter;



$table = (isset($_POST['table'])) ? $_POST['table'] : '' ;


if (!empty($table)){
    getAll($dbh,$table );
}

if (!empty($item) && !$diamParameter){
    getFilter($dbh,$item,$diam,$tableFilter);
}

if ($diamParameter){
    getByParameter($dbh,$diam,$tableFilter,$item);
}

/**
 * @param $dbh
 * @param $table
 */
function getAll($dbh,$table ){

    $sql = "SELECT * FROM ".$table;
    $stmt = $dbh->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $diameter =  filterDiameter($rows);

    header('Content-Type: application/json');
    echo json_encode(["row" => $rows, "param" => $diameter],JSON_OBJECT_AS_ARRAY);
    die();
}

/**
 * @param $dbh
 * @param $item
 * @param $diam
 * @param $table
 */

function getFilter($dbh,$item, $diam,$table){
    try {

        $dataToGo = [];
        foreach ($item  as $value){

            if ($value == "all"){
                continue;
            }

            $valueS = trim($value);
            $sql = "SELECT * FROM ".$table." WHERE name = (SELECT name FROM ".$table." WHERE id = ".$valueS.")";
            $zap = $dbh->query($sql);
            foreach( $zap as $row) {
                $dataToGo[] = $row;
            }


            $sql = "SELECT size,id FROM ".$table." WHERE name = (SELECT name FROM ".$table." WHERE id = ".$valueS.")";

            $nameOf = $dbh->query($sql);
            foreach($nameOf as $row) {
                $rowsParam[] = $row;
            }
        }


        header('Content-Type: application/json');
        echo json_encode(["row" => $dataToGo, "param" => $rowsParam],JSON_OBJECT_AS_ARRAY);
        $dbh = null;

        die();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

/**
 * @param $dbh
 * @param $diam
 * @param $table
 * @param $item
 */
function getByParameter($dbh,$diam, $table, $item){

    $newArra = [];
    $nameOfR = [];
    $newAfteArra = [];

    if (empty($diam)){
        foreach ($item as $name){
            if ($name == "all"){
                continue;
            }
            $sql = "SELECT * FROM ".$table." WHERE name = (SELECT name FROM ".$table." WHERE id = ".$name.")";
            $nameOf = $dbh->query($sql);
            foreach($nameOf as $row) {
                $newAfteArra[] = $row;
            }


            $sql = "SELECT size,id FROM ".$table." WHERE name = (SELECT name FROM ".$table." WHERE id = ".$name.")";

            $nameOf = $dbh->query($sql);
            foreach($nameOf as $row) {
                $rowsParam[] = $row;
            }

        }


    }else{
        foreach ($diam  as $value){

            if ($value == "all"){
                continue;
            }

            $sql = "SELECT * FROM ".$table." WHERE size = (SELECT size FROM ".$table." WHERE id = '".$value."') ";
            $size = $dbh->query($sql);


            foreach($size as $row) {
                $newArra[] = $row;
            }
        }

        foreach ($item as $name){
            if ($name == "all"){
                continue;
            }
            $sql = "SELECT name FROM ".$table." WHERE id = (SELECT id FROM ".$table." WHERE id = ".$name.") ";
            $nameOf = $dbh->query($sql);
            foreach($nameOf as $row) {
                $nameOfR[] = $row;
            }
        }

        foreach ($newArra as $newAr){

            foreach ($nameOfR as $nameOf)

                if ($nameOf['name'] == $newAr['name']){
                    $newAfteArra[] = $newAr;
                }
        }

    }


    header('Content-Type: application/json');
    echo json_encode(["row" => $newAfteArra, "param" => ""],JSON_OBJECT_AS_ARRAY);
    die();
}

/**
 * @param $diameter
 * @return array
 */
function filterDiameter($diameter){

    $size = [];
    foreach ($diameter as $row){
        $size[] = $row['size'];
    }

    $elCounts = array_count_values($size);

    $resultFilter = array_filter($diameter,function ($el) use($elCounts){
        $arr = [];
        foreach ($elCounts as $k => $v){
            if ($el['size'] == $k && $v == 1){
                return $el;
            }
        }
    });

    $diameterRes = array_map(function ($el){
        unset($el['name']);
        unset($el['sort']);
        unset($el['thickness']);
        unset($el['cost']);
        unset($el['sort']);
        return $el;
    },$resultFilter);

    return $diameterRes;
}

?>