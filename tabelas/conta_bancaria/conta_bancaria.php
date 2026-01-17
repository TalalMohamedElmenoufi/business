<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/conta_bancaria/conta_bancaria';
$Script = md5($Conf[script]);

list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao, "select count(id) from usuarios  "));
$_SESSION[$Script][tabela] = 'usuarios';
$_SESSION[$Script][dialog] = 'CONTEUDOS';
$_SESSION[$Script][url] = 'tabelas/conta_bancaria/conta_bancaria';
$_SESSION[$Script][titulo] = 'Sua conta bancaria';

list($api_token_dono) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id='1'  "));
//ListaContas($api_token_dono);

list($proprietario) = mysqli_fetch_row(mysqli_query($conexao, "select nome from usuarios where id='".$_SESSION[id_usuario]."'  "));


list($api_token) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario='".$_SESSION[id_usuario]."'  "));

list($id_alerta) = mysqli_fetch_row(mysqli_query($conexao2, "select MAX(id) from alertas ")); 
list($alerta) = mysqli_fetch_row(mysqli_query($conexao2, "select alerta from alertas where id = '".$id_alerta."' ")); 


$pergunta = "select * from asaas where id_usuario='".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);

if($d->stDoc!='APPROVED'){
	//echo "stDoc:".$d->stDoc;
	StatusDoc1($api_token,$conexao,$d->id,$Script);
}
if($d->stDoc_contrat!='APPROVED'){
	//echo "stDoc:".$d->stDoc;
	StatusDoc2($api_token,$conexao,$d->id,$Script);
}


$pergunta2 = "select * from usuarios where id='".$_SESSION[id_usuario]."' ";
$resultado2 = mysqli_query($conexao, $pergunta2);
$d2 = mysqli_fetch_object($resultado2);

list($estado) = mysqli_fetch_row(mysqli_query($conexao, "select nome from estados where cod_estados= '".$d2->estado."'  "));

