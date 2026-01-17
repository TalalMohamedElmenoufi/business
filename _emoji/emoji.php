<?php
include("../includes/connect.php");	

$Conf[script] = 'tabelas/bot_config_whats/bot_config_whats';
$Script = md5($Conf[script]);

if(!$_SESSION[$Script][Cod]){$_SESSION[$Script][Cod] = $_GET[Cod];}
if($_GET[Cod]){$_SESSION[$Script][Cod] = $_GET[Cod];}

if(!$_SESSION[$Script][Campo]){$_SESSION[$Script][Campo] = $_GET[Campo];}
if($_GET[Campo]){$_SESSION[$Script][Campo] = $_GET[Campo];}
$campo = $_SESSION[$Script][Campo];


list($minId) = mysqli_fetch_row(mysqli_query($conexao, "select MIN(id) from emojis "));

$id = $_SESSION[$Script][Cod];

$idVez = (($id)?$id:$minId);

$pergunta1 = "select * from emojis ";
$resultado1 = mysqli_query($conexao,$pergunta1);

$pergunta2 = "select * from emojis where id = '$idVez' ";
$resultado2 = mysqli_query($conexao,$pergunta2);


?>

<style>
	.EmojiCss{
		border: #FF0004 solid 0px;
		font-size:36px;
		float:left;
		padding:8px;
		cursor:pointer;
	}

	.BarraRolagem{
		overflow:scroll;
		height:250px;
		border:#000 solid 1px;
	}
	
#myMenuEmoji a.active{ 
	border: #000000 solid 1px !important; 
}
</style>


  <ul class="nav nav-tabs" id="myMenuEmoji" role="tablist">
	  
	<?php
	while($m = mysqli_fetch_object($resultado1)){
	?>
	
	<li class="nav-item waves-effect waves-light">
	  <a class="nav-link <?=(($idVez==$m->id)?'active':false)?>" Cod="<?=$m->id?>" id="<?=$m->id?>-tab" data-toggle="tab" href="#<?=$m->id?>" role="tab" aria-controls="<?=$m->id?>" aria-selected="false" onclick="MenuEmoji( this.id )"><?=($m->menu)?></a>
	</li>	  

	<?php	
	} 
	?>

  </ul>	


  <div class="tab-content" id="myEmojis">

	<?php
	while($e = mysqli_fetch_object($resultado2)){
	?>

	  <div class="BarraRolagem tab-pane fade <?=(($idVez==$e->id)?'active show':false)?>" id="<?=$e->id?>" role="tabpanel" aria-labelledby="<?=$e->id?>-tab">

		  
		  <?php
			$jsonEmijis = json_decode($e->emoji);
			$ListaEmojis = $jsonEmijis->Dados;
			foreach ( $ListaEmojis as $em ){

				echo '<div class="EmojiCss" onclick="InsertEmoji( this.id )" id="'.$campo.'|'.$em->emoji.'" >'.$em->emoji.'</div> '; 

			}
		  ?>

 
	  </div>	  

	<?php	
	} 
	?>

  </div>
