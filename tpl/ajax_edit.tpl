<script type="text/javascript">
		$(function(){
					$('.btn-form').click(function(){
					if($('.form-legend textarea').val().trim()!=''&& $('.form-legend .b input').val().trim()!=''){
						$.ajax({
						type: "POST",
						url: "{_PATH}admin", 
						data: { text:$('.form-legend textarea').val(), lid:$('.form-legend .h input').val(),title:$('.form-legend .b input').val(),state: $('.form-legend .row-link .active').index()+1}
				}).done(function( msg ) {
					window.location.reload();
				}).error(function(msg){
					alert(msg.statusText);
				})
					}
				})
		})
		
	</script>
