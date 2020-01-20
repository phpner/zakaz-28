<?php


//Тут указываем на какой ящик посылать письмо
$to = "phpner@gmail.com";
$replyto = "phpner@gmail.com";
//Далее идет тема и само сообщение
// Тема письма

$subject = "Заявка с сайта ".$_SERVER[HTTP_HOST];

// Сообщение письма

setlocale(LC_ALL, "russian");
$monthes = array(
    1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
    5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
    9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
);

$data = (date('d ') . $monthes[(date('n'))] . date(' Y, H:i:s'));

$message = "Дата: $data <br>";

$headers =  "From: <$replyto>\r\n" .
    "MIME-Version: 1.0\r\n".
    "Content-type: text/html; charset=\"utf-8\"\r\n".
    "Reply-To: $replyto\r\n" .
    "Return-Path: $replyto\r\n".
    "X-Mailer: PHP/" . phpversion();


if (isset($_POST['formType']) && $_POST['formType'] === "cart"){
    if (isset($_POST['name'])){
        $message .= "Имя - ".$_POST['name']."<br>";
    }
    if (isset($_POST['email'])){
        $message .= "email - ".$_POST['email']."<br>";
    }
    if (isset($_POST['tel'])){
        $message .= "Телефон - ".$_POST['tel']."<br>";
    }
    if (isset($_POST['text'])){
        $message .= "Сообщения - ".$_POST['text']."<br>";
    }
    if (isset($_POST['item'])){
        $items = $_POST['item'];
    }

    $message .= "<hr><br><br> <h2>Корзина</h2><br>";

    foreach ($items as $item){
        if (isset($item['name'])){
            $message .= "Название - ".$item['name']."<br>";
        }
        if (isset($item['size'])){
            $message .= "Размер - ".$item['size']."<br>";
        }
        if (isset($item['cost'])){
            $message .= "Цена - ".$item['cost']."<br>";
        }
        if (isset($item['quantity'])){
            $message .= "Кол-во - ".$item['quantity']."<br><br>";
        }
    }

    if (isset($_POST['total'])){
        $message .= "Итого - ".$_POST['total']."<br><br>";
    }


    require dirname(__FILE__)."/cartDb.php";
    deleteAll();

    mail ($to, $subject, $message, $headers);
    header('Location: thanks.html');
    exit();
die();
}


if (isset($_POST['formType'])){
    $message .= "Форма - ".$_POST['formType']."<br>";
}
if (isset($_POST['name'])){
    $message .= "Имя - ".$_POST['name']."<br>";
}

if (isset($_POST['tel'])){
    $message .= "Телефон - ".$_POST['tel']."<br>";
}

if (isset($_POST['email'])){
    $message .= "Почта - ".$_POST['email']."<br>";
}

if (isset($_POST['inputName'])){
    $message .= "Название товара - ".$_POST['inputName']."<br>";
}

if (isset($_POST['inputPrice'])){
    $message .= "Цена на сайте - ".$_POST['inputPrice']."<br>";
}

if (isset($_POST['inputQuant'])){
    $message .= "Кол-во ".$_POST['inputQuant']." кг.<br>";
}

if (isset($_POST['inputSize'])){
    $message .= "Размер ".$_POST['inputSize']."<br>";
}


// Отправляем письмо при помощи функции mail();

mail ($to, $subject, $message, $headers);

header('Location: thanks.html');
exit();

?>