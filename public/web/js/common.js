
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement('style')
    msViewportStyle.appendChild(
        document.createTextNode(
            '@-ms-viewport{width:auto!important}'
        )
    )
    document.querySelector('head').appendChild(msViewportStyle)
}


$(".addFav").click(function(e) {
    var href = this.href || document.location,
        title = this.title || document.title;
    try{
        if(window.sidebar){
            sidebar.addPanel(title, href, "");
        }else{
            external.addFavorite(href, title);
        }
    }catch(e){
        alert("浏览器不支持，请按Ctrl+D进行添加");
    }
    return false;
});


$(function(){
    $(window).resize(function(){
        $(".nav-green .navbar-brand").css("height","40px");
        $(".nav-green .navbar-brand").css("line-height","40px;");
        navBrand();
    })
});
navBrand();
function navBrand(){
    var h = $(".nav-green .container-fluid").height();
    $(".nav-green .navbar-brand").css("height",h+"px");
    $(".nav-green .navbar-brand").css("line-height",h+"px");
}



// service qq
Service = function(id, _top, _left) {
    var me = id.charAt ? document.getElementById(id) : id, d1 = document.body, d2 = document.documentElement;
    d1.style.height = d2.style.height = '100%';
    me.style.top = _top ? _top + 'px' : 0;
    me.style.left = _left + "px";// [(_left>0?'left':'left')]=_left?Math.abs(_left)+'px':0;
    me.style.position = 'absolute';
    setInterval(
        function() {
            me.style.top = parseInt(me.style.top)
                + (Math.max(d1.scrollTop, d2.scrollTop) + _top - parseInt(me.style.top))
                * 0.1 + 'px';
        }, 10 + parseInt(Math.random() * 20));
    return arguments.callee;
};


if ($("#xixi").length>0){
    
    window.onload = function() {
        Service('xixi', 100, -152)
    };

    lastScrollY = 0;
    var InterTime = 1;
    var maxWidth = -1;
    var minWidth = -152;
    var numInter = 8;

    var BigInter;
    var SmallInter;

    var o = document.getElementById("xixi");
    var i = parseInt(o.style.left);

    function Big() {
        if (parseInt(o.style.left) < maxWidth) {
            i = parseInt(o.style.left);
            i += numInter;
            o.style.left = i + "px";
            if (i == maxWidth)
                clearInterval(BigInter);
        }
    }

    function toBig() {
        clearInterval(SmallInter);
        clearInterval(BigInter);
        BigInter = setInterval(Big, InterTime);
    }

    function Small() {
        if (parseInt(o.style.left) > minWidth) {
            i = parseInt(o.style.left);
            i -= numInter;
            o.style.left = i + "px";

            if (i == minWidth)
                clearInterval(SmallInter);
        }
    }

    function toSmall() {
        clearInterval(SmallInter);
        clearInterval(BigInter);
        SmallInter = setInterval(Small, InterTime);

    }
}