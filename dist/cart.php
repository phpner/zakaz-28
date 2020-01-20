<!DOCTYPE html>
<html lang="ru" prefix="og: http://ogp.me/ns#">
<head>
    <title>корзина покупателя</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">


    <link rel="stylesheet" href="css/style.css">
    <!-- <link rel="stylesheet" href="css/style.min.css?v=3"> -->

</head>
<body>
<header class="header" id="header">

    <div class="header__bg header__bg__list">
        <div class="header__wrap">
            <div class="header__top__mob">
                <img src="img/mob_logo.png" alt="">
                <div class="hamburger hamburger--3dx">
                    <div class="hamburger-box">
                        <img id="header__top__mob__logo" src="img/mob_logo.png" alt="logo">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </div>
            <div class="header__top">
                <div class="header__top__flex">
                    <img id="logo" src="img/logo.png" alt="logo">
                    <div class="hamburger hamburger--3dx is-active">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </div>

                <div class="header__top__row">
                    <div class="header__top__row__top">
                        <div class="header__top__row__top__tel__box">

                            <a class="header__top__tel__two" href="tel:+73833195545">+7 (383) 319 55 45</a>
                        </div>
                        <div class="header__top__email"><p class="header__top__email-spam" style="position: absolute;left: 1000000000%">info@transsibmetall.ru</p><img src="img/email.png" alt=""></div>
                        <div class="header__top__lang">
                            <select name="lang" id="">
                                <option value="rus">RU</option>
                                <option value="EN">EN</option>
                            </select>
                        </div>
                    </div>
                    <div class="header__top__row__bootom">
                        <a href="/">Главная</a>
                        <a href="/catalog.html">Каталог</a>
                        <a href="/contact.html">Контакты</a>
                        <a href="/delivery.html">Доставка</a>
                    </div>
                </div>
                <div class="header__top__box">

                    <a class="header__top__box__left popup-with-form" href="#test-subscribe"><img src="img/letter.svg" alt="">Подписаться на прайс</a>
                    <a class="header__top__box__right" href="transsibmetall_23.12.2019.pdf" target="_blank"><img src="img/cloud.svg" alt="">Скачать прайс (10.12.19)</a>
                </div>
            </div>
        </div>

        <section class="list__header">
            <div class="wrapper">
                <div class="truby__text__wrap">
                    <h1>Корзина</h1>
                    <span></span>
                </div>
                <a class="back__catalog" href="catalog.html"><i class="icon-arrD"></i>Вернуться в каталог</a>
            </div>
        </section>
    </div>

</header>


<div class="breadcrumbs">
    <div class="wrapper">
        <a href="/">Главная</a>
        <span>корзина</span>
    </div>
</div>
<div class="wrapper">
   <div class="cart">

       <?php

           $items = [];
           require dirname(__FILE__)."/cartDb.php";

          /* var_dump(getCart($dbh));*/
          $items = getCart($dbh);

          $count = count($items);
       if (isset($_COOKIE['user']) && $count > 0){
           $total = 0;
          ?>
               <div class="cart__box">
                   <h3>Товары <span><?php echo $count?></span></h3>
                   <form action="send.php" method="post">
                       <input type="hidden" name="formType" value="cart">
                        <div class="cart__box__row">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Название товара</th>
                                        <th>Размер</th>
                                        <th>Цена/Т.</th>
                                        <th>Кг.</th>
                                        <th>Удалить</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                foreach ($items as $item) :?>
                                    <tr data-cart-id="<?php echo $item['cart_id']?>" data-table="<?php echo $item['name_tb']?>" data-quantity="<?php echo $item['quantity']?>">
                                       <td class="cart__box__row__item">
                                           <span><?php echo $item['name']?></span>
                                           <input type="hidden" name="item[<?php echo  $i?>][name]" readonly value="<?php echo $item['name']?>">
                                       </td>
                                        <td class="cart__box__row__item">
                                            <span><?php echo $item['size']?></span>
                                            <input type="hidden" name="item[<?php echo  $i?>][size]" readonly value="<?php echo $item['size']?>">
                                            </td>
                                        <td class="cart__box__row__item">
                                            <?php  if ( (int) $item['cost']  === 0 || $item['quantity'] == ''){ ?>
                                                <span class="cart__box-empty">по запросу</span>
                                                <input type="hidden" name="item[<?php echo  $i?>][cost]" readonly value="по запросу">
                                            <?php }else{
                                                $str = preg_replace("/[^0-9]/", '',  $item['cost']);
                                                $total += (((int) $str * (int) $item['quantity'] ) / 1000 );
                                                    ?>
                                                <span><?php echo $item['cost']?></span>
                                                <input type="hidden" name="item[<?php echo  $i?>][cost]" readonly value="<?php echo $item['cost']?> ₽">
                                            <?php }?>
                                        </td>
                                        <td class="cart__box__row__item">
                                                <input class="cart__box__row__item-quantity" autocomplete="off" type="number" name="item[<?php echo  $i?>][quantity]" value="<?php echo $item['quantity']?>">
                                        </td>
                                        <td class="cart__box__row__item">
                                            <a href="#" class="cart__box-delete"></a>
                                        </td>
                                    </tr>
                                 <?php
                                    $i++;
                                   endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                       <div class="cart__form-total">
                           <span class="cart__title">ИТОГО:</span>
                           <span class="cart__num"><?php echo number_format($total, 2, ',', ' ') ;?> ₽</span> <br>
                           <input type="hidden" name="total" readonly value="<?php echo  number_format($total, 0, ',', ' ') ?>">
                          <span class="cart__title cart__nds">В том числе НДС:</span>
                           <span class="cart__num cart__nds"><?php echo number_format((($total * 20) / 100), 2, ',', ' ')?> ₽</span> <br>
                           <buttom class="cart__refresh">Пересчитать</buttom>
                       </div>
                       <div class="cart__form__info">
                           <div class="cart__form__info-cont">
                               <input type="text" name="name" placeholder="Имя" required>
                               <input type="text" name="email" placeholder="Email" required>
                               <input type="text" name="tel" placeholder="Телефон">
                           </div>
                           <textarea name="text" id="textarea" cols="15" rows="5" placeholder="Сообщение"></textarea>
                           <input type="submit" value="Заказать">
                       </div>
               </form>
               </div>
                        <?php   }else{ ?>
                    <h2 class="empty"> Корзина пуста!</h2>
                    <?php } ?>
   </div>
