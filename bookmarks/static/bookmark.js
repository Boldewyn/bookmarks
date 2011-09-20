//;(function($, window) {
var $win = $(window);
function _(s) {
  return s;
};
function buttonize(context) {
  context = context || document;
  $('.ui-button, button, input:submit, input[type="button"]', context).button()
    .filter('[type="submit"]').button('option', 'icons', {primary:'ui-icon-check'})
    .end()
    .filter('.cancel').button('option', 'icons', {primary:'ui-icon-closethick'})
    .end();
  $('.search-form [type="submit"]', context).button('option', {
    icons: {primary:'ui-icon-search'},
    text: false});
  return context;
};
jQuery(function ($) {
  $('*[autofocus]').focus();
  buttonize();
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
  var $ts = $('#tagselection');
  if ($ts.length) {
    $btnset = $('<div></div>');
    $ts.find('section').hide()
      .first().show().end()
      .find('h2').hide().each(function(i) {
      var $this = $(this),
          $btn = $('<button type="button"></button>')
                   .text($this.text()).click(function() {
                     $btnset.find('button').removeClass('ui-state-active');
                     $(this).addClass('ui-state-active');
                     $ts.find('section:visible').hide();
                     $this.closest('section').show();
                   });
      if (i === 0) {
        $btn.addClass('ui-state-active');
      }
      $btnset.append($btn);
    });
    $btnset.buttonset().prependTo($ts);
  }
  var dels = $('.functions .delete');
  if (dels.length) {
    dels.click(function() {
      var $this = $(this), url = this.href,
          dialog = $('<div></div>').appendTo('body')
                    .dialog({
                      title: _('Please confirm:'),
                      modal: true,
                      width: Math.min(700, $win.width()-40)
                    });
      $.get(url, function(data) {
        var form = $(data).find('#delete_form').appendTo(dialog);
        buttonize(form);
        form.find('a.cancel').click(function() {
          dialog.dialog("destroy").remove();
        });
        form.find('input[name="url"]').attr('type', 'text').closest('p').height(0);
        form.submit(function() {
          $[form.attr('method').toLowerCase()](
            form.attr('action'),
            form.serialize()+"&ajax=1&url="+encodeURIComponent(form.find('[name="url"]').val()), function(data) {
              if (data === 'true') {
                $this.closest('li').animate({'background':'white'}, 200)
                     .slideUp().remove();
                dialog.dialog("destroy").remove();
              } else {
                alert("Error!");
              }
            }
          );
          return false;
        });
      });
      return false;
    });
  }
  var eds = $('.functions .edit');
  if (eds.length) {
    eds.click(function() {
      var $this = $(this), url = this.href,
          dialog = $('<div></div>').appendTo('body')
                    .dialog({
                      title: _('Edit Bookmark:'),
                      modal: true,
                      width: Math.min(700, $win.width()-40)
                    });
      $.get(url, function(data) {
        var form = $(data).find('#save_form').appendTo(dialog);
        buttonize(form);
        form.find('a.cancel').click(function() {
          dialog.dialog("destroy").remove();
        });
        form.submit(function() {
          $[form.attr('method').toLowerCase()](
            form.attr('action'),
            form.serialize()+"&ajax=1&url="+encodeURIComponent(form.find('[name="url"]').val()), function(data) {
              if (data === 'true') {
                $this.closest('li').animate({opacity:0}, 300).animate({opacity:1}, 300)
                  .find('a.url').delay(300).show(function() { $(this).text(form.find('[name="title"]').val()); })
                  .end();
                dialog.dialog("destroy").remove();
              } else {
                alert("Error!");
              }
            }
          );
          return false;
        });
      });
      return false;
    });
  }
});
//})(jQuery, this);
