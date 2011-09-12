jQuery(function ($) {
  $('#tag-list li').click(function () {
    var tag = $.trim($(this).text()), l = window.location;
    var re = new RegExp('(\\+?)'+tag.replace(/[.*+?|()\[\]{}\\]/g, '\\$1') + '\\+?');
    if (l.search.search(/[\?&]tags=/) > -1) {
      l.search = l.search.replace(re, '$1');
    } else {
      var slash = l.pathname.lastIndexOf('/');
      var pathname = l.pathname.substr(0, slash) +
               l.pathname.substr(slash).replace(re, '$1');
      pathname = pathname.replace(/\+$/, '');
      if (pathname.substr(pathname.length-6) === '/tags/') {
        pathname = pathname.substr(0, pathname.length-5);
      }
      l.pathname = pathname;
    }
    return false;
  });
  $('*[autofocus]').focus();
});
