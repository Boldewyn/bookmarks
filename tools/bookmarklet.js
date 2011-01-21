javascript:
var t = document.title;
var h = document.location.href;
window.open



javascript:
(function(){
  f = 'http://www.manuel-strehl.de/bookmark/save?url='+encodeURIComponent(window.location.href)+
        '&title='+encodeURIComponent(document.title)+
        '&notes='+encodeURIComponent(''+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+
        '&v=6&';
  a = function(){
    if (! window.open(f+'noui=1&jump=doclose', 'deliciousuiv6', 'location=yes,links=no,scrollbars=no,toolbar=no,width=550,height=550')) {
      location.href = f+'jump=yes';
    }
  };
  if(/Firefox/.test(navigator.userAgent)) {
    setTimeout(a,0);
  } else {
    a();
  }
})()
