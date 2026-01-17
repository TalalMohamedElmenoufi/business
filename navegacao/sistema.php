    <div class="container-scroller">

		
	  <?php include("./paginas/topo.php"); ?>
		
		
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
		  
		  
		<?php include("./paginas/menu.php"); ?>  

        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
			  
			  
            <div class="row" id="proBanner">
              <div class="col-12" style="margin-top:10px;">
				<?php include("./paginas/alertas.php"); ?>   
              </div>
            </div>
			  			  
			
			 <div id="CONTEUDOS" class="col-lg-12"></div> 
			 <div id="CARREGANDO"></div> 

			  

          </div>
			
			
			
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
			  
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2005 <a href="https://www.elmenoufi.com.br/" target="_blank">Elmenoufi</a>. Todos os direito reservados.</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">T M Elmenoufi Ltda  2005</span>
				
				

				
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>	


<script>
	 
setInterval(function(){

	$.ajax({
	url: './acoes/atualizar.php?sair=<?=$_GET[sair]?>',
	success: function(data) {
		$('#Atializando').html(data);
	}
	});

}, 500);
	
	
$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	
$.ajax({
url: "./paginas/pagina_inicial.php",
success: function(data) {
	$("#CONTEUDOS").html(data);
	$("#CARREGANDO").html('');
}
});		

</script>