if(isset($_POST["salvar_".$Script])){
	
	
if($_POST[token_producao]){	
	
	
	/*----------------------------------------------*/	
	if($_FILES["documentFile_limited_doc"]["name"]){

	$trat = RetiraEspaco($_FILES["documentFile_limited_doc"]["name"]) ;
	$arquivo = date("YmdHis")."_".acentos($trat) ;

	$ultimos4 = substr($arquivo , -4);

	if($ultimos4 == ".png" or $ultimos4 == ".PNG" or $ultimos4 == ".jpg" or  $ultimos4 == ".JPG" or $ultimos4 == ".pdf" or  $ultimos4 == ".PDF" ){
		$ext_file = substr($ultimos4, 1);
	}elseif($ultimos4 == "JPEG" or $ultimos4 == "jpeg"){
		$ext_file = $ultimos4;
	}

	$uploaddir = "./upload/";
	move_uploaded_file($_FILES["documentFile_limited_doc"]["tmp_name"], $uploaddir.$arquivo);
	
	
	$documentType_limited_doc = $_POST[documentType_limited_doc];
	$documentGroupType_limited_doc = $_POST[documentGroupType_limited_doc];
		EviarDoc($api_token,$arquivo,$ext_file,$documentType_limited_doc,$documentGroupType_limited_doc,$conexao,$_POST[CodR],$Script );		
	//echo "<script>alert(' $api_token e $_POST[CodR] ');</script>";
		
	}
	/*----------------------------------------------*/	
	
	/*----------------------------------------------*/	
	if($_FILES["documentFile_limited_contrato"]["name"]){

	$trat = RetiraEspaco($_FILES["documentFile_limited_contrato"]["name"]) ;
	$arquivo = date("YmdHis")."_".acentos($trat) ;

	$ultimos4 = substr($arquivo , -4);

	if($ultimos4 == ".png" or $ultimos4 == ".PNG" or $ultimos4 == ".jpg" or  $ultimos4 == ".JPG" or $ultimos4 == ".pdf" or  $ultimos4 == ".PDF" ){
		$ext_file = substr($ultimos4, 1);
	}elseif($ultimos4 == "JPEG" or $ultimos4 == "jpeg"){
		$ext_file = $ultimos4;
	}

	$uploaddir = "./upload/";
	move_uploaded_file($_FILES["documentFile_limited_contrato"]["tmp_name"], $uploaddir.$arquivo);

	$documentType_limited_contrato = $_POST[documentType_limited_contrato];
	$documentGroupType_limited_contrato = $_POST[documentGroupType_limited_contrato];
	EviarDoc($api_token,$arquivo,$ext_file,$documentType_limited_contrato,$documentGroupType_limited_contrato,$conexao,$_POST[CodR],$Script );	
	//echo "<script>alert(' $api_token e $_POST[CodR] ');</script>";
		
	}
	/*----------------------------------------------*/	
	
	
	
	
	/*----------------------------------------------*/	
	if($_FILES["documentFile_individual_doc"]["name"]){

	$trat = RetiraEspaco($_FILES["documentFile_individual_doc"]["name"]) ;
	$arquivo = date("YmdHis")."_".acentos($trat) ;

	$ultimos4 = substr($arquivo , -4);

	if($ultimos4 == ".png" or $ultimos4 == ".PNG" or $ultimos4 == ".jpg" or  $ultimos4 == ".JPG" or $ultimos4 == ".pdf" or  $ultimos4 == ".PDF" ){
		$ext_file = substr($ultimos4, 1);
	}elseif($ultimos4 == "JPEG" or $ultimos4 == "jpeg"){
		$ext_file = $ultimos4;
	}

	$uploaddir = "./upload/";
	move_uploaded_file($_FILES["documentFile_individual_doc"]["tmp_name"], $uploaddir.$arquivo);
	
	
	$documentType_individual_doc = $_POST[documentType_individual_doc];
	$documentGroupType_individual_doc = $_POST[documentGroupType_individual_doc];
		EviarDoc($api_token,$arquivo,$ext_file,$documentType_individual_doc,$documentGroupType_individual_doc,$conexao,$_POST[CodR],$Script );		
	//echo "<script>alert(' $api_token e $_POST[CodR] ');</script>";
		
	}
	/*----------------------------------------------*/	
	
	/*----------------------------------------------*/	
	if($_FILES["documentFile_individual_contrato"]["name"]){

	$trat = RetiraEspaco($_FILES["documentFile_individual_contrato"]["name"]) ;
	$arquivo = date("YmdHis")."_".acentos($trat) ;

	$ultimos4 = substr($arquivo , -4);

	if($ultimos4 == ".png" or $ultimos4 == ".PNG" or $ultimos4 == ".jpg" or  $ultimos4 == ".JPG" or $ultimos4 == ".pdf" or  $ultimos4 == ".PDF" ){
		$ext_file = substr($ultimos4, 1);
	}elseif($ultimos4 == "JPEG" or $ultimos4 == "jpeg"){
		$ext_file = $ultimos4;
	}

	$uploaddir = "./upload/";
	move_uploaded_file($_FILES["documentFile_individual_contrato"]["tmp_name"], $uploaddir.$arquivo);

	$documentType_individual_contrato = $_POST[documentType_individual_contrato];
	$documentGroupType_individual_contrato = $_POST[documentGroupType_individual_contrato];
	EviarDoc($api_token,$arquivo,$ext_file,$documentType_individual_contrato,$documentGroupType_individual_contrato,$conexao,$_POST[CodR],$Script );	
	//echo "<script>alert(' $api_token e $_POST[CodR] ');</script>";
		
	}
	/*----------------------------------------------*/	
	
	 
	
	
	
	
	
	
	
	/*----------------------------------------------*/	
	if($_FILES["documentFile_mei_doc"]["name"]){

	$trat = RetiraEspaco($_FILES["documentFile_mei_doc"]["name"]) ;
	$arquivo = date("YmdHis")."_".acentos($trat) ;

	$ultimos4 = substr($arquivo , -4);

	if($ultimos4 == ".png" or $ultimos4 == ".PNG" or $ultimos4 == ".jpg" or  $ultimos4 == ".JPG" or $ultimos4 == ".pdf" or  $ultimos4 == ".PDF" ){
		$ext_file = substr($ultimos4, 1);
	}elseif($ultimos4 == "JPEG" or $ultimos4 == "jpeg"){
		$ext_file = $ultimos4;
	}

	$uploaddir = "./upload/";
	move_uploaded_file($_FILES["documentFile_mei_doc"]["tmp_name"], $uploaddir.$arquivo);
	
	
	$documentType_mei_doc = $_POST[documentType_mei_doc];
	$documentGroupType_mei_doc = $_POST[documentGroupType_mei_doc];
		EviarDoc($api_token,$arquivo,$ext_file,$documentType_mei_doc,$documentGroupType_mei_doc,$conexao,$_POST[CodR],$Script );	
	//echo "<script>alert(' $api_token e $_POST[CodR] ');</script>";
		
	}
	/*----------------------------------------------*/		
	
	
	/*----------------------------------------------*/	
	if($_FILES["documentFile_fisica"]["name"]){

	$trat = RetiraEspaco($_FILES["documentFile_fisica"]["name"]) ;
	$arquivo = date("YmdHis")."_".acentos($trat) ;

	$ultimos4 = substr($arquivo , -4);

	if($ultimos4 == ".png" or $ultimos4 == ".PNG" or $ultimos4 == ".jpg" or  $ultimos4 == ".JPG" or $ultimos4 == ".pdf" or  $ultimos4 == ".PDF" ){
		$ext_file = substr($ultimos4, 1);
	}elseif($ultimos4 == "JPEG" or $ultimos4 == "jpeg"){
		$ext_file = $ultimos4;
	}

	$uploaddir = "./upload/";
	move_uploaded_file($_FILES["documentFile_fisica"]["tmp_name"], $uploaddir.$arquivo);
	
	
	$documentType_fisica = $_POST[documentType_fisica];
	$documentGroupType_fisica = $_POST[documentGroupType_fisica];
		EviarDoc($api_token,$arquivo,$ext_file,$documentType_fisica,$documentGroupType_fisica,$conexao,$_POST[CodR],$Script );	
	//echo "<script>alert(' $api_token e $_POST[CodR] ');</script>";
		
	}
	/*----------------------------------------------*/		
	

	
}
	
	
$CodR = $_POST[CodR];
$CodCliente = $_POST[CodCliente];	
	
$cpf_cnpj0 = str_replace(".","",$_POST[cpfCnpj]);
$cpf_cnpj1 = str_replace("-","",$cpf_cnpj0);
$cpf_cnpj = str_replace("/","",$cpf_cnpj1);	

$mobile1 = explode(" ",$_POST[mobilePhone]) ;
$celular = $mobile1[1]."".$mobile1[2]."".str_replace("-","",$mobile1[3]);

$cep = str_replace("-","",$_POST[postalCode]);
	
$address = ($_POST[address]);
$addressNumber = $d2->numero;
$companyType = $_POST[companyType];//limited
$complement = ($_POST[complement]);
$cpfCnpj = $cpf_cnpj;
$email = $_POST[email];
$mobilePhone = $celular;
$name = ($_POST[name]); 
$phone = $celular;
$postalCode = $cep;
$province = ($_POST[province]);

	$account = $_POST[account];
	$accountDigit = $_POST[accountDigit];
	$accountName = ($_POST[accountName]); //apenas um campo aberto
	$agency = $_POST[agency];
	$bank = $_POST[bank];
	$bankAccountType = $_POST[bankAccountType];  //CONTA_POUPANCA ou CONTA_CORRENTE
	$cpfCnpj2 = $cpf_cnpj;
	$name2 = ($_POST[name]); //nome pessoa fisica ou juridica conforme a razão social	
	
	
//echo "<script>alert(' $cpfCnpj ');</script>";	

	
if(!$_POST[token_producao]){
	sendCadastroAsaas($conexao,$conexao2,$api_token_dono,$CodR,$CodCliente ,$address,$addressNumber,$companyType,$complement,$cpfCnpj,$email,$mobilePhone,$name,$phone,$postalCode,$province,$account,$accountDigit,$accountName,$agency,$bank,$bankAccountType,$cpfCnpj2,$name2,$alerta );

	echo "<script>parent.retornar_$Script()</script>";
	exit();	
	
}else{
	
	mysqli_query($conexao, " update asaas set razao_social='".$name."', companyType='".$companyType."', account='".$account."', accountDigit='".$accountDigit."', agency='".$agency."', bank='".$bank."', bankAccountType='".$bankAccountType."' where id = '".$CodR."' ");	

	echo "<script>parent.retornar_$Script()</script>";
	exit();
}	
	 
	
}

	
function sendCadastroAsaas($conexao,$conexao2,$api_token_dono,$CodR,$CodCliente ,$address,$addressNumber,$companyType,$complement,$cpfCnpj,$email,$mobilePhone,$name,$phone,$postalCode,$province,$account,$accountDigit,$accountName,$agency,$bank,$bankAccountType,$cpfCnpj2,$name2,$alerta ){
	
	if($companyType=='pessoa_fisica'){
		$companyTypeAsaas = false;
	}else{
		$companyTypeAsaas = $companyType;
	}
	
	$fields = array
	(
		'address' => ($address),
		'addressNumber' => $addressNumber,
		'companyType' => $companyTypeAsaas,
		'complement' => $complement,
		'cpfCnpj' => $cpfCnpj,
		'email' => $email,
		'mobilePhone' => $mobilePhone,
		'name' => ($name),
		'phone' => $phone,
		'postalCode' => $postalCode,
		'province' => ($province),

		'bankAccount' =>
			$fields = array
			([
			'account' => $account,
			'accountDigit' => $accountDigit,	
			'accountName' => ($accountName),	
			'agency' => $agency,	
			'bank' => $bank,	
			'bankAccountType' => $bankAccountType,	
			'cpfCnpj' => $cpfCnpj2,	
			'name' => ($name2),
			]),
		
	);
	

	
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token_dono,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/accounts");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	
	$response = curl_exec($ch);
	curl_close($ch);

	    $jsonObjStatusErro = json_decode($response);
		$ListaDocsStatusErro = $jsonObjStatusErro->errors;
		foreach ( $ListaDocsStatusErro as $e ){
			
			//echo "code: $e->code - description: $e->description <br>"; 
			$descriptionSt = $e->description;
		}	
	
		if($descriptionSt){
			
		$alertaDb = ($alerta);
			
			if($descriptionSt!=$alertaDb){
				mysqli_query($conexao2, " insert into alertas set alerta = '".($descriptionSt)."', url = './tabelas/conta_bancaria/conta_bancaria.php', lido = 'N' " );
			}
		}
	
	$obj = json_decode($response);
	   
	//echo "<script>alert(' ".implode(",",$fields)." | ".$descriptionSt." ');</script>";
	
	if($CodR){
		mysqli_query($conexao, " update asaas set id_usuario='".$CodCliente."', token_producao='".$obj->apiKey."', razao_social='".$name."', companyType='".$companyType."', account='".$account."', accountDigit='".$accountDigit."', agency='".$agency."', bank='".$bank."', bankAccountType='".$bankAccountType."', log='$response | ".implode("| ",$fields)." ' where id = '".$CodR."' ");		
	}else{
		mysqli_query($conexao, " insert into asaas set id_usuario='".$CodCliente."', token_producao='".$obj->apiKey."', razao_social='".$name."', companyType='".$companyType."', account='".$account."', accountDigit='".$accountDigit."', agency='".$agency."', bank='".$bank."', bankAccountType='".$bankAccountType."', log='$response | ".implode("| ",$fields)." ' ");	
	}
 
//var_dump($response);	
}



