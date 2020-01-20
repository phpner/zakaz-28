$(document).ready(function($){



/*email copy*/
    $('.header__top__email,.email-fake').on('click',function(){
        const Toast = Swal.mixin({
            toast: true,
            position: 'center',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        var element = this;

        console.log(element);

        var range, selection, worked;

        if (document.body.createTextRange) {
            range = document.body.createTextRange();
            range.moveToElementText(element);
            range.select();
        } else if (window.getSelection) {
            selection = window.getSelection();
            range = document.createRange();
            range.selectNodeContents(element);
            selection.removeAllRanges();
            selection.addRange(range);
        }

        //window.Swal.fire('Any fool can use a computer');

    try {
            document.execCommand('copy');
        Toast.fire({
            icon: 'success',
            title: 'Скопировано'
        });
        }
        catch (err) {
            alert('unable to copy text');
        }
    });



    $('.popup-with-form').magnificPopup({
        type: 'inline',
        preloader: false,
        focus: '#name',

        // When elemened is focused, some mobile browsers in some cases zoom in
        // It looks not nice, so we disable it:
        callbacks: {
            beforeOpen: function() {
                if($(window).width() < 700) {
                    this.st.focus = false;
                } else {
                    this.st.focus = '#name';
                }
            }
        }
    });

    var headerSlider = $(".header__slider");
    headerSlider.slick({
        arrows:true,
        dots: true,
    });

    var newsSlider = $(".news__slider");
    newsSlider.slick({
        infinite: true,
        arrows:false,
        dots: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 500,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows:true,
                    infinite: true,
                    dots: true
                }
            }
        ]
    });

    function photoSliderFunc(){
        var width = $( window ).width();// ширина области просмотра браузера
        var photoSlider = $(".photo__row");
        if (width < 992){
            photoSlider.slick({
                infinite: true,
                arrows:true,
                dots: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 500,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: true
                        }
                    }
                ]
            });

        }else {
            setTimeout(function () {
                photoSlider.slick('unslick');
            },3000)

        }
    }

    var arccor =  $(".filter-body__center__innder" );
    var arccorChack = false;

    function initArrcod(){
        var width = $( window ).width();// ширина области просмотра браузера
        if (width < 992){
            arccorChack = true;

            arccor.accordion({
                heightStyle: "content",
                active: false,
                collapsible: true,
                icons: { "header": "icon-cross", "activeHeader": "icon-minus" }
            });

        }else {
            if (arccorChack){
                arccorChack = false
                arccor.accordion( "destroy" );
            }

        }

    }

    $( window ).resize(function(){
        photoSliderFunc();
        initArrcod()
    });

    photoSliderFunc();
    initArrcod();


    headerSlider.on('afterChange', function(event, slick, currentSlide, nextSlide){
        var indexActive = $(".header__slider  .slick-dots .slick-active").index(".header__slider  .slick-dots li");
        indexActive = (indexActive + 1);
        changeIndexActive(indexActive);
    });

    function changeIndexActive(index){
        index = typeof index !== 'undefined' ?  index : 1;
        var datsHeader = $(".header__slider  .slick-dots li").length;

        var dotsIn = "<span>"+index+"/</span><span>"+datsHeader+"</span>";

        $(".header__slider__wrapper__number").html(dotsIn);
    }
    changeIndexActive();


    $('.image-popup-no-margins').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
            verticalFit: true
        }
    });

    $(".hamburger").on("click",function () {

        $(".header__top").toggleClass("header__top__active")
    });


    $(".input__fake").on('click',function(){

        var filterLeft = $(this).next(".filter__col__left");

        if (!filterLeft.hasClass("active__select")){
            $(".filter__col__left").removeClass('active__select');

            filterLeft.addClass('active__select');
            $("body").addClass('active__select__body');
        }else{
            filterLeft.removeClass('active__select');
            $("body").removeClass('active__select__body');
        }
        return false
    });

    $(document).on("click",'.active__select__body',function(event){

        if (!$(event.target).closest('.filter__col__left ').length){
            $(".filter__col__left").removeClass('active__select');
            $("body").removeClass('active__select__body');
        }

        return false
    });



    /*filter*/

    var item = [];
    var diameter = [];

    /*Обработка фильтра*/
    $(document).on('click',".filter__col",function(event){
        var all = true;
        event.preventDefault();

        item = [];
        diameter = [];
        var thisCheckBox = $(this).find('[type="checkbox"]');

        var parent =  $(this).closest('.filter__col__left');

        var parentBox =  $(this).closest('.filter__box__input');
        var classClick = '';


        if (thisCheckBox.attr('value') == "all"){

            var checkBox = parent.find("[type=\"checkbox\"]");


            if (!thisCheckBox.is(":checked")){

                parent.find('.filter__col').addClass("input_active");

                thisCheckBox.attr('Checked','Checked');

                checkBox.each(function () {
                    if (!$(this).is(":checked")){
                        $(this).attr('Checked','Checked');
                    }
                });

            }else {
                if (!$(parentBox).hasClass("filter__box__diam")){
                    $(".filter__box__diam .filter__col__left").html('');
                    all = false;
                }

                parent.find(".filter__col").removeClass("input_active");
                checkBox.each(function () {
                    if ($(this).is(":checked")){
                        $(this).removeAttr('Checked');
                    }
                });
            }
            $(".filter__box__name .filter__col input[type=checkbox]:checked").each(function (i,v) {
                item.push(this.value);
            });

            $(".filter__box__diam .filter__col input[type=checkbox]:checked").each(function (i,v) {
                diameter.push(this.value);

            });

        }else {
            parent.find(":input[value=\"all\"]").removeAttr('Checked');
            parent.find(":input[value=\"all\"]").closest('.filter__col').removeClass("input_active");

            if(!thisCheckBox.is(":checked")){
                thisCheckBox.attr('Checked','Checked');
            }else {
                thisCheckBox.removeAttr('Checked');
            }

            $(this).toggleClass("input_active");

            $(".filter__box__name .filter__col input[type=checkbox]:checked").each(function (i,v) {
                item.push(this.value);
            });

            $(".filter__box__diam .filter__col input[type=checkbox]:checked").each(function (i,v) {
                diameter.push(this.value);
            });

        }

        if ($(parentBox).hasClass("filter__box__name")){
            if (item.length <= 0 ){
                all = false;
            }

            ajaxItem(item,all);

        } else if ($(parentBox).hasClass("filter__box__diam")) {

            if (item.length <= 0 ){
                item = [];
                $(".filter__box__name .filter__col input[type=checkbox]").each(function (i,v) {
                    item.push(this.value);
                });
            }

            if (diameter.length <= 0){
                item = [];
                $(".filter__box__name .filter__col input[type=checkbox]:checked").each(function (i,v) {
                    item.push(this.value);
                });
            }

            ajaxParametr(diameter);
        }

    });


    /*Шлак для файк данных*/
    function rendomNum() {
        var iconR = "";
        numR = parseInt(Math.random() * (4 - 1) + 1);

        for(var ro=0; ro < numR; ro++){
            iconR += "<i class=' icon-bag'></i>";
        }
        return iconR;
    }

    function reLoadFilter(load) {
        if (load){
            $(".filter-body").append("<img class='load-gif' src='img/load.gif'>");
            $(".filter-body__header, .filter-body__center").css({opacity: "0.2"})
        }
        else {
            $(".filter-body__header, .filter-body__center").css({opacity: "1"})
            $(".load-gif").remove();
        }
    }

    function lightUP(response,tableName) {

        var light = JSON.parse(response.light);

        light.forEach(function (v) {

            setTimeout(function () {
                console.log(tableName);
                if (tableName === v.name_tb) {
                    $("[data-id='" + v.item_id + "']").addClass('in-cart');
                    console.log(v.name_tb);

                    $(".cart-icon-box").addClass("cart-in-i");
                }
            }, 800);
        });
    }
    /*  console.log(dataJson);*/
    function ajaxParametr(diameter){

        diameter = typeof diameter !== 'undefined' ?  diameter : "";

        var tableName = $(".filter").attr('id');
        dataJson =  JSON.stringify({"diam": diameter, "tableFilter" :tableName, "diamParameter": true});

        $.ajax({
            url: "pdo.php",
            type: "JSON",
            data: dataJson,
            beforeSend:function(){
                reLoadFilter(true);
            },
            success: function(response) {
                reLoadFilter(false);

                if (!checkFilterResult(response))
                    return;

                $(".filter-body__center__innder").html(response.row);

                lightUP(response,tableName);

                if (arccorChack){
                    arccor.accordion( "refresh" );
                }

            },
            error: function (er) {
                console.log(er.responseText)
            }
        });
    }

    /*filter by parameter*/
    function ajaxItem(item){
        diameter = typeof diameter !== 'undefined' ?  diameter : "";
        var tableName = $(".filter").attr('id');
        dataJson =  JSON.stringify({'item': item, "diam": diameter, "tableFilter" :tableName, "diamParameter": false});
        $.ajax({
            url: "pdo.php",
            type: "JSON",
            data: dataJson,
            beforeSend:function(){
                reLoadFilter(true);
            },
            success: function(response) {
                reLoadFilter(false);

                if (!checkFilterResult(response))
                    return;

                $(".filter__box__diam .filter__col__left").html('');

                $(".filter__box__diam .filter__col__left").append(response.param);
                $(".filter-body__center__innder").html(response.row);

                lightUP(response,tableName);

                if (arccorChack){
                    arccor.accordion( "refresh" );
                }

            },
            error: function (er) {
                console.log(er.responseText)
            }
        });
    }

    /*firs select data*/
    if($(".filter").length >0){
        ajaxItemInit();
    }
    function ajaxItemInit(){
        var tableName = $(".filter").attr('id');
        var table = 'table='+tableName;

        $.ajax({
            url: "pdo.php",
            type: "POST",
            data: table,
            beforeSend:function(){
                reLoadFilter(true);
            },
            success: function(response) {
                reLoadFilter(false);

                if (!checkFilterResult(response))
                    return;

                $(".filter__box__diam .filter__col__left").html('');
                $(".filter__box__name .filter__col__left").html('');

                $(".filter__box__name .filter__col__left").append('<div class="filter__col title input_active">\n' +
                    '           <label>\n' +
                    '                <input checked type="checkbox" value="all">\n' +
                    '                     <span></span>\n' +
                    '                     <p>Все марки</p>\n' +
                    '           </label>\n' +
                    '</div>')
                    .append(response.names);

                $(".filter__box__diam .filter__col__left").append(response.param);
                $(".filter-body__center__innder").append(response.row);

                lightUP(response,tableName);

                if (arccorChack){
                    arccor.accordion( "refresh" );
                }
            },
            error: function (er) {
                console.log(er.responseText)
            }
        });

    }

    function checkFilterResult(response) {
        if (typeof response == 'undefined' || response == 'no' ||   response.length <= 0) {
            console.log('пусто!')
            $(".filter-body__center__innder").html("<span class='no__found'>Ничего не найдено!<span>");
            return false;
        }
        return true;
    }

    /*Отправка заказа / Корзина */
    $(document).on("click", ".buy-btn",function(event){
        event.preventDefault();

        $(".form__item__buy .submit__box").html("<div class=\"submit__box form-in-cart\">\n" +
            "<img src=\"img/basket_white.png\" alt=\"\">\n" +
            "<input type=\"submit\" value=\"Добавить в корзину\">\n" +
            "</div>");

        var name = $(event.target).attr('data-name');
        var size = $(event.target).attr('data-size');
        var price = $(event.target).attr('data-price');
        var tabel = $(event.target).attr('data-table');
        var itemId = $(event.target).attr('data-id');

        $(".headerInner").html("<p class='title_For'>Название</p><p>"+name+"</p>"+" <hr> <p class='title_For'>Размер </p><p>" + size +"</p> <hr> <p class='title_For'>Цена с НДС за т.</p><p>" + price +"</p> <hr>");

        $(".form__item__buy .size").val(size);

        $(".form__item__buy .inputName").val(name);
        $(".form__item__buy .inputPrice").val(price);
        $(".form__item__buy .inputSize").val(size);

        $(".form__item__buy .inputTable").val(tabel);
        $(".form__item__buy .inputId").val(itemId);

        /*reset*/

        var cost= '';
        cost = price.replace(/\D+/g,"");

        if (cost === "" || cost === "0"){
            $(".total__price span").html("по запросу");
            $(".form__item__buy .inputQuant").val(1);
        }
        else {
            $(".form__item__buy .total__price span").html(cost * 1 / 1000);
            $(".form__item__buy .inputQuant").val('1');
        }

        $(".form__item__buy [name=\"quant\"]").val("1");



        if($(this).hasClass('in-cart')){

            var tableName = $(".filter").attr('id');
            var itemId    = $(this).attr('data-id');
            var data = "cart=1&nameTable="+tableName+"&itemId="+itemId+"&action=cartQuantity";
            $.ajax({
                url: "cartDb.php",
                type: "POST",
                data: data,
                success: function(response) {
                    $(".form__item__buy .submit__box").html("<div class=\"submit__box form-in-cart\">\n" +
                        "<img src=\"img/basket-in.png\" alt=\"\">\n" +
                        "<input type=\"submit\" value=\"Обновить корзине\">\n" +
                        "</div>");
                    $(".form__item__buy .inputQuant").val(response);
                    $(".form__item__buy [name=\"quant\"]").val(response);
                    $(".form__item__buy .total__price span").html(cost * response / 1000);
                },
                error: function (er) {
                    console.log(er.responseText)
                }
            });
        }

        $.magnificPopup.open({
            items: {
                src: '.form__item__buy'
            },
            type: 'inline',

        });
    });

    $(document).on("input", ".form__item__buy input[type=number]",function(event){

        var cost = $(".form__item__buy .inputPrice").val();
        var qu = this.value;
        cost = cost.replace(/\D+/g,"");

     if(qu <= 0){
            $(".total__price span").html("0");
            $(".form__item__buy .inputQuant").val("0");
            this.value = 0;
            return;
        }else if(cost === "0"){
         $(".total__price span").html("	по запросу");
         $(".form__item__buy .inputQuant").val( this.value);
         return;
        }else if (cost === ""){
            $(".total__price span").html("по запросу");
            $(".form__item__buy .inputQuant").val(this.value);
            return;
        }

        var totalPrice = (parseInt(cost) * Number(qu) / 1000);

         totalPrice = totalPrice.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1\u202f');

        if (qu < 1){
            totalPrice = "0";
            this.value = 0;
        }

        $(".total__price span").html(totalPrice);
        $(".form__item__buy .inputQuant").val(qu);
    });

    $(document).on('submit',".form__item__buy",function(event){
        event.preventDefault();
       var table  = $(".form__item__buy .inputTable").val();
       var itemId = $(".form__item__buy .inputId").val();
       var qunt =  $(".form__item__buy .inputQuant").val();

        var table = "cart=1&nameTable="+table+"&itemId="+itemId+"&action=cartAdd&quant="+ qunt;

        $.ajax({
            url: "cartDb.php",
            type: "POST",
            data: table,
            success: function(response) {
                var light = JSON.parse(response);
                var table = $(".filter").attr('id');
                light.forEach(function (v) {

                    if (table === v.name_tb){
                        $("[data-id='"+v.item_id+"']").addClass('in-cart');
                        $.magnificPopup.close();
                        $(".cart-icon-box").addClass("cart-in-i");
                    }

                });

            },
            error: function (er) {
                console.log(er.responseText)
            }
        });

    });

    /*delete*/
    $(".cart__box-delete").on('click',function (event) {
        event.preventDefault();

        var itemId =  $(this).closest('tr').attr('data-cart-id');

        Swal.fire({
            title: 'Удалить?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Да',
            cancelButtonText: 'Нет!',
        }).then(function(result) {
            if (result.value) {

                var data = "cart=1&itemId="+itemId+"&action=cartDelete";

                $.ajax({
                    url: "cartDb.php",
                    type: "POST",
                    data: data,
                    success: function(response) {
                        location.reload();
                    },
                    error: function (er) {
                        console.log(er.responseText)
                    }
                });
            }
        })
    });

    /*refresh cart*/
    $(".cart__box__row__item-quantity").on('change',function (event) {

        $(".cart__refresh").css({'display': "inline-block"});
        $(this).closest('tr').attr('data-quantity', this.value);


        if (this.value < 0 || this.value === ""){
            this.value = 0;
            $(this).closest('tr').attr('data-quantity', 0);
        }

    });

    $(".cart__refresh").on('click',function(){
        var toRefresh = [];


        $(".cart__box__row tbody tr").each(function () {
           var quan =  $(this).attr('data-quantity');
           var idQuan =  $(this).attr('data-cart-id');
            toRefresh.push({'id' : idQuan, "quan" : quan});
        })

        var data = JSON.stringify(toRefresh)
        var data = "cart=1&action=cartRefresh&update="+data;

        $.ajax({
            url: "cartDb.php",
            type: "POST",
            data: data,
            success: function(response) {
                document.location.reload();
            },
            error: function (er) {
                console.log(er.responseText)
            }
        });
    })
});

var cartIsEmpry = "cart=1&action=cartIsEmpry";
    $.ajax({
        url: "cartDb.php",
        type: "POST",
        data: cartIsEmpry,
        success: function(response) {
            console.log(response)
          if (response === "yes"){
              $(".cart-icon-box").addClass("cart-in-i");
            }else if (response === "empty"){

            }
            //document.location.reload();
        },
        error: function (er) {
            console.log(er.responseText)
        }
});

