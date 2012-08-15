<script type="text/javascript">
		$(function(){
					$('.btn-form').click(function(){
					if($('.form-legend .checkboxAreaChecked').size()!=0 && $('.form-legend textarea').val().trim()!=''&& $('.form-legend .b input').val().trim()!=''){
						$.ajax({
						type: "POST",
						url: "{_PATH}add", 
						data: { text:$('.form-legend textarea').val(), title:$('.form-legend .b input').val(),state: $('.form-legend .row-link .active').index()+1}
				}).done(function( msg ) {
					$('.popup-holder.add-legend').fadeOut(300);
					$('.popup-holder').eq(0).fadeIn(300,function(){
						$('.form-legend .checkboxAreaChecked').removeClass('checkboxAreaChecked').addClass('checkboxArea');
                                                //dump(msg);
					});
				}).error(function(msg){
					alert(msg.statusText);
				})
					}
				})
		})
		
	</script>
