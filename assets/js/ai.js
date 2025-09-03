(function ($, window, document) {
  'use strict';

  // Namespace for AIintegration utilities
  const AIintegration = {};


  // AJAX Request Function
  AIintegration.makeAjaxRequest = function (action,data, callback, el = '') {
    data = data || {};
    data._wpnonce = window.admin?.nonce  || '';
    data.action = action;
    $.ajax({
      type: 'POST',
      url: window.admin?.ajaxurl,
      dataType: 'json',
      data: data,
      success: function (response) {
        if (typeof callback === 'function') {
          callback(response);
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX Error:', error, xhr.status, xhr.responseText);
      },
      xhr: function () {
        const xhr = new window.XMLHttpRequest();
        xhr.addEventListener('progress', function (evt) {
          if (evt.lengthComputable && el) {
            const percentComplete = (evt.loaded / evt.total) * 100;
            $(el).animate({ width: percentComplete + '%' }, 100);
          }
        }, false);
        return xhr;
      }
    });
  };

  // Trigger Generating content by Ai
  $('#get_text').on('click',function(){
     var content = $('#content').val();
     var el = $(this);
     el.after('<div class="loading">Loading  .... </div>')
    AIintegration.makeAjaxRequest('generate_text_by_ai',{content:content},function(response){
      $('#generated_text').val(response.data.text);
      $('.loading').remove();
      el.after('<div class="success">'+response.data.message+'</div>')
    });

  })

})(jQuery, window, document);