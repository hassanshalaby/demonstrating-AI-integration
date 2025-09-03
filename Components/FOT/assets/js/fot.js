var $ = jQuery;

$(function(){

	var custom_uploader;

		$('body').on('click', '.upload--file', function(e){

		var field = $(this).data('field');

		var name = $(this).data('name');

		var datamultiple = $(this).data('multiple');

		e.preventDefault();

     		var button = $(this),

    		    custom_uploader = wp.media({

			title: 'upload image',

			library : {

				type : $(this).data('type')

			},

			button: {

				text: 'upload file'

			},

			multiple: $(this).data('multiple')

		}).on('select', function() {

			if( datamultiple == true ) {

				var attachments = custom_uploader.state().get('selection'),

				    attachment_ids = new Array(),

				    i = 0;

				attachments.each(function(attachment) {

	 				attachment_ids[i] = attachment['id'];

	 				var attachment = attachment.toJSON();

	 				if (attachment.url.indexOf("pdf") > 0) {

	 					var fileName = attachment.url.split('/').pop();

						$(field+'_preview').append('<span><input type="hidden" name="'+name+'['+attachment.id+']" value="'+attachment.url+'" /><em onClick="this.parent().remove();"><span></span><span></span></em><div class="sm-preveiew"><i class="fa-solid fa-file-pdf"></i><span>'+fileName+'</span></div></span>');

	 				}else if (attachment.url.indexOf("docx") > 0){



						var fileName = attachment.url.split('/').pop();

						$(field+'_preview').append('<span><input type="hidden" name="'+name+'['+attachment.id+']" value="'+attachment.url+'" /><em onClick="this.parent().remove();"><span></span><span></span></em><div class="sm-preveiew"><i class="fa-solid fa-book"></i><span>'+fileName+'</span></div></span>');

	 				}else if(attachment.url.indexOf("ppt") > 0){



						var fileName = attachment.url.split('/').pop();

						$(field+'_preview').append('<span><input type="hidden" name="'+name+'['+attachment.id+']" value="'+attachment.url+'" /><em onClick="this.parent().remove();"><span></span><span></span></em><div class="sm-preveiew"><i class="fa-solid fa-presentation-screen"></i><span>'+fileName+'</span></div></span>');

	 				}else{



					$(field+'_preview').append('<span><input type="hidden" name="'+name+'['+attachment.id+']" value="'+attachment.url+'" /><em onClick="this.parent().remove();"><span></span><span></span></em><img src="'+attachment.url+'" /></span>');

	 				}

					i++;

				});

			}else {

				var attachment = custom_uploader.state().get('selection').first().toJSON();

				$(field+'_id').val(attachment.id);

				$(field).val(attachment.url);



					if (attachment.url.indexOf("pdf") > 0) {

 						var fileName = attachment.url.split('/').pop();

						$(field+'_preview').after(`

							<div class="APBPreviewFile preview-file" id="${field}'_preview'">

								<i class="fa-solid fa-file-pdf"></i>

								<span>${fileName}</span>

							</div>';

						`);

						$(field+'_preview').remove()

	 				}else if (attachment.url.indexOf("docx") > 0){

						var fileName = attachment.url.split('/').pop();

						$(field+'_preview').after(`

							<div class="APBPreviewFile preview-file" id="${field}'_preview'">

								<i class="fa-solid fa-book"></i>

								<span>${fileName}</span>

							</div>';

						`);

						$(field+'_preview').remove()

	 				}else if(attachment.url.indexOf("ppt") > 0){



						var fileName = attachment.url.split('/').pop();

						$(field+'_preview').after(`

							<div class="APBPreviewFile preview-file" id="${field}'_preview'">

								<i class="fa-solid fa-presentation-screen"></i>

								<span>${fileName}</span>

							</div>';

						`);

						$(field+'_preview').remove()

	 				}else{



						if ($(field+'_preview').length) {

							$(field+'_preview').attr('src', attachment.url).show();

						}else {
                            if($(field+'_id').siblings('.preview-image') .length){
							$(field+'_id').siblings('.preview-image').append(`

								<img class="" id="${field}_preview" src="${attachment.url}" />

								<a  href="javascript:void(0);" class="remove" data-multiple="false" id="${field}_remove"><i class="fal fa-times"></i></a>

								`)	
                            }else {
                                $(field).after(`
                                <div class="preview-image">
    								<img class="" id="${field}_preview" src="${attachment.url}" />

    								<a  href="javascript:void(0);" class="remove" data-multiple="false" id="${field}_remove"><i class="fal fa-times"></i></a>
                                </div>
								`)
                            }

						}

	 				}





				$(field+'_remove').show();

			}

		})

		.open();

	});

 

	$('body').on('click', '.remove', function(){

		if( $(this).data('multiple') == false ) {

			$(this).closest('.field--content').find('input').val('');

			$(this).parent().html('');

		}else {

			$(this).prev().html('');

			$(this).remove();

		}

		return false;

	});

/*******************************************************
 * *******************************************************/

    
    $(document).on('click', '.add-more', function() {
      var $box = $(this).prev('.group-holder');
      var num = $(this).closest('.field--content').find('.group-holder').length;
      var $clone = $box.clone();
      $clone.find('[name]').val('');
      $clone.find('[name]').each(function(){
          $(this).attr('name',$(this).attr('name') + '_' + num);
          if( $(this).attr('id') ){
            $(this).attr('id',$(this).attr('id')+'_'+num)
          }
      });
      
      if( $clone.find('[type="hidden"]').length ){
          $clone.find('[type="hidden"]').each(function(){
           var id = $(this).attr('id')
                id = id.replace('_id','_'+num+'_id');
                $(this).attr('id',id)
          })
      }
      if($clone.find('.toggle').length){
          $clone.find('.toggle').removeClass('active')
      }
      
      if( $clone.find('[data-name]').length ){
          $clone.find('[data-name]').each(function(){
              $(this).attr('data-name',$(this).attr('data-name') + '_' + num)
              $(this).attr('data-field',$(this).attr('data-field') + '_' + num)
          })
      }
      
      $clone.find('.preview-image').remove();
      $clone.append('<i class="fa-solid fa-xmark remove-box"></i>');
      $box.after($clone);
    });
    
    $(document).on('click', '.remove-box', function() {
      $(this).closest('.group-holder').remove();
    });
    
     $(document).on('change','.group-holder [name]',function(){
        var el = $(this).closest('.field--content')
    });
    /*******************************************************
 * *******************************************************/

    
    
	$('#save_fields').click(function(){

		$('success').remove();

		$('container').css({opacity:'.5',pointerEvents:'none'})


      if($('.type-group').length){
            $('.type-group').each(function(){
                var arr = [];
                var el = $(this);
                saveGroupData(el)
                
            })
        }

		var fields = {};

		$('.opt-field').each(function(){
            if($(this).hasClass('wp-editor-area')){
                fields[ $(this).attr('name') ] = tinymce.activeEditor.getContent()
            }
            else {
               fields[ $(this).attr('name') ] = $(this).val() 
            }
			

		})
    
  
    
			$.ajax({
			    type: 'POST',
			    url: fotAjax.ajaxurl, // Use the admin-ajax URL from wp_localize_script
			    data: {
			        action: 'save_options',
			        fields: fields,
			        dataType: 'json',
			        _ajax_nonce: fotAjax.nonce // Include the nonce for security
			    },
			    success: function(response) {
			        $('container').css({opacity: '1', pointerEvents: 'all'}); // Fixed selector syntax
			        $('container').before('<success>'+response.data.message+'</success>');
			    }
			});

	})


  function saveGroupData(el){
		var allData = {};
		var i = 0;
		el.find('.group-holder').each(function(){
		  	var boxData = {};
	    	var arr = {};

		  	$(this).find('[name]').each(function(){
			    var id = $(this).attr('name');
			    arr[id] = $(this).val()
		  	});

		  allData[i] = arr;
		  i++;
		});
        var allDataJson = JSON.stringify(allData);
        // var allDataJson = unescape(encodeURIComponent(allDataJson));
        // var allDataJson = btoa(allDataJson);
        el.find('.group--values').val(allDataJson).change();
        
    }


	$('tab').click(function(){

		$(this).addClass('active').siblings().removeClass('active');

		$('feild-control').hide();

		$(''+$(this).data('class')+'').css('display','block');

	})



	function customField(title,desc){

		return `

			<field-item class="flex-start align-center">

				

				<div class="flex-1">

					<h3>${title}</h3>

					<p>${desc}</p>

				</div>

				<i class="fa solid fa-xmark remove-field"></i>

			</field-item>

		`;

	}



	$('.add-post').click(function(){

		var title = $('.add-title').val();

		var desc = $('.add-desc').val();

	    $('fields-posts').append(customField(title,desc));

	    var allData = {};

	    $('field-item').each(function(){

			allData[$(this).find('h3').text()] = { 

			'title':$(this).find('h3').text(),

			'desc':$(this).find('p').text()

	      }

	    })

	 

	    $('[name="custom_fields"]').val(JSON.stringify(allData));

	    $('custom--posts *').val('')

	})



	$('body').on('click','.remove-field',function(){

	  $(this).parent().remove();

	    var allData = {};

	    $('field-item').each(function(){

			allData[$(this).find('h3').text()] = { 

				'title':$(this).find('h3').text(),

				'desc':$(this).find('p').text()

		      }

	    })

	    $('[name="custom_fields"]').val(JSON.stringify(allData));

	})

  if ($('fields-posts').length) {



    $( "fields-posts" ).sortable(

          {

          update: function( event, ui ) {

		    var allData = {};

		    $('field-item').each(function(){

				allData[$(this).find('h3').text()] = { 

					'title':$(this).find('h3').text(),

					'desc':$(this).find('p').text()

			      }

		    })

		    $('[name="custom_fields"]').val(JSON.stringify(allData));

           

          }

      }

);



  }

  $('body').on('click','.toggle',function(){
  	$(this).toggleClass('active');
  	if ($(this).hasClass('active')) {
  		$(this).prev().val('on').change();
  	}else {
  		$(this).prev().val('').change();
  	}
  })

})