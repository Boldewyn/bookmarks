;(function($, window) {
var $win = $(window);

function _(s) {
  if (s in Bookmarks.i18n.catalog) {
    return Bookmarks.i18n.catalog[s];
  }
  return s;
};

/**
 * fetch a remote form via AJAX and open it in a dialog
 */
function get_and_submit_form(title, url, id, onsubmit) {
  var dialog = $('<div></div>').appendTo('body').dialog({
    title: title,
    modal: true,
    width: Math.min(700, $win.width()-40)
  });
  $.get(url, function(data) {
    var form = $(data).find('#'+id).editform().appendTo(dialog);
    dialog.dialog("close").dialog("open");
    form.buttonize();
    form.find('a.cancel').click(function() {
      dialog.dialog("destroy").remove();
      return false;
    });
    form.submit(function() {
      var method = form.attr('method').toLowerCase() || 'get';
      $[method](
        form.attr('action'),
        form.serialize()+"&ajax=1", function(data) {
          if (data === 'true') {
            if ($.isFunction(onsubmit)) {
              onsubmit(form, data);
            }
            dialog.dialog("destroy").remove();
          } else {
            alert("Error!");
          }
        }
      );
      return false;
    });
  });
};

/**
 * build a list of tag links from a tag array
 */
function build_tag_list(tags) {
  var i = 0, h = '';
  if (tags.length === 0) {
    return h;
  }
  if (! $.isArray(tags)) {
    tags = $.trim(tags).split(/\s+/);
  }
  for (; i < tags.length; i += 1) {
    h += '<a rel="tag" href="' + Bookmarks.script_path + 'tags/' +
         encodeURIComponent(tags[i]) + '">' + tags[i] + '</a> ';
  }
  return h;
};

/**
 * enhance all buttons contained in the selection
 */
$.fn.buttonize = function() {
  $('.ui-button, button, input:submit, input[type="button"]', this).button()
    .filter('[type="submit"]').button('option', 'icons', {primary:'ui-icon-check'})
    .end()
    .filter('.cancel').button('option', 'icons', {primary:'ui-icon-closethick'})
    .end();
  $('.search-form [type="submit"]', this).button('option', {
    icons: {primary:'ui-icon-search'},
    text: false});
  return this;
};

/**
 * add enhancements to bookmark edit forms
 */
$.fn.editform = function() {
  $('input[name="tags"]', this).autocomplete({
    minLength: 2,
    source: function(request, response) {
      $.getJSON(Bookmarks.url + "alltags", {
        term: request.term.split(/\s+/).pop()
      }, response);
    },
    focus: function() {
      return false;
    },
    select: function(event, ui) {
      var terms = this.value.split(/\s+/);
      terms.pop();
      terms.push(ui.item.value);
      terms.push("");
      this.value = terms.join(" ");
      return false;
    }
  });
  return this;
};

$.fn.bookmarklist = function() {
  var dels = $('.functions .delete', this);
  if (dels.length) {
    dels.click(function() {
      var $this = $(this);
      get_and_submit_form(_('Please confirm:'), this.href, 'delete_form',
        function(form, data) {
        $this.closest('li').animate({ backgroundColor: '#f02', height: 0 }, {
          duration: 900,
          complete: function() { $(this).remove(); }
        });
      });
      return false;
    });
  }
  var eds = $('.functions .edit', this);
  if (eds.length) {
    eds.click(function() {
      var $this = $(this);
      get_and_submit_form(_('Edit Bookmark:'), this.href, 'save_form',
        function(form, data) {
          var li = $this.closest('li').animate({opacity:0}, 300)
                                      .animate({opacity:1}, 300);
          window.setTimeout(function() {
            li.find('a.url').text(form.find('[name="title"]').val()).end()
              .find('.tags').empty().html(
                build_tag_list(form.find('[name="tags"]').val())
              ).end();
          }, 300);
      });
      return false;
    });
  }
  var shares = $('.functions .share', this);
  if (shares.length) {
    shares.click(function() {
      var $this = $(this);
      if ($this.closest('li').data('private') == '1') {
        var buttons = {};
        buttons[_("Share")] = function() {
          get_and_submit_form(_('Share Bookmark:'), $this[0].href,
                              'share_form');
          $(this).dialog("close");
        };
        buttons[_("Cancel")] = function() {
          $(this).dialog("close");
        };
        var dialog = $('<div><p>' +
          _('This is a private bookmark. Do you really want to share it?') +
          '</p></div>').appendTo('body').dialog({
            title: _('Private Bookmark'),
            modal: true,
            width: Math.min(700, $win.width()-40),
            buttons: buttons
        });
      } else {
        get_and_submit_form(_('Share Bookmark:'), this.href, 'share_form');
      }
      return false;
    });
  }
  return this;
};

$(function() {
  $('*[autofocus]').focus();
  $(document).buttonize();
  $('#save_form, #change_form').editform();
  $('nav a[href$="/save"]').click(function() {
    get_and_submit_form(_('New Bookmark:'), this.href, 'save_form',
      function(form, data) {
      var i = 0, l = $('#bookmarks'), d = {}, d2 = form.serializeArray();
      for (; i < d2.length; i++) {
        d[d2[i].name] = d2[i].value;
      }
      if (l.length) {
        if (! l.prev('ol.paginate').length ||
            l.prev('ol.paginate').find('li:eq(0)').hasClass('active')) {
          var r = $('<li></li>');
          if (d.private == '1') {
            r.attr('data-private', '1');
          }
          r.append($('<a class="url" rel="external"></a>')
                    .attr('href', d.url).text(d.title));
          r.append($('<span class="tags"></span>')
                    .html(build_tag_list(d.tags)));
          r.append($('<span class="functions">' +
            '<a class="edit" href="' + Bookmarks.script_path +
              'save?edit=1&amp;url=' + encodeURIComponent(d.url) + '">' +
              _('edit') + '</a>' +
            '<a class="delete" href="' + Bookmarks.script_path +
              'delete?url=' + encodeURIComponent(d.url) + '">' +
              _('delete') + '</a>' +
            '<a class="share" href="' + Bookmarks.script_path +
              'share?url=' + encodeURIComponent(d.url) + '">' +
              _('share') + '</a>' +
            '</span>'));
          r.bookmarklist().hide().prependTo(l).slideDown();
        }
      }
    });
    return false;
  });
  if (Bookmarks.new_window) {
    $('[rel="external"]').live('click', function() {
      window.open(this.href, '_blank');
      return false;
    });
  }
  var $bl = $('#bookmarks');
  if ($bl.length) {
    $bl.bookmarklist();
  }
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
  var taglist = $('#tag-list');
  if (taglist.length) {
    $('<li><input type="text" placeholder="' + _('type a tag') +
        '" name="tags" class="nexttag" /></li>').appendTo(taglist).editform()
      .find('.nexttag').keypress(function(e) {
      if (e.which === 13) {
        if (location.href.substring(0, Bookmarks.url.length + 5) === Bookmarks.url+'tags/') {
          location.href += '+' + this.value;
        } else {
          location.href = Bookmarks.url + 'tags/' + this.value;
        }
        return false;
      }
    });
  }
});

})(jQuery, this);
