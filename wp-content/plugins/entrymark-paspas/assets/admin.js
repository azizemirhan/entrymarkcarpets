(function($){
  'use strict';

  var frame;
  $(document).on('click', '.emc-upload-texture', function(e){
    e.preventDefault();
    if (frame) { frame.open(); return; }
    frame = wp.media({
      title: 'Yüzey dokusu görseli seç',
      library: { type: 'image' },
      multiple: false,
      button: { text: 'Kullan' }
    });
    frame.on('select', function(){
      var att = frame.state().get('selection').first().toJSON();
      $('#emc_texture_image_id').val(att.id);
      $('#emc_texture_preview').html('<img src="' + (att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url) + '" alt="">');
    });
    frame.open();
  });

  $(document).on('click', '.emc-edit-texture', function(e){
    e.preventDefault();
    var id = $(this).data('id'), name = $(this).data('name'), imageId = $(this).data('image-id');
    $('#emc_texture_id').val(id);
    $('#emc_texture_name').val(name);
    $('#emc_texture_image_id').val(imageId);
    if (imageId) {
      // Önizleme için attachment url (sayfa yüklüyse wp.media.attachment(id).fetch ile de alınabilir)
      var $img = $(this).closest('tr').find('img');
      $('#emc_texture_preview').html($img.length ? $img.clone() : '');
    } else {
      $('#emc_texture_preview').html('');
    }
  });
})(jQuery);
