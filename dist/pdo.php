<?php
/*Подключение к БД*/
require dirname(__FILE__)."/db_settings.php";

/*Получение данных*/
$fromPost = json_decode(file_get_contents('php://input'));

$item = $fromPost->item;
$diam = $fromPost->diam;
$diamParameter = $fromPost->diamParameter;
$tableFilter = $fromPost->tableFilter;

/*Проверка и запуск нужной функции*/


$table = (isset($_POST['table'])) ? $_POST['table'] : '' ;
if (!empty($table)){
    getInit($dbh,$table );
}

if (!empty($item) && !$diamParameter){
    filterByName($dbh,$item,$tableFilter);
}

if ($diamParameter){
    filterBySize($dbh,$diam,$tableFilter);
}


function lightUpSeleced($dbh){
    /*Подсветка кнопки "купить" */
    $cookie     = $_COOKIE['user'];

    $sql        = "SELECT `name_tb`, `item_id`FROM cart WHERE cookie ='".$cookie."' ";
    $stmt = $dbh->query($sql);
    $lightUp = $stmt->fetchAll();
   return json_encode($lightUp);
}

/*Шлак для файк данных*/
function rendomNum() {
    $fake = "";
    $i = rand(1,5);

    for ($i; $i<=5; $i++){
        $fake .= "<i class=' icon-bag'></i>";
    }
    return $fake;
}

/**
 * @param $data
 * @param $kye
 * @return array
 */
function filterFormHtml($data, $kye){
    $temp_array = array();
    $key_array = array();

    foreach($data as $val) {
        if (!in_array($val[$kye], $key_array)) {
            $key_array[] = $val[$kye];
            $temp_array[] = ['id' => $val['id'],$kye => $val[$kye]];
        }
    }
    return $temp_array;
}

/**
 * @param $dbh
 * @param $table
 */
function getInit($dbh,$table ){

    $sql = "SELECT * FROM ".$table;
    $stmt = $dbh->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $htmlSize =  renderHtmlSize($rows);
    $htmlName =  renderHtmlName($rows);
    $htmlBody =  renderHtml($rows,$table);

    $lightUp = lightUpSeleced($dbh);

    header('Content-Type: application/json');
    echo json_encode(["row" => $htmlBody, "param" => $htmlSize,'names' => $htmlName,'light' =>  $lightUp],JSON_OBJECT_AS_ARRAY);
    $dbh = null;
    die();
}

/**
 * @param $dbh
 * @param $item
 * @param $table
 */
function filterByName($dbh,$itemId,$table){
    $rows = [];
    $lightUp = lightUpSeleced($dbh);
    try {
        foreach ($itemId  as $id){
            if ($id == "all"){
                continue;
            }

            $sql = "SELECT * FROM ".$table." WHERE name = (SELECT name FROM ".$table." WHERE id = ".$id.")";

            $stmt = $dbh->query($sql);

            foreach($stmt as $row) {
                $rows[] = $row;
            }
        }

        $htmlSize =  renderHtmlSize($rows);
        $htmlBody =  renderHtml($rows,$table);

        header('Content-Type: application/json');
        echo json_encode(["row" => $htmlBody, "param" => $htmlSize,'light' =>  $lightUp],JSON_OBJECT_AS_ARRAY);
        $dbh = null;
        die();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

/**
 * @param $dbh
 * @param $sizesId
 * @param $table
 */
function filterBySize($dbh,$sizesId, $table){
    $lightUp = lightUpSeleced($dbh);
    if (!empty($sizesId)){
        foreach ($sizesId as $id){
            if ($id == "all"){
                continue;
            }
            $sql = "SELECT * FROM ".$table." WHERE size = (SELECT size FROM ".$table." WHERE id = ".$id.")";
            $stmt = $dbh->query($sql);
            foreach($stmt as $row) {
                $rows[] = $row;
            }
        }

        $htmlBody =  renderHtml($rows,$table);

        header('Content-Type: application/json');
        echo json_encode(["row" => $htmlBody,'light' =>  $lightUp],JSON_OBJECT_AS_ARRAY);
        $dbh = null;
        die();
    }

        header('Content-Type: application/json');
        echo json_encode('no',JSON_OBJECT_AS_ARRAY);
        $dbh = null;
        die();
}

/**
 * @param $data
 * @return string
 */
function renderHtml($data,$table){
    $html = '';
    $temp_array = array();
    foreach($data as $val) {
        $temp_array[$val['name']][] = $val;
    }

    foreach($temp_array as $name => $vals) {

            $html .= "<h3 id='header-".$name."' class='filderAjax__header'>
                        <div class='wrapper'>
                            <i class='icon-square'></i> ".$name."
                         </div>
                       </h3>";

            $html .= "<div class='filderAjax__col'> 
                            <div id='item-'>";

                foreach ($vals as $val){
                    $html .= "<div id='".$val['id']."' class='filderAjax__col__row'>
                                    <div class='wrapper'>
                                         <div class='filderAjax__col__item'>".$val['size']."</div>
                                         <div class='filderAjax__col__item'>".$val['cost']."</div>
                                         <div class='filderAjax__col__item weight-filter'>".rendomNum()."</div>
                                         <div class='filderAjax__col__item weight-filter'>".rendomNum()."</div>
                                         <div class='filderAjax__col__item'><a class='buy-btn' data-table='".$table." 'data-id='".$val['id']."'  data-name='".$val['name']."' data-size='".$val['size']."' data-price='".$val['cost']."'  href='#'></a></div>
                                    </div>
                               </div>";
                }
             $html .= "</div> </div>";

    }


    return $html;
}

/**
 * @param $data
 * @return string
 */
function renderHtmlSize($data){
    $html ='';
    $temp_array =  filterFormHtml($data,'size');

    foreach ($temp_array as $temp){

        $html .= "<div class='filter__col alax_col'>
                            <label>
                                     <input type='checkbox' value='".$temp['id']."' >
                                     <span></span>
                                  <p>".$temp['size']."</p>
                            </label>
                    </div>";
    }

    return $html;
}

/**
 * @param $data
 * @return string
 */
function renderHtmlName($data){
    $html ='';

    $temp_array =  filterFormHtml($data,'name');

    foreach ($temp_array as $temp){

        $html .= "<div class='filter__col alax_col input_active'>
                            <label>
                                     <input type='checkbox' value='".$temp['id']."' >
                                     <span></span>
                                  <p>".$temp['name']."</p>
                            </label>
                    </div>";
    }

    return $html;
}




?>