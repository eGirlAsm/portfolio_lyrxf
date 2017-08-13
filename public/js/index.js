/*
* author : 2010-12-27 11:33:02 teresa
* discription: add favorite
*/
function addFavorite(){
	alert( "您可以尝试通过快捷键  “Ctrl+D”加入到收藏夹" );
    if (document.all){
        try{
            window.external.addFavorite(window.location.href,document.title);
        }catch(e){
            alert( "加入收藏失败，请使用Ctrl+D进行添加" );
        }
        
    }else if (window.sidebar){
        window.sidebar.addPanel(document.title, window.location.href, "");
     }else{
        alert( "加入收藏失败，请使用Ctrl+D进行添加" );
    }
}


/*
* author : 2010-12-27 11:33:02 teresa
* discription: set homepage
*/
function setHomepage(){

    if (document.all){
        document.body.style.behavior='url(#default#homepage)';
          document.body.setHomePage(window.location.href);
    }else if (window.sidebar){
        if(window.netscape){
            try{
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            }catch (e){
                alert( "该操作被浏览器拒绝，如果想启用该功能，请在地址栏内输入 about:config,然后将项 signed.applets.codebase_principal_support 值该为true" );
            }
        }
        var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components. interfaces.nsIPrefBranch);
        prefs.setCharPref('browser.startup.homepage',window.location.href);
    }else{
        alert('您的浏览器不支持自动自动设置首页, 请使用浏览器菜单手动设置!');
    }
}


