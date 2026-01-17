<?php
include("../../includes/connect.php");

$Conf[script] = 'tabelas/graficos_sms/graficos_sms';
$Script = md5($Conf[script]);


if(!$_SESSION[$Script][MesAno]){$_SESSION[$Script][MesAno] = $_GET[MesAno];}
if($_GET[MesAno]){$_SESSION[$Script][MesAno] = $_GET[MesAno];}

if(!$_SESSION[$Script][Mes_Ano]){$_SESSION[$Script][Mes_Ano] = $_GET[Mes_Ano];}
if($_GET[Mes_Ano]){$_SESSION[$Script][Mes_Ano] = $_GET[Mes_Ano];}


if($_SESSION[$Script][MesAno]){
	$dataTabela = $_SESSION[$Script][MesAno];
}else{
	$dataTabela = date('mY');
}

if($_SESSION[$Script][Mes_Ano]){
	$dataSel01 = $_SESSION[$Script][Mes_Ano];
}else{
	$dataSel01 = date('Y-m');
}


if($_SESSION[$Script][AnoSelect]){
	$AnoSelect = $_SESSION[$Script][AnoSelect];
}else{
	$AnoSelect = date('Y');
}

list($creditoSms) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_sms from usuarios where id = '".$_SESSION[id_usuario]."' "));

list($smsUso) = mysqli_fetch_row(mysqli_query($conexao2, "select count(codigo) from t_".$dataTabela."_smsStatuses where mes_ano = '".$dataTabela."' "));

?>

<style>
	
.FundoGrafico1{

	height:400px;
	overflow-y: auto;

}
	
.FundoGrafico{

	height:300px;
	overflow-y: auto;

}
	
text{
color:#000 !important;
fill:#000 !important; 

}	


	div[credito]{
		float: right;
	}	
	
	span[creditoDesc]{
		font-size:15px;
		font-family: antic;
	}	
	
	span[creditoQnt]{
		font-size:30px;
	}	
	
	span[infoSmsDesc]{
		font-size:15px;
		font-family: antic;
	}	
	
	span[infoSmsQnt]{
		font-size:20px;
	}	
</style>


<div class="card" >
<div class="card-body">
		
		
		<div class="panel-heading">
			<div class="row">
				
				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
					<h2 >Dashboard SMS</h2>
				</div>
				
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
					<button type="button" class="btn btn-gradient-info btn-rounded btn-icon " onClick="dc.filterAll(); dc.renderAll();" data-toggle="tooltip" data-placement="top" title="Atualizar os gráficos">
					<i class="fas fa-redo"></i>
					</button>				
				</div>
				
				
				
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-2" style="padding:0px; margin-top:4px;">
	
	<i class="far fa-copyright Icons"></i>
	<select InputForm class="form-control" id="AnoSelect">
	<option InputForm value="">Ano</option>		
	
	<?php
	function newYear($dataBase, $return, $incremento) {
		$novaData = strtotime("$incremento", strtotime($dataBase));
		return date("$return", $novaData);
	}

	$dtInicio = date('Y-m-d', strtotime('-5 year')) ;
	$dtFim = date('Y-m-d', strtotime('+5 year'))  ;

	while ($dtInicio < $dtFim) {
		$dtInicio = newYear($dtInicio, "Y-m-d", "+1 year");
		
		$dataTrat = explode("-",$dtInicio);
		?>

		<option InputForm value="<?=$dataTrat[0]?>" <?=(($dataTrat[0]==$AnoSelect)?'selected':false)?>  ><?=$dataTrat[0]?> </option>
		
	<?php
	}
	?>
	</select> 	
</div>	

<div class="dropdown-divider" ></div> 				

<div class="col-xs-12 col-sm-12 col-md-3 col-lg-2" style="padding:0px; margin-top:10px;">

	<i class="far fa-copyright Icons"></i>
	<select InputForm class="form-control" id="MesAno">
	<option InputForm value="">Mês Ano</option>	

	<?php
	for ($x = 1; $x <= 12; $x++) {

	if($x < 10){
		$x = '0'.$x;
	}else{
		$x;
	}	

	$mesAno = $x.'-'.$AnoSelect;	
		
	$mAno = $x.''.$AnoSelect;
		
	$dataSel = $AnoSelect.'-'.$x;	
		
	$MY = $x.''.$AnoSelect;	
		
	?>
	<option InputForm value="<?=$mAno?>|<?=$dataSel?>|<?=$MY?>" <?=(($dataSel==$dataSel01)?'selected':false)?>  ><?=$x.'/'.$AnoSelect?> </option>
	<?php
	}
	?>

	</select> 
