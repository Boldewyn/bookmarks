jQuery(function ($) {
  $('#tag-list li').click(function () {
    var tag = encodeURIComponent($.trim($(this).text())), l = window.location;
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
  $('.ui-button, button, input:submit, input[type="button"]').button()
    .filter('[type="submit"]').button('option', 'icons', {primary:'ui-icon-check'})
    .end()
    .filter('.cancel').button('option', 'icons', {primary:'ui-icon-closethick'})
    .end();
  $('.search-form [type="submit"]').button('option', {
    icons: {primary:'ui-icon-search'},
    text: false});
  var tagfields = $('#save_form input[name="tags"], #change_form input[name="tags"]');
  tagfields.autocomplete({
    minLength: 2,
    source: function( request, response ) {
      $.getJSON(Bookmarks.url + "alltags", {
        term: request.term.split(/\s+/).pop()
      }, response );
    },
    focus: function() {
      return false;
    },
    select: function( event, ui ) {
      var terms = this.value.split(/\s+/);
      terms.pop();
      terms.push(ui.item.value);
      terms.push("");
      this.value = terms.join(" ");
      return false;
    }
  });
  tagfields.live('xkeypress', function(e) {
    var $this = $(this).attr('autocomplete', 'off');
    if ($this.val().length > 0 && e.which > 32/*ASCII SP*/) {
      var val = $this.val()+String.fromCharCode(e.which);
      if ($this.data('req')) {
        $this.data('req').abort();
      }
      if ($this.data('complist')) {
        $this.data('complist').slideUp();
      } else {
        $this.data('complist', $('<ul class="complist"></ul>').css({
          position: 'absolute',
          top: $this.offset().top + $this.outerHeight(),
          left: $this.offset().left
        }).appendTo($('body')).slideUp());
      }
      $this.data('req',
        $.ajax({
          url: Bookmarks.url+'alltags/'+encodeURIComponent(val),
          success: function(data) {
            var i = 0, cl = $this.data('complist').empty();
            for (i; i < data.length; i++) {
              cl.append($('<li></li>').text(data[i].tag));
            }
            cl.find('li').bind('click', function() {
              var tag = $(this).text();
              $this.val(function(i, v) {
                var curtags = v.split(/\s+/), last = curtags.pop();
                if (last && tag.indexOf(last) === 0) {
                  return $.trim(curtags.join(" ") + " " + tag) + " ";
                } else {
                  return $.trim(v + " " + tag) + " ";
                }
              }).focus().data('complist').slideUp();
            }).end().slideDown();
          },
          dataType: 'json'
        }));
    }
  });
});
