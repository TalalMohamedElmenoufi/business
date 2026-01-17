<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/mailmarketing/mailmarketing';
$Script = md5($Conf[script]);

$query = "select * from mailmarketing where id = '".$_GET[cod]."'";
$result = mysqli_query($conexao2,$query);
$d = mysqli_fetch_object($result);


if($_POST){

	if(isset($_POST["alterar_".$Script])){

		$query = "update mailmarketing set nome='".($_POST[nome])."', email='".$_POST[email]."', assinatura='".($_POST[assinatura])."', mensagem='".($_POST[mensagem])."', situacao = '".$_POST[situacao]."' where id = '".$_POST[cod]."'";
		if(mysqli_query($conexao2,$query)){
			echo "<script>parent.retornar_$Script('".$_POST[cod]."')</script>";
			exit();
		}

	}
	
	if(isset($_POST["salvar_".$Script])){

		$query = "insert into mailmarketing set nome='".($_POST[nome])."', email='".$_POST[email]."', assinatura='".($_POST[assinatura])."', mensagem='".($_POST[mensagem])."', situacao = '".$_POST[situacao]."'";
		if(mysqli_query($conexao2,$query)){
				echo "<script>parent.retornar_$Script()</script>";
			exit();
		}
	}
	
	

}




?>

<style>
	button[incluir_marq]{

	}

</style>


<div class="card" >
<div class="card-body">
		
		
<div class="panel-heading">
	<?=($_SESSION[$Script][titulo])?>
</div>	