function EviarDoc($api_token,$arquivo,$ext_file,$documentTypeDoc,$documentGroupTypeDoc,$conexao,$CodR,$Script){

	
	if($ext_file=='pdf' or $ext_file=='PDF'){
		$TipoExt = 'application';
	}else{
		$TipoExt = 'image';
	}

	$cfile = curl_file_create('/home/elmenoufi/public_html/business/tabelas/conta_bancaria/upload/'.$arquivo,$TipoExt.'/'.$ext_file);

	$authorization = "Bearer $api_token";

	$fields = array
	(
		'documentType' => $documentTypeDoc,
		'documentGroupType' => $documentGroupTypeDoc,
		'documentFile' => $cfile
	);

	$headers = array
	(
	'Content-Type: multipart/form-data',
	'Authorization: ' . $authorization

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://api.asaas.com/v2/documents' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, $fields );
	curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
	
	$result = curl_exec($ch );
	echo " v2/documents ". $result."<br><br>";
	
	curl_close( $ch );

	StatusDoc1($api_token,$conexao,$CodR,$Script);
	
	StatusDoc2($api_token,$conexao,$CodR,$Script);
}


function StatusDoc1($api_token,$conexao,$CodR,$Script){

	$headers = array
	(
	'Content-Type: application/json',
	'access_token: ' . $api_token

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://api.asaas.com/v3/myAccount/status/documentation/' );
	curl_setopt( $ch,CURLOPT_GET, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
	$result = curl_exec($ch );
	curl_close( $ch );

	echo " status/documentation 01 ". $result."<br><br>";
	
	    $jsonObjStatusErro = json_decode($result);
		$ListaDocsStatusErro = $jsonObjStatusErro->errors;
		foreach ( $ListaDocsStatusErro as $e ){
			
			//echo "code: $e->code - description: $e->description <br>"; 
			$descriptionSt = $e->description;
			
		}
	
	
	
		$json_str = '{"ListaDocsStatus": '.	'[' . $result . ']}';

		$jsonObjStatus = json_decode($json_str);
		$ListaDocsStatus = $jsonObjStatus->ListaDocsStatus;
	
		foreach ( $ListaDocsStatus as $e ){
			
			//echo "status: $e->status - observations: $e->observations <br>"; 
			$stObs = $e->status;
			$Obs = $e->observations;
			foreach ( $e->documents as $d ){
				//echo "status: $d->status - group $d->group - type $d->type <br>"; 
				$stDoc = $d->status ;
				$Grupo = $d->group;
				$Tipo = $d->type;
					foreach ( $d->files as $f ){
						$stArq = $f->status ;
						$Arquivo = $f->name;
						//echo "status: $f->status - group $f->name <br>"; 
					}
			}

			
		}	
		
		//echo "<script>alert( 'Passo 3 $stDoc ' );</script>";
		
	    $stObsVez = (($stObs)?$stObs:$descriptionSt);
	
		mysqli_query($conexao, " update asaas set stObs='".$stObsVez."', Obs='".$Obs."', stDoc='".$stDoc."', Grupo='".$Grupo."', Tipo='".$Tipo."', stArq='".$stArq."', Arquivo='".$Arquivo."' where id = '".$CodR."' ");	
	 
	    //echo "<br>Dados para DB:<br> stObs:$stObs - Obs:$Obs - stDoc:$stDoc - Grupo:$Grupo - Tipo:$Tipo - stArq:$stArq - Arquivo:$Arquivo " ;
 
}

function StatusDoc2($api_token,$conexao,$CodR,$Script){

	$headers = array
	(
	'Content-Type: application/json',
	'access_token: ' . $api_token

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://api.asaas.com/v3/myAccount/status/documentation/' );
	curl_setopt( $ch,CURLOPT_GET, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
	$result = curl_exec($ch );
	curl_close( $ch );

	echo " status/documentation 02 ". $result."<br><br>";	
	
	
	    $jsonObjStatusErro = json_decode($result);
		$ListaDocsStatusErro = $jsonObjStatusErro->errors;
		foreach ( $ListaDocsStatusErro as $e ){
			
			//echo "code: $e->code - description: $e->description <br>"; 
			$descriptionSt = $e->description;
			
		}
	
	
		$json_str = '{"ListaDocsStatus": '.	'[' . $result . ']}';

		$jsonObjStatus = json_decode($json_str);
		$ListaDocsStatus = $jsonObjStatus->ListaDocsStatus;
	
		foreach ( $ListaDocsStatus as $e ){
			
			//echo "status: $e->status - observations: $e->observations <br>"; 
			$stObs = $e->status;
			$Obs = $e->observations;
			foreach ( $e->documents as $d ){
				//echo "status: $d->status - group $d->group - type $d->type <br>"; 
				$stDoc = $d->status ;
				$Grupo = $d->group;
				$Tipo = $d->type;
					foreach ( $d->files as $f ){
						$stArq = $f->status ;
						$Arquivo = $f->name;
						//echo "status: $f->status - group $f->name <br>"; 
					}
			}

			
		}	
	
		$stObsVez = (($stObs)?$stObs:$descriptionSt);
	
		mysqli_query($conexao, " update asaas set stObs_contrat='".$stObsVez."', Obs_contrat='".$Obs."', stDoc_contrat='".$stDoc."', Grupo_contrat='".$Grupo."', Tipo_contrat='".$Tipo."', stArq_contrat='".$stArq."', Arquivo_contrat='".$Arquivo."' where id = '".$CodR."' ");	
	
	    //echo "<br>Dados para DB:<br> stObs:$stObs - Obs:$Obs - stDoc:$stDoc - Grupo:$Grupo - Tipo:$Tipo - stArq:$stArq - Arquivo:$Arquivo " ;
   
}

function ListaContas($api_token_dono){
	
	
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/accounts");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "access_token: $api_token_dono"
));

$response = curl_exec($ch);
curl_close($ch);

//echo "<script>alert(' 03 - $response ');</script>";	
	
}
	




?>



<style>
.fieldset-border {
  border: 1px groove #ddd !important;
  padding: 0 0.4em 0.4em 0.4em !important;
  margin: 0 0 1.5em 0 !important;
  -webkit-box-shadow: 0px 0px 0px 0px #000;
  box-shadow: 0px 0px 0px 0px #000;
}

.fieldset-border .legend-border {
  font-size: 1.2em !important;
  text-align: left !important;
  width: auto;
  padding: 0 3px;
  border-bottom: none;
}
	
	
.btn-bs-file{
    position:relative;
	width:100%;
}
.btn-bs-file input[type="file"]{
    position: absolute;
    top: -9999999;
    filter: alpha(opacity=0);
    opacity: 0;
    width:0;
    height:0;
    outline: none;
    cursor: inherit;
}	
	
	.IconContrac{
		font-size:20px !important;
	}
	.TextContrac{
		font-size:15px !important;
	}	
	
	.IconCpfCnh{
		font-size:20px !important;
	}
	.TextCpfCnh{
		font-size:15px !important;
	}
	
	.Iconind{
		font-size:20px !important;
	}
	.Textind{
		font-size:15px !important;
	}
	.IconCpfCnhindi{
		font-size:20px !important;
	}
	.TextCpfCnhindi{
		font-size:15px !important;
	}	
	
	
	.Aprovado{
		font-size:27px;
		color:#0D5100;
	}
	.AprovadoText{
		font-size:16px;
		font-family: Constantia, "Lucida Bright", "DejaVu Serif", Georgia, "serif";
		font-weight: 800;
	}
	
	.ObsST{
		font-size:11px;
		color: #E00003;
	}
	
</style>



<div class="card" >
<div class="card-body">
	
		<div class="panel-heading">
		<?=$d->id?> - <?=($_SESSION[$Script][titulo])?>
		</div>

		<form id="conta_bancaria" action="<?=$Conf[script]?>.php" method="post" target="pagina" enctype="multipart/form-data" >	
			 
		<div class="row">
	
			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Nome ou Razão social</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="name" class="form-control" placeholder="Nome ou Razão social" value="<?=($d->razao_social)?>" /></p>
			</div>	




			<div class="form-group col-lg-4 col-md-4">
				
			

				<?php
				if($d2->tipo_pessoa=='F'){
				?>
				 <span class="TituloForms">Tipo pessoa</span><br>
				  <i class="far fa-copyright Icons"></i>				
				  <p><input InputForm type="text" name="companyType" class="form-control"   value="pessoa_fisica" readonly /></p>
				<?php
				}else{
				?>
				<span class="TituloForms">Tipo da empresa:</span> 	
				<select InputForm class="form-control input-text-select" id="companyType" name="companyType" style="display:<?=($d2->tipo_pessoa=='J')?'block':'none'?>">
				<option InputForm value="">::Selecione::</option>
				<option InputForm value="mei" <?=(($d->companyType=='mei')?'selected':false)?> >Micro Empreendedor individual (mei)</option>
				<option InputForm value="limited" <?=(($d->companyType=='limited')?'selected':false)?>>Empresa limitada</option>	
				<option InputForm value="individual" <?=(($d->companyType=='individual')?'selected':false)?>>Empresa individual</option>
				<option InputForm value="association" <?=(($d->companyType=='association')?'selected':false)?>>Associação</option>
				</select>				
				<?php
				}
	            ?>
				

			</div>				 
		

			
			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Nº Conta</span>
				  <p><input type="number" name="account" class="form-control" placeholder="Nº Conta" value="<?=($d->account)?>" /></p>
			</div>	
			
			<div class="form-group col-lg-2 col-md-2">
				 <span class="TituloForms">Digito</span>
				  <p><input type="number" name="accountDigit" class="form-control" placeholder="Digito" value="<?=($d->accountDigit)?>" /></p>
				
				
				<input type="hidden" name="accountName" class="form-control" value="Referencia <?=($d->razao_social)?>" />
			</div>	
			
			<div class="form-group col-lg-2 col-md-2">
				 <span class="TituloForms">Agencia</span>
				  <p><input type="number" name="agency" class="form-control" placeholder="Agencia" value="<?=($d->agency)?>" /></p>
			</div>			


			<div class="form-group col-lg-4 col-md-4">
				<span class="TituloForms">Banco:</span> 

				<select Meubanco class="selectpicker" data-width="100%" title="Informe seu banco" data-live-search="true" name="bank" >
				 <option class="MenusAoPassar" data-icon="fas fa-university" data-subtext="" value="">Selecione  o seu banco</option>
				  <?php
					$query = "select * from  bancos_clientes order by id desc";
					$result = mysqli_query($conexao, $query);
					while($b = mysqli_fetch_object($result)){
				  ?>
				  <option class="MenusAoPassar" data-icon="fas fa-university" data-subtext="<?=$b->cod_bn?>" value="<?=$b->cod_bn?>" <?=(($d->bank==$b->cod_bn)?'selected':false)?> ><?=($b->banco_nome)?></option>
				  <?php
					}
				  ?>
				</select>				
				
			</div>			

			
			<div class="form-group col-lg-4 col-md-4">
				<span class="TituloForms">Tipo da conta:</span> 
				<select InputForm class="form-control input-text-select" id="bankAccountType" name="bankAccountType">
				<option InputForm value="">::Selecione::</option>
				<option InputForm value="CONTA_POUPANCA" <?=(($d->bankAccountType=='CONTA_POUPANCA')?'selected':false)?> >Poupança</option>
				<option InputForm value="CONTA_CORRENTE" <?=(($d->bankAccountType=='CONTA_CORRENTE')?'selected':false)?>>Corrente</option>	
				</select>
			</div>
			
			

<?php
	//$mostrar = (($d->companyType)?'block': (($d2->tipo_pessoa=='F')?'block': false)  ) ;
	$mostrar = (($d->token_producao)?'block': 'none') ;
?>
			
<div ANOXOS class="form-group col-lg-12 col-md-12" style="display:<?=$mostrar?>" >	
	
<fieldset class="fieldset-border">
  <legend class="legend-border">ANEXO DE DOCUMENTOS</legend>	

	<?php
	
		$StDos = trim($d->stDoc);
		
		$stDocs = ( (($StDos=='APPROVED')?'APROVADO':  
				   
				    ( ($StDos=='REJECTED')?'REJEITADO':   
				   
				    ( ($StDos=='Y')?'Y': 

				    '' 
		            ))));	
		
		$StDos2 = trim($d->stDoc_contrat);
		
		$stDocs2 = ( (($StDos2=='APPROVED')?'APROVADO':  
				   
				    ( ($StDos2=='REJECTED')?'REJEITADO':   
				   
				    ( ($StDos2=='Y')?'Y': 

				    '' 
		            ))));
		
		
		
		if($StDos=='APPROVED'){
			$iconD1 = 'fas fa-id-card-alt';
		}else{
			$iconD1 = 'fas fa-exclamation-triangle';
		}
		
		if($StDos2=='APPROVED'){
			$iconD2 = 'fas fa-id-card-alt';
		}else{
			$iconD2 = 'fas fa-exclamation-triangle';
		}	
	
	
	if($d2->tipo_pessoa=='F'){
	?>
	<div class="row">

			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs?></span> <i class="fas fa-check Aprovado"></i>
			</div>		
		
			<input type="hidden" name="documentType_fisica" value="IDENTIFICATION" style="display:<?=(($d2->tipo_pessoa=='F')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_fisica" value="<?=($proprietario)?>" style="display:<?=(($d2->tipo_pessoa=='F')?'block':'none')?>" />		
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'none':'block')?>">
			<span class="TituloForms" style="color:#000000">Anexar CPF ou CNH:</span><br>
				<span class="ObsST"><?=$d->stObs?></span>	
			<label class="btn-bs-file btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Anexar CPF ou CNH">
				<i class="fas fa-id-card IconCpfCnhmei" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextCpfCnhmei"><strong><?=(($d->stDoc)?$stDocs:'CPF ou CNH')?></strong></span></b>
				<input type="file" name="documentFile_fisica" id="documentFile_fisica" style="display:<?=(($d2->tipo_pessoa=='F')?'block':'none')?>" >
			</label>
			</div>	

	</div>	

	<?php
	}else{
		

	?>

	
	<div limited style="display:<?=(($d->companyType=='limited')?'block':'none')?>">
		
		<div class="row">

			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs?></span> <i class="fas fa-check Aprovado"></i>
			</div>		

			<input type="hidden" name="documentType_limited_doc" value="IDENTIFICATION" style="display:<?=(($d->companyType=='limited')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_limited_doc" value="PARTNER" style="display:<?=(($d->companyType=='limited')?'block':'none')?>" />		
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'none':'block')?>">
			<span class="TituloForms" style="color:#000000">Anexar CPF ou CNH:</span><br>
				<span class="ObsST"><?=$d->stObs?></span>	
			<label class="btn-bs-file btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Anexar CPF ou CNH">
				<i class="<?=$iconD1?> IconCpfCnh" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextCpfCnh"><strong><?=(($d->stDoc)?$stDocs:'CPF ou CNH')?></strong></span></b>
				<input type="file" name="documentFile_limited_doc" id="documentFile_limited_doc" style="display:<?=(($d->companyType=='limited')?'block':'none')?>" >
			</label>
			</div>	

			
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc_contrat=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs2?></span> <i class="fas fa-check Aprovado"></i>
			</div>				
			
			<input type="hidden" name="documentType_limited_contrato" value="SOCIAL_CONTRACT" style="display:<?=(($d->companyType=='limited')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_limited_contrato" value="LIMITED_COMPANY" style="display:<?=(($d->companyType=='limited')?'block':'none')?>"/>
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc_contrat=='APPROVED')?'none':'block')?>"> 
			<span class="TituloForms" style="color:#000000">Anexar Contrato Social:</span><br>
			<label class="btn-bs-file btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Anexar Contrato Social">
				<i class="<?=$iconD2?> IconContrac" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextContrac"><?=(($d->stDoc_contrat)?$stDocs2:'Contrato Social')?></span></b>
				<input type="file" name="documentFile_limited_contrato" id="documentFile_limited_contrato" style="display:<?=(($d->companyType=='limited')?'block':'none')?>" >
			</label>
			</div>

			
		</div>
		
	</div>
	
	<div individual style="display:<?=(($d->companyType=='individual')?'block':'none')?>">
		
		<div class="row">

			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs?></span> <i class="fas fa-check Aprovado"></i>
			</div>		

			<input type="hidden" name="documentType_individual_doc" value="IDENTIFICATION" style="display:<?=(($d->companyType=='individual')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_individual_doc" value="INDIVIDUAL_COMPANY" style="display:<?=(($d->companyType=='individual')?'block':'none')?>" />		
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'none':'block')?>">
			<span class="TituloForms" style="color:#000000">Anexar CPF ou CNH:</span><br>
				<span class="ObsST"><?=$d->stObs?></span>
			<label class="btn-bs-file btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Anexar CPF ou CNH">
				<i class="<?=$iconD1?> IconCpfCnh" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextCpfCnh"><strong><?=(($d->stDoc)?$stDocs:'CPF ou CNH')?></strong></span></b>
				<input type="file" name="documentFile_individual_doc" id="documentFile_individual_doc" style="display:<?=(($d->companyType=='individual')?'block':'none')?>" >
			</label>
			</div>	

			
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc_contrat=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs2?></span> <i class="fas fa-check Aprovado"></i>
			</div>				
			
			<input type="hidden" name="documentType_individual_contrato" value="ENTREPRENEUR_REQUIREMENT" style="display:<?=(($d->companyType=='individual')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_individual_contrato" value="INDIVIDUAL_COMPANY" style="display:<?=(($d->companyType=='individual')?'block':'none')?>"/>
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc_contrat=='APPROVED')?'none':'block')?>"> 
			<span class="TituloForms" style="color:#000000">Anexar Contrato Social:</span><br>
				
			<label class="btn-bs-file btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Anexar Contrato Social">
				<i class="<?=$iconD2?> IconContrac" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextContrac"><?=(($d->stDoc_contrat)?$stDocs2:'Contrato Social')?></span></b>
				<input type="file" name="documentFile_individual_contrato" id="documentFile_individual_contrato" style="display:<?=(($d->companyType=='individual')?'block':'none')?>" >
			</label>
			</div>

	
			
		</div>		
		
	</div>
	
	<div mei style="display:<?=(($d->companyType=='mei')?'block':'none')?>">
		
		<div class="row">
		
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs?></span> <i class="fas fa-check Aprovado"></i>
			</div>		

			<input type="hidden" name="documentType_mei_doc" value="IDENTIFICATION" style="display:<?=(($d->companyType=='mei')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_mei_doc" value="MEI" style="display:<?=(($d->companyType=='mei')?'block':'none')?>" />		
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'none':'block')?>">
			<span class="TituloForms" style="color:#000000">Anexar CPF ou CNH:</span><br>
				<span class="ObsST"><?=$d->stObs?></span>	
			<label class="btn-bs-file btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Anexar CPF ou CNH">
				<i class="<?=$iconD1?> IconCpfCnh" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextCpfCnh"><strong><?=(($d->stDoc)?$stDocs:'CPF ou CNH')?></strong></span></b>
				<input type="file" name="documentFile_mei_doc" id="documentFile_mei_doc" style="display:<?=(($d->companyType=='mei')?'block':'none')?>" >
			</label>
			</div>	
			
		</div>			
		
	</div>
	
	<div association style="display:<?=(($d->companyType=='association')?'block':'none')?>">
		<center>Não disponivel no momento!</center>
	</div>	
	
	
	

	<?php
	}
	?>	
	
	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

	</div>
	
	
	
