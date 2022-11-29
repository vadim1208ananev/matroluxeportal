$(document).ready(function () {
    $("a.topLink").click(function () {
        $("html, body").animate({scrollTop: $($(this).attr("href")).offset().top + "px"}, {duration: 500});
        return false;
    });
});
function setCookie(name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape(value) + ((expires) ? "; expires=" + expires : "") + ((path) ? "; path=" + path : "") + ((domain) ? "; domain=" + domain : "") + ((secure) ? "; secure" : "");
}
function getCookie(name) {
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;
    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset)
            if (end == -1) {
                end = cookie.length;
            }
            setStr = unescape(cookie.substring(offset, end));
        }
    }
    return (setStr);
}
function getsize() {
    document.getElementById('size_standart').checked = true;
    document.getElementById('pricemain').innerHTML = $("#selectsize").val();
    $("#pricemain").animate({opacity: "hide"}, 500);
    $("#pricemain").animate({opacity: "show"}, 500);
    document.getElementById('sizemain').innerHTML = $("#selectsize option:selected").text();
    $("#sizemain").animate({opacity: "hide"}, 500);
    $("#sizemain").animate({opacity: "show"}, 500);
}
function getsize2(size) {
    document.getElementById('size_indiv').checked = true;
    document.getElementById('pricemain').innerHTML = Math.ceil($("#size_input1").val() * $("#size_input2").val() / 10000 * size);
    document.getElementById('sizemain').innerHTML = document.getElementById('size_input1').value + ' x ' + document.getElementById('size_input2').value + ' см';
}
function kolplus() {
    document.getElementById('kolspan').innerHTML = document.getElementById('kolspan').innerHTML - 0 + 1;
    $("#kolspan").animate({opacity: "hide"}, 300);
    $("#kolspan").animate({opacity: "show"}, 300);
    $("#sumspan").animate({opacity: "hide"}, 300);
    $("#sumspan").animate({opacity: "show"}, 300);
    document.getElementById('sumspan').innerHTML = document.getElementById('pricemain').innerHTML * document.getElementById('kolspan').innerHTML;
    document.getElementById('kolinputform').value = document.getElementById('kolspan').innerHTML;
}
function kolminus() {
    if (document.getElementById('kolspan').innerHTML == 1) {
        return false
    }
    document.getElementById('kolspan').innerHTML = document.getElementById('kolspan').innerHTML - 0 - 1;
    $("#kolspan").animate({opacity: "hide"}, 300);
    $("#kolspan").animate({opacity: "show"}, 300);
    $("#sumspan").animate({opacity: "hide"}, 300);
    $("#sumspan").animate({opacity: "show"}, 300);
    document.getElementById('sumspan').innerHTML = document.getElementById('sumspan').innerHTML - 0 - document.getElementById('pricemain').innerHTML;
    document.getElementById('kolinputform').value = document.getElementById('kolspan').innerHTML;
}
function showorder() {
    window.scrollTo(0, 0);
    document.getElementById('phone').value = getCookie("phone");
    document.getElementById('phone1').value = getCookie("phone");
    document.getElementById('kolspan').innerHTML = 1;
    document.getElementById('sumspan').innerHTML = document.getElementById('pricemain').innerHTML;
    document.getElementById('sizespan').innerHTML = document.getElementById('sizemain').innerHTML;
    document.getElementById('sizeinputform').value = document.getElementById('sizemain').innerHTML;
    if (document.getElementById('phone').value != '') {
        document.getElementById('provinputform').value = '(дозаказ)'
    }
    ;
    yaCounter30365892.reachGoal('order');
}
function showblock() {
    $(".div_mob").toggle("fast");
}
var chehol1 = 1;
function addprice4exol() {
    if (chehol1 == 0) {
        document.getElementById('sumspan').innerHTML = parseInt(document.getElementById('sumspan').innerHTML) - 199;
        $("#sumspan").animate({opacity: "hide"}, 300);
        $("#sumspan").animate({opacity: "show"}, 300);
        document.getElementById('sizeinputform').value = document.getElementById('sizespan').innerHTML;
        chehol1 = 1;
    } else {
        document.getElementById('sumspan').innerHTML = parseInt(document.getElementById('sumspan').innerHTML) + 199;
        $("#sumspan").animate({opacity: "hide"}, 300);
        $("#sumspan").animate({opacity: "show"}, 300);
        document.getElementById('sizeinputform').value = document.getElementById('sizeinputform').value + ' (+ выбрал чехол)';
        chehol1 = 0;
    }
}
var podushka = 1;
function addpricepodushka() {
    if (podushka == 0) {
        document.getElementById('sumspan').innerHTML = parseInt(document.getElementById('sumspan').innerHTML) - 149;
        $("#sumspan").animate({opacity: "hide"}, 300);
        $("#sumspan").animate({opacity: "show"}, 300);
        document.getElementById('sizeinputform').value = document.getElementById('sizespan').innerHTML;
        podushka = 1;
    } else {
        document.getElementById('sumspan').innerHTML = parseInt(document.getElementById('sumspan').innerHTML) + 149;
        $("#sumspan").animate({opacity: "hide"}, 300);
        $("#sumspan").animate({opacity: "show"}, 300);
        document.getElementById('sizeinputform').value = document.getElementById('sizeinputform').value + ' (+ выбрал подушку)';
        podushka = 0;
    }
}
function proverka1() {
    if (document.getElementById('phone').value.length < 1) {
        $(".div_mob").animate({'margin-left': '-100'}, 200);
        $(".div_mob").animate({'margin-left': '10'}, 200);
        $(".div_mob").animate({'margin-left': '0'}, 200);
        return false;
    }
    $("#order_load").toggle("slow");
    setCookie("phone", document.getElementById('phone').value);
    document.getElementById('but_a_order').disabled = 1;
    setTimeout("func1()", 5000);
}
function func1() {
    {
        $("#signup").hide("fast");
        document.getElementById('idTab_href_ok').click();
        setTimeout("func2()", 2000);
        ga('send', 'event', 'button', 'ordersus');
        _paq.push(['trackEvent', 'all', 'ordersus']);
    }
}
function func2() {
    location.reload();
}
function airrew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({widget: 'Comment', id: 43256, url: 'http://matro-roll.ua/matras-topper-air-standart-31/',});
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function airlrew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({
        widget: 'Comment',
        id: 43256,
        url: 'https://matro-roll.ua/matras-topper-matroroll-air-lite-31/',
    });
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function comfprew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({widget: 'Comment', id: 43256, url: 'https://matro-roll.ua/matroroll-comfort-plus/',});
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function airwrew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({widget: 'Comment', id: 43256, url: 'https://matro-roll.ua/topper-matroroll-air-watermax/',});
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function dourew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({widget: 'Comment', id: 43256, url: 'http://matro-roll.ua/matras-topper-double-comfort-2/',});
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function extrarew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({widget: 'Comment', id: 43256, url: 'http://matro-roll.ua/matras-topper-domio-extra-kokos/',});
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function estadrew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({widget: 'Comment', id: 43256, url: 'http://matro-roll.ua/matras-topper-extra-standart/',});
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function mstadrew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({widget: 'Comment', id: 43256, url: 'https://matro-roll.ua/matroroll-memotex-standart/',});
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function memrew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({widget: 'Comment', id: 43256, url: 'http://matro-roll.ua/matras-topper-memotex/',});
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function memkrew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({widget: 'Comment', id: 43256, url: 'http://matro-roll.ua/matras-topper-memotex-kokos/',});
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function memarew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({widget: 'Comment', id: 43256, url: 'http://matro-roll.ua/matras-topper-memotex-advance/',});
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function ultrarew() {
    cackle_widget = window.cackle_widget || [];
    cackle_widget.push({widget: 'Comment', id: 43256, url: 'http://matro-roll.ua/matras-topper-ultra-flex/',});
    (function () {
        var mc = document.createElement('script');
        mc.type = 'text/javascript';
        mc.async = true;
        mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(mc, s.nextSibling);
    })();
}
function phoneone() {
    document.getElementById('phone').value = document.getElementById('phone1').value;
}
function phoneone1() {
    document.getElementById('phone1').value = document.getElementById('phone').value;
}
var colors = "#FCDBDB";
function selectback() {
    document.getElementById('selectsize').style.background = colors;
    setTimeout("funcselect()", 1000);
}
function funcselect() {
    {
        document.getElementById('selectsize').style.background = '#fff';
        setTimeout("selectback()", 5000);
    }
    $(document).ready(function () {
        $("#selectsize").click(function () {
            colors = "#fff";
            document.getElementById('selectsize').style.background = '#fff';
            document.getElementById('size_standart').checked = true;
        });
        $("#div_but_ind").click(function () {
            colors = "#fff";
            document.getElementById('selectsize').style.background = '#fff';
            document.getElementById('size_standart').checked = true;
        });
    });
}
</
script >
< script
type = "text/javascript" > jQuery(function ($) {
        $("#phone").mask("+38 (999) 999-9999");
        $("#phone1").mask("+38 (999) 999-9999");
    });