</div>
	
	

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:0px;">

	<div credito>
		<span creditoDesc>Seus créditos</span><br>
		<span creditoQnt><?=$creditoSms?></span>	
	</div>	

	<?php
	$MostraData = explode('-',$dataSel01);
	?>
	<div infoSms>
		<span infoSmsDesc>Mensagens utilizadas em <?=$MostraData[1]?> <?=$MostraData[0]?></span><br>
		<span infoSmsQnt><?=$smsUso?></span>
	</div>	
	
<hr>	
	
</div>				
				
				
			</div>
			
		</div>

		<div class="dropdown-divider"></div>
	
		<div class="row">

			<div id="chart-ring-year" class="col-xs-12 col-sm-12 col-md-6 col-lg-6 FundoGrafico1 Grafico1" style="padding:1px; overflow-y:hidden;"></div>
			<div class="dropdown-divider"></div>

			<div id="chart-row-spenders" class="col-xs-12 col-sm-12 col-md-6 col-lg-6  FundoGrafico Grafico2" style="padding:1px; overflow-y:hidden;"></div> 
			<div class="dropdown-divider"></div>

			<div id="MesBar" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 FundoGrafico Grafico3" style=" overflow-y:hidden;" ></div>
			<div class="dropdown-divider"></div>

			<div id="Dia" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 FundoGrafico Grafico4" style=" overflow-y:hidden;" ></div>			
			<div class="dropdown-divider"></div>		

		</div>
				
	
	   
	
	</div>
</div>	


<div id="checarQ"></div>

<div id="CarregarGrafico"></div>
<script >
$('[data-toggle="tooltip"]').tooltip();

	
	