</fieldset>	
	
</div>			
			
			
			<hr>
			

			<?php $tipoPessoa = ( ($d2->tipo_pessoa=='F')?'FISICA' : (($d2->tipo_pessoa=='J')? 'JURIDICA':false) ) ?>
			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Tipo pessoa</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="personType" class="form-control" placeholder="Complemento"  value="<?=$tipoPessoa?>" readonly /></p>
			</div>
			
			
			
			<div class="form-group col-lg-6 col-md-6">
				 <span class="TituloForms">Endereço</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="address" class="form-control" placeholder="Endereço"  value="<?=($d2->endereco)?>" readonly /></p>
			</div>		

			<div class="form-group col-lg-2 col-md-2">
				 <span class="TituloForms">Número</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="addressNumber" class="form-control" placeholder="Número"  value="<?=($d2->numero)?>" readonly /></p>
			</div>		

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Complemento</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="complement" class="form-control" placeholder="Complemento"  value="<?= (($d2->complemento)?($d2->complemento):'Não informado') ?>" readonly /></p>
			</div>			

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">CPF/CNPJ</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="cpfCnpj" class="form-control" placeholder="CPF/CNPJ"  value="<?=($d2->cpf_cnpj)?>" readonly /></p>
			</div>				

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">E-mail</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="email" class="form-control" placeholder="E-mail" value="<?=($d2->email)?>" readonly /></p>
			</div>				

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Celular</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="mobilePhone" class="form-control" placeholder="Celular" value="<?=($d2->celular)?>" readonly /></p>
			</div>				
					

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">CEP</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="postalCode" class="form-control" placeholder="CEP" value="<?=($d2->cep)?>" readonly /></p>
			</div>					

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Estado</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="province" class="form-control" placeholder="Estado" value="<?=($estado)?>" readonly /></p>
			</div>				

			
			
			<input type="hidden" name="token_producao" value="<?=$d->token_producao?>" />
			<input type="hidden" name="CodR" value="<?=$d->id?>" />
			<input type="hidden" name="CodCliente" value="<?=$_SESSION[id_usuario]?>" />
			
		 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

				<button type="submit" name="salvar_<?=$Script?>" class="btn btn-success">Salvar</button>                  
                

		 </div>			
			
	    </div>
	
		</form>	
			
