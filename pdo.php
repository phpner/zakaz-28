<?php

$param = parse_ini_file('congig.ini');

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

    $diameter =  array_unique(array_column($rows,'size'));

    $arrNames = array_unique(array_column($rows, 'name'));


    $dateBody = array_map(function($ro) use($rows){

                    foreach ($rows as  $row){
                        if ($ro == $row['name']){
                            $dateAee[$ro][] =  $row;
                        }
                    }
                        return $dateAee;

            },$arrNames);


    $body  = makeHtmlBody($dateBody);
    $names = makeHtmlNames($arrNames);
    $diameter = makeHtmlDiameter($diameter);

    $html = ['body' => $body, "names" => $names, "diameter" => $diameter];

    header('Content-Type: application/json');
    echo json_encode($html,JSON_OBJECT_AS_ARRAY);
    die();
}

/**
 * @param $dbh
 * @param $item
 * @param $diam
 * @param $table
 */

function getFilter($dbh,$item,$table){
    try {
        $dateBody = [];
        print_r('empty!');
        if (empty($item)){
            print_r('empty!');
            $sql = "SELECT * FROM ".$table;
            $stmt = $dbh->query($sql);
            $dateBody = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            foreach ($item  as $value){

                if ($value == "all"){
                    continue;
                }

                $valueS = trim($value);
                $sql = "SELECT * FROM ".$table." WHERE name = (SELECT name FROM ".$table." WHERE id = ".$valueS.")";
                $zap = $dbh->query($sql);
                foreach( $zap as $row) {
                    $dateBody[] = $row;
                }

            }
        }




        $body  = makeHtmlBody($dateBody);
        $diameter = makeHtmlDiameter($dateBody);

        $html = ['body' => $body, "names" => '', "diameter" => $diameter];

        header('Content-Type: application/json');
        echo json_encode($html,JSON_OBJECT_AS_ARRAY);
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
 * @param $dateBody
 * @return array
 */
function makeHtmlBody($dateBody){

    usort($dateBody, function($a,$b){
        return ($a['size']-$b['cost']);
    });


    $html = "";
     array_map(function ($dateRe) use(&$html){
            foreach ($dateRe as $key => $value){
                    $html .= "<h3 id='header-".$key."' class='filderAjax__header'><div class='wrapper'><i class='icon-square'></i> ".$key."</div></h3>
                              <div class='filderAjax__col'><div id='item-".$key."'></div></div>";

                foreach ($value as $val){
                        $html .="
                                <div id='' class='filderAjax__col__row'><div class='wrapper'>
                                <div class='filderAjax__col__item'>".$val['size']."</div>
                                <div class='filderAjax__col__item'>".$val['cost']."</div>
                                <div class='filderAjax__col__item'></div>
                                <div class='filderAjax__col__item'></div>
                                </div></div>";
                    }
            }
            return $html;
        },$dateBody);

        return $html;
}

/**
 * @param $names
 * @return string
 */

function makeHtmlNames($names){
    $nameOut = "";
    if (!empty($names)){

        /*Выбрать марку стали: */
        foreach ($names as $name){
            $nameOut .=  "
                           <div class=\"filter__col alax_col input_active\">
                                <label>
                                <input checked type='checkbox' value='".$name."'>
                                         <span></span>
                                         <p>".$name."</p>
                                </label>
                            </div>
                           ";
        }

    }
    return  $nameOut;
}

/**
 * @param $diameters
 * @return string
 */
function makeHtmlDiameter($diameters){
    $diameterOut = '';
    if (!empty($diameters)){

        foreach ($diameters as $val) {

            $diameterOut .= "<div class='filter__col alax_col'>
                                       <label>
                                         <input type='checkbox' value='" . $val . "'>
                                             <span></span>
                                         <p>" . $val . "</p>
                                </label>
                            </div>";
        }
    }

    return $diameterOut;
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