function AtGrafico(MesAno,Mes_Ano){	

	
//alert(MesAno+' '+Mes_Ano);
$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

$("#CarregarGrafico").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	
	
$.ajax({
url: './tabelas/graficos_sms/montar.php?MesAno='+MesAno+'&Mes_Ano='+Mes_Ano,
success: function(RetornaVel) {
	
	
	
	var RetornaVel = eval('('+RetornaVel+')');
	var spendData = RetornaVel ;		
	
	
	
var yearRingChart   = dc.pieChart("#chart-ring-year"),
    spenderRowChart = dc.rowChart("#chart-row-spenders"),
	Mes = dc.rowChart("#Mes"),
	Dia = dc.barChart("#Dia"),

	MesBar = dc.barChart("#MesBar")
;
	
/*var spendData = [
	{Name: 'Enviado',     mes:'01/2015', dia:'01-2015', Envios: '1480', Year: 2015},
	{Name: 'Enviado',     mes:'01/2015', dia:'02-2015', Envios: '1480', Year: 2015},
	{Name: 'Enviado',     mes:'01/2015', dia:'03-2015', Envios: '1480', Year: 2015},
];*/

// normalize/parse data
spendData.forEach(function(d) {
    d.Envios = d.Envios.match(/\d+/);
});

function remove_empty_bins(source_group) {
    return {
        all:function () {
            return source_group.all().filter(function(d) {
                return d.value != 0;
            });
        }
    };
}

// set crossfilter
var ndx = crossfilter(spendData),
    yearDim  = ndx.dimension(function(d) {return +d.Year;}),
    spendDim = ndx.dimension(function(d) {return Math.floor(d.Envios/10);}),
    nameDim  = ndx.dimension(function(d) {return d.Name;}),
	
	mesDim   = ndx.dimension(function(d) {return d.mes;}),
	diaDim   = ndx.dimension(function(d) {return d.dia ;}),
	
	MesBarDim   = ndx.dimension(function(d) {return d.mes ;}),
	
	
    spendPerYear = yearDim.group().reduceSum(function(d) {return +d.Envios;}),
    spendPerName = nameDim.group().reduceSum(function(d) {return +d.Envios;}),
	
	spendPermes = mesDim.group().reduceSum(function(d) {return +d.Envios;}),
	spendPerdia = diaDim.group().reduceSum(function(d) {return +d.Envios;}),
	
	spendPerMesBar = MesBarDim.group().reduceSum(function(d) {return +d.Envios;}),
	
	
    spendHist    = spendDim.group().reduceCount(),
	
	spendDiaHist = remove_empty_bins(spendPerdia),
	
	spendMesBarHist = remove_empty_bins(spendPerMesBar),
	
    nonEmptyHist = remove_empty_bins(spendHist);
	

	var fudge = 50;
	
	
function GerarGrafico(){
	
	yearRingChart
		.dimension(yearDim)
		.group(spendPerYear)
		.ordinalColors(['#429735', '#282182', '#8B2E30', '#A47A20', '#285A09', '#6B1EC0', '#259C97', '#335537', '#D9D836', '#135962', '#6F27AD', '#AB20A1'])
		.innerRadius(50)
			.width($("#Grafico1").innerWidth())
			.height(window.innerHeight/2-fudge);	


	
	
	spenderRowChart
		.dimension(nameDim)
		.group(spendPerName)
		.ordinalColors(['#25C092','red'])
		.elasticX(true)
			.width($("#Grafico2").innerWidth())
			.height(window.innerHeight/2-fudge);

		
	
	MesBar
		.dimension(MesBarDim)
		.group(spendMesBarHist)
		/*.yAxisLabel('Meses do ano')
		.xAxisLabel('Meses do ano')*/
        .title(function (d) {
			return 'Mes '+d.key +' => '+ d.value+' mesagens';
        })		
		.x(d3.scaleBand())
		.xUnits(dc.units.ordinal)
		.ordinalColors(['#8C4A1C'])
		.renderLabel(false)
		.elasticX(true)
		.elasticY(true)
	
			.width($("#Grafico3").innerWidth())
			.height(window.innerHeight/2-fudge);
	
	
	
	Dia
		.dimension(diaDim)
		.group(spendDiaHist)
		/*.yAxisLabel('Dias do ano')
		.xAxisLabel('Dia do ano')*/
        .title(function (d) {
			return 'Dia '+d.key +' => '+ d.value+' mesagens';
        })	
	
		.x(d3.scaleBand())
		.xUnits(dc.units.ordinal)
		.renderLabel(false)
		.elasticX(true)
		.elasticY(true)
			.width($("#Grafico4").innerWidth())
			.height(window.innerHeight/2-fudge);	
	
	
	
	
    Mes 
        .group(spendPermes)
        .dimension(mesDim)
        //.ordinalColors(['#3182bd', '#6baed6', '#9ecae1', '#c6dbef', '#dadaeb'])
		.label(function (d) { 
		
			if(d.value > 0){
				return d.key; 
			}
		      
		
		})
        .title(function (d) {
			return d.value+' mesagens';
        })
	
			.width($("#Grafico5").innerWidth())
			.height(window.innerHeight/2-fudge);


	dc.renderAll();	
	


}
	

	
	GerarGrafico();
	$('body>.tooltip').remove();
	$("#CARREGANDO").html('');
	$("#CarregarGrafico").html('');	
}
});		

}

	
AtGrafico('<?=$dataTabela?>','<?=$dataSel01?>');	
	

	$('#MesAno').change(function(){

		var MesAno = $(this).val();
		
		var TmesAno = MesAno.split('|');

		var DiaMes = TmesAno[1].split('-');
		var DiaMes1 = DiaMes[1]+' '+DiaMes[0];
		
		$.ajax({
		url: './acoes/checar_quantidade.php?MesAno='+TmesAno[2]+'&DiaMes1='+DiaMes1,
		success: function(Retorna) {
			$('#checarQ').html(Retorna);
			AtGrafico(TmesAno[0],TmesAno[1]);
		}	
		});		

	});	
	
	
	
	
	$('#AnoSelect').change(function(){

		var AnoSelect = $(this).val();
		//alert(AnoSelect);
		$.ajax({
		url: './acoes/atualiza_select.php?AnoSelect='+AnoSelect,
		success: function(RetornaSelect) {
			$('#MesAno').html(RetornaSelect);

		}	
		});		

	});		
	

	
	function VoltarChequeQ(Q,DM){		
		$('span[infoSmsDesc]').text('Mensagens utilizadas em '+DM);
		$('span[infoSmsQnt]').text(Q);
	}	
</script>
