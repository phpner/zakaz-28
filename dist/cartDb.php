<?php
/*Подключение к БД*/
$user = "root";
$pass = "";
$dbh = new PDO('mysql:host=localhost;dbname=zakaz-28', $user, $pass);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$cookieTime = time()+3600;

/*post*/
if (isset($_POST['cart']) && isset($_POST['action'])){

    if ($_POST['action'] === 'cartAdd'){

        setcookie('user',md5($_SERVER['REMOTE_ADDR']), $cookieTime);

        $param = [
            "nameTb" => $_POST['nameTable'],
            "itemId" => $_POST['itemId'],
            "quantity" => $_POST['quant'],
        ];

        cartAdd($dbh,$param);
    }
    if ($_POST['action'] === 'cartDelete'){
        $id = (int) $_POST['itemId'];
        cartDelete($id);
    }
}

function cartAdd($dbh,$param){

    $cookie     = $_COOKIE['user'];

    $nameTb     = trim($param["nameTb"]);
    $itemId     = (int) $param["itemId"];
    $iquantity  = trim($param["quantity"]);

    try {

        $stmt = $dbh->prepare("SELECT `name_tb`, `item_id` FROM cart WHERE name_tb = ? AND item_id = ? ");
        $stmt->execute([$nameTb,$itemId]);

        if ($stmt->fetch()){
            $sql        = "UPDATE cart SET name_tb = ?, item_id = ?,quantity = ?,cookie = ? WHERE name_tb = ? AND item_id = ?";
            $stmt       = $dbh->prepare($sql);
            $stmt->execute([$nameTb, $itemId, $iquantity,$cookie, $nameTb,$itemId]);
        }else{

            $stmt = $dbh->prepare("INSERT INTO cart (name_tb, item_id,quantity,cookie) VALUES (?, ?,?,?)");

            $stmt->execute([$nameTb, $itemId, $iquantity,$cookie]);
        }

        /*Подсветка кнопки "купить" */
        $sql        = "SELECT `name_tb`, `item_id`FROM cart WHERE cookie ='".$cookie."' ";
        $stmt = $dbh->query($sql);

        $lightUp = $stmt->fetchAll();
        print_r($lightUp);
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
    $cook = $_COOKIE['user'];

    $sql = "SELECT id as cart_id, name_tb,item_id,quantity FROM cart WHERE cookie = '".$cook."'";
    $stmt = $dbh->query($sql);

    $names = $stmt->fetchAll();
    $i=0;
    foreach ($names as $name){
       $n =  trim($name['name_tb']);
       $id =  trim($name['item_id']);

        $sql = "SELECT * FROM ".$n." WHERE id = ".$id." ";
        $stmt = $dbh->query($sql);

        $res[] =  array_merge( $stmt->fetch(),$name);
       /// $res[] = array_merge(  $res[$i],$name);

    }


    return $res;

}



