<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', E_ALL);
/*Подключение к БД*/
require dirname(__FILE__)."/db_settings.php";

$cookieTime = time()+3600;

/*post*/
if (isset($_POST['cart']) && isset($_POST['action'])){

    if ($_POST['action'] === 'cartAdd'){
        cartAdd($dbh);
    }
    if ($_POST['action'] == "cartDelete"){

        $id = (int) $_POST['itemId'];
        cartDelete($dbh,$id);
    }

    if ($_POST['action'] == "cartQuantity"){

        cartQuantity($dbh);
    }
    if ($_POST['action'] == "cartRefresh"){

        cartRefresh($dbh);
    }

    if ($_POST['action'] == "cartIsEmpry"){

        isEmpty($dbh);
    }
}


function isEmpty($dbh){

      if (!isset($_COOKIE['user'])){
          die();
      }

    if (isset($_COOKIE['user'])){
        $cookie = $_COOKIE['user'];

        $sql        = "SELECT count(id) as count FROM cart WHERE cookie ='".$cookie."' ";
        $stmt       = $dbh->prepare($sql);
        $stmt->execute();
    }
   // print_r($cookie);
    $res = $stmt->fetch();
    if (!empty($res['count'] && $res['count'] > 0)){
         echo 'yes';
        die();
    }
    echo 'empty';
    die();
}

/**
 *  Delete all in BD and cookie user
 */
function deleteAll(){
    global $dbh;

        $sql        = "DELETE FROM cart";
        $stmt       = $dbh->prepare($sql);
        $stmt->execute();
        setcookie('user', '', time() - 3600);
}
/**
 * @param $dbh
 */
function cartRefresh($dbh){

    $updates = json_decode($_POST['update']);

    foreach ($updates as $update){
        $sql        = "UPDATE cart SET quantity = ? WHERE id = ?";
        $stmt       = $dbh->prepare($sql);
        $stmt->execute([$update->quan, $update->id]);
    }

}

/**
 * @param $dbh
 */
function cartQuantity($dbh){
    $nameTb     = trim($_POST['nameTable']);
    $itemId     = (int) $_POST['itemId'];

    $stmt = $dbh->prepare("SELECT quantity FROM cart WHERE name_tb = ? AND item_id = ? ");
    $stmt->execute([$nameTb,$itemId]);

    $res = $stmt->fetch();

    echo $res['quantity'];
}

/**
 * @param $dbh
 * @param $id
 */
function cartDelete($dbh,$id){

    $sql = "DELETE FROM cart WHERE id =".$id;
    $dbh->query($sql);
    echo "deleted!";
}

/**
 * @param $dbh
 * @return bool
 */
function cartAdd($dbh){


    global $cookieTime;

    $hash = md5($_SERVER['REMOTE_ADDR']);

    setcookie('user',$hash, $cookieTime,'/');

    /*first time or cookie was deleted*/
    if (!isset($_COOKIE['user'])){
        $sql = "DELETE FROM cart WHERE  cookie ='".$hash."' ";
        $dbh->query($sql);
    }

    $nameTb     = trim($_POST['nameTable']);
    $itemId     = (int) $_POST['itemId'];
    $iquantity  = trim($_POST['quant']);

    try {

        $stmt = $dbh->prepare("SELECT `name_tb`, `item_id` FROM cart WHERE name_tb = ? AND item_id = ? AND cookie = ?");
        $stmt->execute([$nameTb,$itemId,$hash]);

        if ($stmt->fetch()){
            $sql        = "UPDATE cart SET name_tb = ?, item_id = ?,quantity = ?,cookie = ? WHERE name_tb = ? AND item_id = ? AND  cookie = ? ";
            $stmt       = $dbh->prepare($sql);
            $stmt->execute([$nameTb, $itemId, $iquantity,$hash, $nameTb,$itemId,$hash]);
        }else{

            $stmt = $dbh->prepare("INSERT INTO cart (name_tb, item_id,quantity,cookie,expire) VALUES (?, ?,?,?,?)");

            $stmt->execute([$nameTb, $itemId, $iquantity,$hash, $cookieTime]);
        }

        /*Подсветка кнопки "купить" */
        $sql        = "SELECT `name_tb`, `item_id`FROM cart WHERE cookie ='".$hash."' ";
        $stmt = $dbh->query($sql);
        $lightUp = $stmt->fetchAll();

        /*Очистка базы от старих данных*/
        $now = time();
        $sql        = "DELETE FROM cart WHERE expire < ".$now." ";
        $stmt       = $dbh->prepare($sql);
        $stmt->execute();

        print_r(json_encode($lightUp));
        die();
        $dbh = null;
        echo 'yes';
        die();

    } catch (PDOException $e) {
        echo 'error';
        print_r($e->getMessage());
        return false;
    }

}

function getCart($dbh){
    $res = [];
    $cook = '';
    if (isset($_COOKIE['user'])){
        $cook = $_COOKIE['user'];
    }

    $sql = "SELECT id as cart_id, name_tb,item_id,quantity FROM cart WHERE cookie = '".$cook."'";
    $stmt = $dbh->query($sql);

    $names = $stmt->fetchAll();

    foreach ($names as $name){
       $n =  trim($name['name_tb']);
       $id =  trim($name['item_id']);
       if ($n === "")
           continue;

        $sql = "SELECT * FROM `{$n}` WHERE id = ".$id." ";
        $stmt = $dbh->query($sql);

        $fatch = $stmt->fetch();

        if ($fatch !== false)
            $res[] =  @array_merge($fatch,$name);

    }



    return $res;

}



