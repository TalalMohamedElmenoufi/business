<?php
include('../../includes/connect.php');

list($creditoSms) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_msg from usuarios where id = '".$_SESSION[id_usuario]."' "));

?>



              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Basic form elements</h4>
                    <p class="card-description"> Basic form elements </p>
                    <form class="forms-sample">
						
                      <div class="row">
                        <div class="col-md-4">
                          <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password">
                        </div>

						<div class="col-md-4">
                          <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password">
                        </div>
						  
						<div class="col-md-4">
							 <select id="descricao" name="descricao[]" multiple="multiple" class="form-control">
									<optgroup label="Todos">
									<?php
										$Tipos = explode(',',$d->descricao);
										$queryc = "select * from categoria_cadastro order by descricao";
										$resultc = mysqli_query($conexao2,$queryc);
										while($c = mysqli_fetch_object($resultc)){
										$descricao = ($c->descricao);	
									?>
									<option value="<?=$c->id?>" <?=((in_array($c->id,$Tipos))?'selected':false)?> ><?=$descricao?></option>
									<?php
										}
									?> 
									</optgroup>
									<option data-role="divider" ></option>
							</select> 
                        </div>						  
						  
						  
						  
						<div class="col-md-4">
							<div id="dataSms">
								<input type="hidden" name="data_sms" id="data_sms">
							</div>  
						</div>  
						  
						  
					  </div>
						

                    </form>
					  
					  
					  
 
					  
                  </div>
                </div>
              </div>		
				  
				  
				  
				  
<script language="javascript">

	$('#descricao').multiselect({
	maxHeight: 430,
	enableFiltering: true,

	enableClickableOptGroups: true,
	enableCollapsibleOptGroups: true,
	//includeSelectAllOption: true,
	buttonText: function(options, select) {
		if (options.length === 0) {

			var grupo = $('#descricao').val();
			var mensagem = $('#sua_mensagem').val();

			$.ajax({
			type: "POST",
			url: "./tabelas/whatsapp/smg_disponivel.php",
			data: {grupos: grupo},
				success: function (dados){
					$("#smsDisponivel").html(dados);
				}
			});							

			if(grupo && mensagem){
				$("#Enviar").prop('disabled', false);
			}else{
				$("#Enviar").prop('disabled', true);
			} 
			return 'Selecione pelo menos um grupo';

		}
		else if (options.length > 2) {

			var grupo = $('#descricao').val();
			var mensagem = $('#sua_mensagem').val();

			$.ajax({
			type: "POST",
			url: "./tabelas/whatsapp/smg_disponivel.php",
			data: {grupos: grupo},
				success: function (dados){
					$("#smsDisponivel").html(dados);
				}
			});						

			if(grupo && mensagem){
				$("#Enviar").prop('disabled', false);
			}else{
				$("#Enviar").prop('disabled', true);
			} 
			return options.length+' selecionados!';

		}
		 else {
			 var labels = [];
			 options.each(function() {
				 if ($(this).attr('label') !== undefined) {
					 labels.push($(this).attr('label'));

				 }
				 else {
					 labels.push($(this).html());


				 }
			 });
			 return labels.join(', ');


		 }

	}
	});
	
	
	$('#dataSms').datetimepicker({
		inline: true,
		sideBySide: true, 
		useCurrent: true,
		locale: 'pt-br',
		minDate: new Date(),
		widgetPositioning: {
		horizontal: 'auto',
		vertical: 'bottom'
		},
		icons: {
		date: 'far fa-calendar-alt',
		up: 'far fa-arrow-alt-circle-up',
		down: 'far fa-arrow-alt-circle-down',
		previous: 'far fa-arrow-alt-circle-left',
		next: 'far fa-arrow-alt-circle-right',
		today: 'fas fa-calendar-week',
		clear: 'fas fa-recycle'
		}
	});	
	
</script>