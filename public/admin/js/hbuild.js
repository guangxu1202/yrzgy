$(function(){
    //获取左侧菜单定位
    var pnArr = window.location.pathname.split("/");
    var pnArrNumber = $.inArray("Hbuild", pnArr) >0 ? $.inArray("Hbuild", pnArr) : $.inArray("hbuild", pnArr);
    $(".nav-list li").eq(0).removeClass("active");
    $(".nav-list li").each(function () {
        if ($(this).find(".dropdown-toggle").attr("data-pn") == pnArr[pnArrNumber+1]){
            $(this).addClass("active");
        }
    });

});