<form id="ValidarMailer" action="<?=$Conf[script]?>_form.php" method="post" target="pagina" enctype="multipart/form-data" >

	<div class="row" > 


                <div class=" col-md-12 col-lg-12" >
					<span class="TituloForms">Nome da Campanha</span><br>
					<i class="far fa-copyright Icons"></i>
                    <input InputForm type="text" class="form-control" nome name="nome" id="nome" placeholder="Nome da Campanha" value="<?=($d->nome)?>" >
                </div>
                <div class=" col-md-6 col-lg-6" >
					<span class="TituloForms">E-mail do Remetente</span><br>
					<i class="far fa-copyright Icons"></i>
                    <input InputForm type="text" class="form-control" email name="email" id="email" placeholder="E-mail do Remetente" value="<?=$d->email?>" >
                </div>
                <div class="col-md-6 col-lg-6" >
					<span class="TituloForms">Assinatura do Remetente</span><br>
					<i class="far fa-copyright Icons"></i>
                    <input InputForm type="text" class="form-control" assinatura name="assinatura" id="assinatura" placeholder="Assinatura do Remetente" value="<?=($d->assinatura)?>" >
                </div>



               <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:10px;" >


                    <div id="EditarGaleria" style="cursor:pointer">
                        <div><i style="font-size: 20px;" class="fa fa-search"></i> Inserir / Adicionar Imagens</div>
                    </div>


                    <div class="form-inline" >
                    <div class="form-group">
                        Incluir Campos: 
                        <!--<span incluir_nome>[nome]</span>-->
                    </div>
                    
                    
                        <?php
                            $cp_padrao = array( 'id',
                                                'usuario',
                                                'categoria',
                                                'situacao',
                                                'destination',
											    'cod_pais',
											    'cod_estado',
											    'telefone',
                                                'data_nascimento',
                                                'tel_tipo',
                                                'erro'
                                            );
                    
                            $q = "SELECT COLUMN_NAME, COLUMN_COMMENT, COLUMN_TYPE, DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'elmenoufi_bot_".$_SESSION[id_usuario]."' AND TABLE_NAME = 'cadastro' ";
                            $r = mysqli_query($conexao2, $q);
                            ?>
                    
                            <div class="form-group">
                            <select InputForm marq class="form-control">
                            <?php
                            while($m = mysqli_fetch_object($r)){
                                if(!in_array($m->COLUMN_NAME,$cp_padrao)){
									
								$COLUMN_NAME = 	($m->COLUMN_NAME);
									
                            ?> 
                                <option InputForm value='<?=($m->COLUMN_NAME)?>'><?=($m->COLUMN_NAME)?></option>
                            <?php 
                                }
                            }
                            ?>
                            </select>
                            </div>
                            <div class="form-group">
                            <button incluir_marq type="button" class="btn btn-gradient-primary btn-sm">ADD
                            </button>                   
                            </div>
                      
                    </div>

					<span class="TituloForms">Mensagem</span><br>
					<textarea class="form-control" id="mensagem" name="mensagem"  > <?=($d->mensagem)?> </textarea>


                    <script>

                        var campo = CKEDITOR.replace( 'mensagem', {
                            uiColor: '#eeeeee',
							height:330,
                            toolbar:[

									/*						[ 'Bold', 'Italic', 'Link', 'Unlink', 'Table', 'Copy', 'Paste' ],
								
								[ 'FontSize', 'Font', 'TextColor', '-', 'Image', 'Maximize' , 'Anchor' ],
								
								[ 'document', 'mode', 'document', '-', 'Source', 'Save' , 'NewPage' , 'Preview' , 'Print' , 'Templates' ],
								
								[ 'clipboard', 'clipboard', 'undo', '-', 'Cut', 'Copy' , 'Paste' , 'PasteFromWord' , 'Undo' , 'Redo' ],
								
								[ 'editing', 'find', 'selection', '-', 'spellchecker', 'Find' , 'Replace' , 'SelectAll' , 'Scayt'  ],
				
								[ 'forms', 'Form', 'Checkbox', '-', 'Radio', 'TextField' , 'Textarea' , 'Select' , 'Button' , 'ImageButton' , 'HiddenField'  ],
								
				
								[ 'basicstyles', 'basicstyles', 'cleanup', '-', 'Bold', 'Italic' , 'Underline' , 'SStrikeelect' , 'Subscript' , 'Superscript' , 'RemoveFormat'  ],
								
								[ 'paragraph', 'list', 'indent', '-', 'blocks', 'align' , 'bidi' , 'NumberedList' , 'BulletedList' , 'Outdent' , 'Indent' , 'Blockquote' , 'CreateDiv' , 'JustifyLeft' , 'JustifyCenter' , 'JustifyRight' , 'JustifyBlock' , 'BidiLtr' , 'BidiRtl' , 'Language'  ],
								
								[ 'links', 'Link', 'Anchor' ],
								
								[ 'insert', 'Image', 'Flash', '-', 'Table', 'HorizontalRule' , 'Smiley' , 'SpecialChar' , 'PageBreak' , 'Iframe' ],
				
								[ 'styles', 'Format', 'Font' , 'FontSize' ]	,
				
								[ 'colors', 'TextColor', 'BGColor' ],	
								
								[ 'tools', 'Maximize', 'ShowBlocks' ],	
								
								[ 'document', 'mode', 'document' , 'doctools' ],
								
								[ 'others', '-' ],		
				
								[ 'about', 'About' ],		
								
								[ 'clipboard', 'clipboard', 'undo' ],	
				
								[ 'editing', 'find', 'selection', 'spellchecker' ]	,								
				
								[ 'forms']	,
				
								[ 'basicstyles', 'basicstyles', 'cleanup' ]	,
								
								[ 'list', 'indent', 'blocks' , 'align' , 'bidi' ],
								
								[ 'links', 'styles', 'colors' , 'tools' , 'others' ]
                            ]
                        });*/
							
							//[

									/*[ 'Source', 'Print' , 'Templates' ],
								
									['Link', 'Unlink', 'Table'],

                                    ['styles', 'Format', 'FontSize', 'Font', 'TextColor', 'BGColor','-', 'Image', 'Maximize', 'Anchor'],
								
									
                                    ['clipboard', 'clipboard', 'undo', '-', 'Cut', 'Copy', 'Paste', 'PasteFromWord', 'Undo', 'Redo'],

                                    ['editing', 'find', 'selection', '-', 'spellchecker', 'Find', 'Replace', 'SelectAll', 'Scayt'],

                                    
                                    ['basicstyles', 'basicstyles', 'cleanup', '-', 'Bold', 'Italic', 'Underline', 'SStrikeelect', 'Subscript', 'Superscript', 'RemoveFormat'],

                                    ['paragraph', 'list', 'indent', '-', 'blocks', 'align', 'bidi', 'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],


                                    ['HorizontalRule', 'SpecialChar', 'PageBreak'],
									

                                    ['tools', 'Maximize']*/
								['Source'],

								[ 'Print' , 'Templates' ],

								[ 'clipboard', 'clipboard', 'undo', '-', 'Cut', 'Copy' , 'Paste' , 'PasteFromWord' , 'Undo' , 'Redo' ],

								[ 'editing', 'find', 'selection', '-', 'spellchecker', 'Find' , 'Replace' , 'SelectAll' , 'Scayt'  ],

								[ 'basicstyles', 'basicstyles', 'cleanup', '-', 'Bold', 'Italic' , 'Underline' , 'SStrikeelect' , 'Subscript' , 'Superscript' , 'RemoveFormat'  ],

								[ 'paragraph', 'list', 'indent', '-', 'blocks', 'align' , 'bidi' , 'NumberedList' , 'BulletedList' , 'Outdent' , 'Indent' , 'Blockquote' , 'CreateDiv' , 'JustifyLeft' , 'JustifyCenter' , 'JustifyRight' , 'JustifyBlock' , 'BidiLtr' , 'BidiRtl' ],

								[ 'Link', 'Unlink', 'Table'],
								
								[ 'TextColor', 'BGColor', '-', 'Maximize' , 'Anchor' ],
							
								[ 'insert', 'Image', '-', 'HorizontalRule' , 'SpecialChar' , 'PageBreak' , 'Iframe' ],
				
								[ 'styles', 'Format', 'Font' , 'FontSize' ]	,	
								
								[ 'ShowBlocks' ]
				
                            ] });


                        $("#EditarGaleria").click(function(){

							//dialogo("./_galeria/editor.php?pasta=paginas_internas");


							Galeria = $.alert({
								content:"url:./_galeria/editor.php?pasta=paginas_internas",
								closeIcon: true,
								confirmButton: false,
								title: false,
								columnClass: 'col-md-12'
							})

                        });


						function AbrirNovoDialog(){
							//dialogo("./_galeria/editor.php?pasta=paginas_internas");

							Galeria = $.alert({
								content:"url:./_galeria/editor.php?pasta=paginas_internas",
								closeIcon: true,
								confirmButton: false,
								title: false,
								columnClass: 'col-md-12'
							})
						}

                    </script>

                </div>

		
               <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 checkbox" style="margin-top:6px;" >
			   
				   
				<div class="form-check">
				<label class="form-check-label">
				  <input id="situacao" name="situacao" value="1" class="checkbox" type="checkbox" checked=""> ncluir na mensagem opção de cancelamento do cadastro <i class="input-helper"></i></label>
				</div>				   
				   
				   
				   
              </div>           

	
	
	
	 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >

	  <button type="submit" id="salvar" name="<?=(($_GET[op]=='novo') ? 'salvar_'.$Script : 'alterar_'.$Script)?>" class="btn btn-success"><?=(($_GET[op]=='novo') ? 'Salvar' : 'Alterar')?></button>                  

	  <button type="button" id="cancelar_<?=$Script?>" name="cancelar" class="btn btn-danger">Cancelar</button>                  

	 </div>
	 <input type="hidden" name="destination" value="<?=$d->destination?>" />

	  <input type="hidden" id="cod_<?=$Script?>" name="cod" value="<?=$_GET[cod]?>" />	


    </div>


   </div>

 </form>	


