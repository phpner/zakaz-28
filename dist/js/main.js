$(document).ready(function($){

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
            ajaxParametr(item,diameter, false);
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

    /*  console.log(dataJson);*/
    function ajaxParametr(item,diameter){

        diameter = typeof diameter !== 'undefined' ?  diameter : "";

        var tableName = $(".filter").attr('id');
        dataJson =  JSON.stringify({"item":item ,"diam": diameter, "tableFilter" :tableName, "diamParameter": true});

        $.ajax({
            url: "/pdo.php",
            type: "JSON",
            data: dataJson,
            success: function(response) {
                var arrData = [];
                var str = '';
                var dataTo = "<div class='filderAjax__col'></div>";
                makeView(response,false, true);
            },
            error: function (er) {
                console.log(er.responseText)
            }
        });
    }
    /*filter by parameter*/
    function ajaxItem(item, all){
        diameter = typeof diameter !== 'undefined' ?  diameter : "";
        var tableName = $(".filter").attr('id');
        dataJson =  JSON.stringify({'item': item, "diam": diameter, "tableFilter" :tableName, "diamParameter": false});
        $.ajax({
            url: "/pdo.php",
            type: "JSON",
            data: dataJson,
            success: function(response) {

                makeView(response,false, false, all);

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
            url: "/pdo.php",
            type: "POST",
            data: table,
            success: function(response) {
                $(".filter__box__diam .filter__col__left").html('');
                $(".filter__box__name .filter__col__left").html('');

                $(".filter__box__name .filter__col__left").append('<div class="filter__col title input_active">\n' +
                    '           <label>\n' +
                    '                <input checked type="checkbox" value="all">\n' +
                    '                     <span></span>\n' +
                    '                     <p>Все марки</p>\n' +
                    '           </label>\n' +
                    '</div>');


                makeView(response,true,false,true);

            },
            error: function (er) {
                console.log(er.responseText)
            }
        });

    }

    /*append response*/
    function makeView(response,noFirst,is_param, all) {
        $(".filter-body__center__innder").html('');
        /*Проверка на пустоту))*/
        if (!is_param) {
            $(".filter__box__diam .filter__col__left").html('');
      /*      if (all){
                $(".filter__box__diam .filter__col__left").append('<div class="filter__col title ">\n' +
                    '           <label>\n' +
                    '                <input type="checkbox" value="all">\n' +
                    '                     <span></span>\n' +
                    '                     <p>Все</p>\n' +
                    '           </label>\n' +
                    '</div>');
            }*/
            }


        if (typeof response == 'undefined' || response == 'no' ||   response.length <= 0) {
            console.log('пусто!')
            $(".filter-body__center__innder").html("<span class='no__found'>Ничего не найдено!<span>");
            return;
        }

        var name ='';
        var sizeCh = "";

        console.log(response);


        for (var kyePP in response.param){

            var sizeTr = response.param[kyePP].size;

            if (sizeCh.indexOf(sizeTr) < 0){
                if (!is_param) {
                    $(".filter__box__diam .filter__col__left").append("<div class=\"filter__col alax_col\">\n" +
                        "    <label>\n" +
                        "             <input type=\"checkbox\" value=\"" + response.param[kyePP].id + "\">\n" +
                        "             <span></span>\n" +
                        "          <p>" + response.param[kyePP].size + "</p>\n" +
                        "    </label>\n" +
                        "</div>");
                }
            }

            sizeCh +=  sizeTr;
        }
        for (var kyeP in response.row){

            var size = response.row[kyeP].size;
            var aldSize = "";
            var cost = response.row[kyeP].cost;
            var id = response.row[kyeP].id;
            var innner  = $(".filter-body__center__innder");


            if ( name.indexOf(response.row[kyeP].name) < 0 ){
                str = id;

                var app = "<h3 id='header-"+id+"' class='filderAjax__header'><div class='wrapper'><i class='icon-square'></i> "+response.row[kyeP].name+"</div></h3>" +
                    "<div class='filderAjax__col'><div id='item-"+str+"'></div></div>";

               innner.append(app);

                /*Название*/
               innner.find("#item-"+str).append("<div id='"+response.row[kyeP].id+"' class='filderAjax__col__row'>" +
                    "<div class='wrapper'>" +
                    "<div class='filderAjax__col__item'>"+size+"</div>" +
                    " <div class='filderAjax__col__item'>"+cost+"</div>" +
                    " <div class='filderAjax__col__item'>"+rendomNum()+"</div>" +
                    " <div class='filderAjax__col__item'>"+rendomNum()+"</div>" +
                    "</div></div>");

                /*name*/
                if (noFirst) {

                    $(".filter__box__name .filter__col__left").append("<div class=\"filter__col alax_col input_active\">\n" +
                        "    <label>\n" +
                        "             <input checked type=\"checkbox\" value=\"" + response.row[kyeP].id + "\">\n" +
                        "             <span></span>\n" +
                        "          <p>" + response.row[kyeP].name + "</p>\n" +
                        "    </label>\n" +
                        "</div>");
                }

                name += response.row[kyeP].name;

            }else {
                /*Название*/
               innner.find("#item-"+str).append("<div id='"+response.row[kyeP].id+"' class='filderAjax__col__row'>" +
                    "<div class='wrapper'>" +
                    "<div class='filderAjax__col__item'>"+size+"</div>" +
                    " <div class='filderAjax__col__item'>"+cost+"</div>" +
                    " <div class='filderAjax__col__item'>"+rendomNum()+"</div>" +
                    " <div class='filderAjax__col__item'>"+rendomNum()+"</div>" +
                    "</div></div>");

            }
            aldSize = size;
        }

        if (arccorChack){
            arccor.accordion( "refresh" );
        }
    }

});