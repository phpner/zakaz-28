<?php


//Тут указываем на какой ящик посылать письмо
$to = "exr@gmail.com";
$replyto = "exr@gmail.com";
//Далее идет тема и само сообщение
// Тема письма
$subject = "Заявка на подписку";

// Сообщение письма

setlocale(LC_ALL, "russian");
$monthes = array(
    1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
    5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
    9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
);

$data = (date('d ') . $monthes[(date('n'))] . date(' Y, H:i:s'));

$message = "Дата: $data <br>";

foreach ($_POST as $name => $val) {
    if ($name == 'name') {
         $message .= "Имя - ".$val."<br>";      
    }elseif ($name == 'tel') {
         $message .= "Телефон - ".$val."<br>";
        
    }elseif ($name == 'email') {
         $message .= "Почта - ".$val."<br>";
        
    }else{
         $message .= $name." - ".$val."<br>";
    }
   
}

// Отправляем письмо при помощи функции mail();

 $headers =  "From: <$replyto>\r\n" . 
     "MIME-Version: 1.0\r\n".
     "Content-type: text/html; charset=\"utf-8\"\r\n".
     "Reply-To: $replyto\r\n" .
     "Return-Path: $replyto\r\n".
     "X-Mailer: PHP/" . phpversion();

mail ($to, $subject, $message, $headers);

header('Location: thanks.html');

?>