</div>


<footer class="footer">
    <div class="container">
        <div class="footer__row">
            <div class="footer__col__left">
                <img class="logo__bottom" src="img/logo.png" alt="">
                <p>
                    ООО “Трассибметалл”. <br>
                    Нержавеющий металлопрокат в Новосибирске
                </p>
                <a href="#">Политика конфиденциальности</a>
                <p>2019. Все права защищены.</p>
            </div>

            <div class="footer__col__right">
                <div class="footer__col__right__top">
                    <div class="footer__col__right__top__menu">
                        <a href="">Главная</a>
                        <a href="catalog.html">Каталог</a>
                        <a href="contact.html">Контакты</a>
                        <a href="/">Доставка</a>
                    </div>
                    <div class="footer__col__right__top__adress">
                        <span>Наш адрес:</span>
                        <p>
                            630105, а/я 254 г. Новосибирск, <br>
                            ул. Кропоткина, 271, 9 этаж
                        </p>
                        <span>Адрес склада:</span>
                        <p>
                            г. Новосибирск, ул. Тайгинская 6
                        </p>
                    </div>
                    <div class="footer__col__right__top__contact">
                        <span>Наши контакты:</span>
                        <a class="footer__col__right__top__contact__tel" href="tel:+73833195546">+7 (383) 319 55 46</a>
                        <a class="footer__col__right__top__contact__tel" href="tel:+73833195546">+7 (383) 319 55 45</a>
                        <div class="email-fake">
                            <p class="header__top__email-spam" style="position: absolute;left: 1000000000%">info@transsibmetall.ru</p>
                            <img src="img/email.png" alt="">
                        </div>
                        <div class="email-fake">
                            <p class="header__top__email-spam" style="position: absolute;left: 1000000000%">2731130@mail.ru</p>
                            <img style="width: 140px; margin-top: 5px" src="img/email-2-w.png" alt="">
                        </div>

                    </div>

                </div>
                <div class="footer__col__right__bottom">
                    <span class="func">Полезные функции:</span>
                    <div class="footer__col__right__bottom__box">
                        <a href="#test-subscribe" class="popup-with-form"><i class="icon-letter"></i>Подписаться на прайс</a>
                        <a href="transsibmetall_23.12.2019.pdf" target="_blank"><i class="icon-cloud"></i>Скачать прайс (10.12.19)</a>
                        <div class="lang__box">
                            <span>Смена языка:</span>
                            <select name="lang">
                                <option value="rus">RU</option>
                                <option value="EN">EN</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="footer__mob__box">
                    <a href="#">Политика конфиденциальности</a>
                    <p>2019. Все права защищены.</p>
                </div>
                <form class="mfp-hide white-popup-block form__subscribe" id="test-subscribe" method="post" action="send.php">
                    <span>Подписаться на прайс-лист</span>
                    <input type="text" name="name" placeholder="Ваше имя" required>
                    <input type="text" name="email" placeholder="E-mail" required>
                    <input type="submit" value="отправить">
                </form>

                <form class="mfp-hide white-popup-block form__item__buy"  method="post" action="send.php">
                    <span class="headerInner">Подписаться на прайс-лист</span>
                    <div class="form__item__buy__wrap">
                        <label for="quant-number">Кол-во <br> кг</label>
                        <input type="number" id="quant-number"   name="quant" placeholder="Кол-во м." required>

                    </div>
                    <div class="total__price">Цена <span class="priceIN"></span></div>
                    <input type="hidden" name="totalPrice">
                    <input  type="hidden" name="formType" value="Форма заказа">
                    <input class="inputSize" type="hidden" name="inputSize">
                    <input class="inputName" type="hidden" name="inputName">
                    <input class="inputPrice" type="hidden" name="inputPrice">
                    <input class="inputQuant" type="hidden" name="inputQuant">

                    <input class="inputTable" type="hidden" name="inputTable">
                    <input class="inputId" type="hidden" name="inputId">
                    <div class="submit__box">
                        <img src="img/basket_white.png" alt="">
                        <input type="submit" value="Добавить в корзинуы">
                    </div>
                </form>
            </div>
        </div>
    </div>
</footer>


<script src="js/libs/jquery-3.4.1.min.js"></script>
<script src="js/libs/slick.min.js"></script>
<script src="js/libs/sweetalert2@9.js"></script>
<script src="js/libs/jquery-ui.min.js"></script>
<script src="js/libs/jquery.magnific-popup.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>