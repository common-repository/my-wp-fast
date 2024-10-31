  jQuery(document).ready(function(){
    jQuery('.btn').click(function(){
      jQuery('.whitebox').hide();
      jQuery('.btn').removeClass('active');
      jQuery(this).addClass('active');
      jQuery('.' + jQuery(this).data('tab')).show();
    });
  });