$(document).ready( function(){
		 
	 $('#link1').click(function() {
		 
		 $.blockUI({
			message: '<img src="img/loading1.gif" height=100px width=100px/>', 
		  css: {
			backgroundColor: 'transparent',
			border: '0'
			}
		  });
		 
		$('#conteudo').load("list_usuario");
		$('.sidebar-menu li').each(function(i) {
			$(this).removeClass('active')
		});
		$(this).parents("li").addClass('active');
		
		$.unblockUI();
	 });
	 
	 $('#link2').click(function() {
		 
		 $.blockUI({
			message: '<img src="img/loading1.gif" height=100px width=100px/>', 
		  css: {
			backgroundColor: 'transparent',
			border: '0'
			}
		  });
		 
		$('#conteudo').load("list_solicitacao");
		$('.sidebar-menu li').each(function(i) {
			$(this).removeClass('active')
		});
		$(this).parents("li").addClass('active');
		
		$.unblockUI();
	 });
});