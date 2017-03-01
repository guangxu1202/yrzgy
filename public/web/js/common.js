
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement('style')
    msViewportStyle.appendChild(
        document.createTextNode(
            '@-ms-viewport{width:auto!important}'
        )
    )
    document.querySelector('head').appendChild(msViewportStyle)
}


addFav = function() {
    if(document.all) {
        window.external.addFavorite('http://www.yrzgy.com/', '元认知心理干预技术网 - 首页');
    } else if(window.sidebar) {
        window.sidebar.addPanel('元认知心理干预技术网 - 首页', 'http://www.yrzgy.com/', "");
    } else {
        alert("您的浏览器不支持[添加收藏夹]操作, 请手动收藏.");
    }
};

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