</div>	
</div>	
	
	
<script language="javascript">

	
		$("#cancelar_<?=$Script?>").click(function(){

		       $("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
				$.ajax({
					url: './<?=$Conf[script]?>.php',
					success: function(data) {
					$('#<?=$_SESSION[$Script][dialog]?>').html(data);
						$('#CARREGANDO').html('');
					}
		        });
		});
	
	

	//$(function(){
		
		$("a[Fechar]").click(function(){
		    Ausuario.close();
		    //Nusuario.close();
		});



		$("button[incluir_marq]").click(function(){
			msg = $("#mensagem").val();
			nome = $("select[marq]").val();
			campo.insertHtml('[' + nome + ']');
		});	


	//})
	


	
		//opção de salvar
		function retornar_<?=$Script?>(cod){
			
			$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

			$.confirm({
			title: "",
			content: "Campanha cadastrada com sucesso!",
			columnClass:"col-md-4 col-md-offset-4",
			theme: "light",
			buttons: {
				Ok: {
					btnClass: "btn-success",
					action: function(){
						$.ajax({
						url: './<?=$Conf[script]?>.php',
						success: function(data) {
						$('#<?=$_SESSION[$Script][dialog]?>').html(data);
							$('#CARREGANDO').html('');
						}
					   });						
					}
				},
			}				
		    });			

		}	
	
	
    
    //$(function(){
        $("#ValidarMailer").validate({
			errorClass: 'error-view',
			validClass: 'success-view',
			errorElement: 'span',

			// Define as regras
			rules: {
				nome: {
					required: true
				},
				email: {
					required: true
				},
				assinatura: {
					required: true
				},
			},
			messages: {
				nome: {
					required: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Campo obrigatorio!</div>' 
				},
				email: {
					required: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Campo obrigatorio!</div>' 
				},
				assinatura: {
					required: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Campo obrigatorio!</div>' 
				},
			}
        });
    //});


$('#nome').focus();	
	
</script>