</div>	
</div>	




<script>
$('select[Meubanco]').selectpicker();
	
//opção de salvar
function retornar_<?=$Script?>(cod){

	 $.confirm({
		title: "",
		content: "<b>Dados salvo com sucesso!</b>",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			ok: {
				btnClass: "btn-success",
				action: function(){

					$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
						$.ajax({
						url: './<?=$Conf[script]?>.php',
						success: function(data) {
						    $('#<?=$_SESSION[$Script][dialog]?>').html(data);	
							$("#CARREGANDO").html('');
							$('body>.tooltip').remove();
						}
					}); 					

				}
			},
		}
	  });

}	

	
$('#conta_bancaria').validate({
	rules : {
		name : {
			required : true
		},		
		companyType : {
			required : true
		},
		account : {
			required : true
		},
		accountDigit : {
			required : true
		},
		agency : {
			required : true
		},
		bank : {
			required : true
		},
		bankAccountType : {
			required : true
		}

	},
	messages : {
		name : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o Nome ou Razão social.</div>'
		},
		companyType : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o Tipo da empresa.</div>'
		},
		account : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe Nº Conta.</div>'
		},
		accountDigit : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o Digito.</div>'
		},
		agency : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a Agencia.</div>'
		},
		bank : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o Banco.</div>'
		},
		bankAccountType : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o Tipo da conta.</div>'
		}
	},
