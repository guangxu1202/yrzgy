$(function(){
    //获取左侧菜单定位
    var pnArr = window.location.pathname.split("/");

    var pnArrNumber = $.inArray("house", pnArr) > -1 ? $.inArray("house", pnArr) :  $.inArray("House", pnArr);
    $('.dropdown-toggle').each(function () {
        if ($(this).attr("data-pn") == pnArr[pnArrNumber+1]){
            $(this).click();
            $(".nav-list li").eq(0).removeClass("active");
           $(this).parent().find("li").each(function(){
               //判断子菜单定位
                if (!!$(this).attr("data-pn") && $(this).attr("data-pn").indexOf("|"+pnArr[pnArrNumber+2]+"|") >= 0){
                    $(this).addClass("active");
                }
            });
        }
    });
    //****

});