submitHandler: function( form ){
	var dados = $( form ).serialize();

		$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
	    $("#conta_bancaria").submit();

	return false;
}

});	

	
	
	$("#companyType").change(function(e){

		let TipoEmpresa = $(this).val();
 
		if(TipoEmpresa=="mei"){
		   $("div[ANOXOS]").css('display','block');	
		   $("div[mei]").css('display','block');
		   $("div[limited]").css('display','none');
		   $("div[individual]").css('display','none');
		   $("div[association]").css('display','none');	
		}
		else if(TipoEmpresa=="limited"){
		   $("div[ANOXOS]").css('display','block');		
		   $("div[mei]").css('display','none');
		   $("div[limited]").css('display','block');
		   $("div[individual]").css('display','none');
		   $("div[association]").css('display','none');	  
		}
		else if(TipoEmpresa=="individual"){
		   $("div[ANOXOS]").css('display','block');		
		   $("div[mei]").css('display','none');
		   $("div[limited]").css('display','none');
		   $("div[individual]").css('display','block');
		   $("div[association]").css('display','none');	 
		}
		else if(TipoEmpresa=="association"){
		   $("div[ANOXOS]").css('display','block');		
		   $("div[mei]").css('display','none');
		   $("div[limited]").css('display','none');
		   $("div[individual]").css('display','none');
		   $("div[association]").css('display','block');	
		}else{
		   $("div[ANOXOS]").css('display','none');
		   $("div[mei]").css('display','none');
		   $("div[limited]").css('display','none');
		   $("div[individual]").css('display','none');
		   $("div[association]").css('display','none');				
		}

	});		
	
	
	
	$("#documentFile_limited_contrato").change(function(e){

		var files = e.target.files;
		let NomeArq = e.target.files[0].name;

		var Com3 = NomeArq.substr(-3);
		var Com4 = NomeArq.substr(-4);
		
		if(Com3 == 'pdf' || Com3 == 'PDF' || Com3 == 'png' || Com3 == 'PNG' || Com3 == 'jpg' ||  Com3 == 'JPG' || Com4 == 'JPEG' || Com4 == 'jpeg'){
			$('#TextContrac').text(NomeArq);
		}else{
			$('#TextContrac').text('Arquivo invalido!');
		}

	});	

	
	$("#documentFile_limited_doc").change(function(e){

		var files = e.target.files;
		let NomeArq = e.target.files[0].name;

		var Com3 = NomeArq.substr(-3);
		var Com4 = NomeArq.substr(-4);
		
		if(Com3 == 'pdf' || Com3 == 'PDF' || Com3 == 'png' || Com3 == 'PNG' || Com3 == 'jpg' ||  Com3 == 'JPG' || Com4 == 'JPEG' || Com4 == 'jpeg'){
			$('#TextCpfCnh').text(NomeArq);
		}else{
			$('#TextCpfCnh').text('Arquivo invalido!');
		}

	});		
	
	$("#documentFile_individual_socio").change(function(e){

		var files = e.target.files;
		let NomeArq = e.target.files[0].name;

		var Com3 = NomeArq.substr(-3);
		var Com4 = NomeArq.substr(-4);
		
		if(Com3 == 'pdf' || Com3 == 'PDF' || Com3 == 'png' || Com3 == 'PNG' || Com3 == 'jpg' ||  Com3 == 'JPG' || Com4 == 'JPEG' || Com4 == 'jpeg'){
			$('#Textind').text(NomeArq);
		}else{
			$('#Textind').text('Arquivo invalido!');
		}

	});	

	
	$("#documentFile_individual_doc").change(function(e){

		var files = e.target.files;
		let NomeArq = e.target.files[0].name;

		var Com3 = NomeArq.substr(-3);
		var Com4 = NomeArq.substr(-4);
		
		if(Com3 == 'pdf' || Com3 == 'PDF' || Com3 == 'png' || Com3 == 'PNG' || Com3 == 'jpg' ||  Com3 == 'JPG' || Com4 == 'JPEG' || Com4 == 'jpeg'){
			$('#TextCpfCnhindi').text(NomeArq);
		}else{
			$('#TextCpfCnhindi').text('Arquivo invalido!');
		}

	});			
	

	
	$("#documentFile_mei_doc").change(function(e){

		var files = e.target.files;
		let NomeArq = e.target.files[0].name;

		var Com3 = NomeArq.substr(-3);
		var Com4 = NomeArq.substr(-4);
		
		if(Com3 == 'pdf' || Com3 == 'PDF' || Com3 == 'png' || Com3 == 'PNG' || Com3 == 'jpg' ||  Com3 == 'JPG' || Com4 == 'JPEG' || Com4 == 'jpeg'){
			$('#TextCpfCnhmei').text(NomeArq);
		}else{
			$('#TextCpfCnhmei').text('Arquivo invalido!');
		}

	});		
	
	
$("#phone").mask("999999999999");
</script>