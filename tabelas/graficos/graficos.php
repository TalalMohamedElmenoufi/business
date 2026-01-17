<?php
include("../../includes/connect.php");

$Conf[script] = 'tabelas/graficos/graficos';
$Script = md5($Conf[script]);


$Tabela = "resposta_user_bot_".date('Y');
$AnoMes = date('Y-m');


$pergunta = "SELECT participante from resposta_user_bot_".date('Y')."
group by participante 
";
$resultado = mysqli_query($conexao2, $pergunta);
while($d = mysqli_fetch_object($resultado)){
	$QtParticipante += count($d->participante);
}

$pergunta2 = "SELECT id_resposta from resposta_user_bot_".date('Y')."
group by id_resposta 
";
$resultado2 = mysqli_query($conexao2, $pergunta2);
while($d = mysqli_fetch_object($resultado2)){
	$QtResposta += count($d->id_resposta);
}


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


</style>


<div class="card" >
<div class="card-body">
		
		
		<div class="panel-heading">
			<div class="row">
				
				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
					<h2 >Dashboard Pesquisas</h2>
				</div>
				
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
					<button type="button" class="btn btn-gradient-info btn-rounded btn-icon " onClick="dc.filterAll(); dc.renderAll();" data-toggle="tooltip" data-placement="top" title="Atualizar os gráficos">
					<i class="fas fa-redo"></i>
					</button>				
				</div>
				
			</div>
			
		</div>

		<div class="dropdown-divider"></div>
	
		<div class="row">

				<div id="chart-ring-year" class="col-xs-12 col-sm-12 col-md-6 col-lg-6 Grafico1 FundoGrafico1" style="padding:0px;"></div>				

				<div id="Pesquisas" class="col-xs-12 col-sm-12 col-md-6 col-lg-6 Grafico2 FundoGrafico1" style="padding:0px;"></div>

				<div class="dropdown-divider"></div>

				<div id="Participantes" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 Grafico3 FundoGrafico" style="padding:0px;"></div>
				<div id="row-axis" class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

				<div class="dropdown-divider"></div>

				<div id="Perguntas" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 Grafico4 FundoGrafico" style="padding:0px;"></div>
				<div id="row2-axis" class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

				<div class="dropdown-divider"></div>

		</div>
				
	
	   
	
	</div>
</div>	

<div id="CarregarGrafico"></div>

<script >
$('[data-toggle="tooltip"]').tooltip();

	
AtGrafico('<?=$Tabela?>','<?=$AnoMes?>');	

	
function AtGrafico(Tabela,AnoMes){	

$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

$("#CarregarGrafico").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');		
	

$.ajax({
url: './tabelas/graficos/montar.php?Tabela='+Tabela+'&AnoMes='+AnoMes,
success: function(RetornoDados) {

//alert(RetornoDados);	
	
var RetornoDados = eval('('+RetornoDados+')');
var spendData = RetornoDados ;

var yearRingChart   = dc.pieChart("#chart-ring-year"),
	Pesquisas   	= dc.pieChart("#Pesquisas"),
    Participantes   = dc.rowChart("#Participantes"),
    Perguntas       = dc.rowChart("#Perguntas")
;

	
// normalize/parse data
spendData.forEach(function(d) {
     d.Year = d.Year.match(/\d+/);

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
spendPerYear = yearDim.group().reduceCount(function(d) {return +d.participante;}),
YearHist = remove_empty_bins(spendPerYear),	
	
PesquisasDim  = ndx.dimension(function(d) {return d.pesquisa;}),	
spendPesquisas = PesquisasDim.group().reduceCount(function(d) {return +d.participante;}),	
PesquisasHist = remove_empty_bins(spendPesquisas),	
	
ParticipanteDim  = ndx.dimension(function(d) {return +d.participante;}),		
GroupParticipante = ParticipanteDim.group().reduceCount(function(d) {return +d.participante;}),
ParticipanteHist = remove_empty_bins(GroupParticipante),	
	
PerguntasDim   = ndx.dimension(function(d) {return d.pergunta;}),
GroupPerguntas = PerguntasDim.group().reduceCount(function(d) {return +d.participante;}),
PerguntasHist = remove_empty_bins(GroupPerguntas)

;



var fudge = 50;
	
	
function GerarGrafico(){

let QtParticipante = "<?=$QtParticipante?>";		
let QtResposta = "<?=$QtResposta?>";
	
	yearRingChart
		.dimension(yearDim)
		.group(YearHist)
		.ordinalColors(['#7ED56D','#BA75E8','#D164CA','#A8B958','#B0874B','#77D0CC','#78ACB4' ])
		.innerRadius(50)
		.width($("#Grafico1").innerWidth())
		.height(window.innerHeight/2-fudge);

	
	Pesquisas
		.dimension(PesquisasDim)
		.group(PesquisasHist)
		.ordinalColors(['#78ACB4','#77D0CC','#B0874B','#A8B958','#D164CA','#BA75E8','#7ED56D' ])
		.innerRadius(50)
		.width($("#Grafico2").innerWidth())
		.height(window.innerHeight/2-fudge);	
	
	
	
	Participantes
	    .margins({ left: 20, top: 0, right: 0, bottom:0 })
		.dimension(ParticipanteDim)
		.group(ParticipanteHist)
        .title(function (d) {
              return  "Cel:"+d.key +"=> "+ d.value + " ALL "+ndx.groupAll().reduceCount().value() ;
        })	
		.ordinalColors(['#7ED56D', '#BA75E8', '#77D0CC', '#78ACB4', '#B0874B', '#A8B958', '#D164CA'])
		.elasticX(true)
		.width($("#Grafico3").innerWidth())
		.height( 35 * QtParticipante )

	    ;
      dc.axisChart('#row-axis')
		.margins({ left: 5, top: 2, right: 30, bottom: 10 })
		.dimension(ParticipanteDim)
		.group(ParticipanteHist)
		.label(function(d){return d.key})
		.width($("#Grafico3").innerWidth())
		.height( 50 )	
		;
	

	Perguntas
	    .margins({ left: 20, top: 0, right: 0, bottom:0 })
		.dimension(PerguntasDim)
		.group(PerguntasHist)
        .title(function (d) {
           return  d.key +": "+ d.value ;
        })	
		.ordinalColors(['#77D0CC', '#78ACB4', '#BA75E8', '#B0874B', '#D164CA', '#7ED56D', '#A8B958'])
		.elasticX(true)
		.width($("#Grafico4").innerWidth())
		.height( 35 * QtResposta )
		//.height( (window.innerHeight/2-fudge) * 2 )
	    ;	
      dc.axisChart('#row2-axis')
		.margins({ left: 5, top: 2, right: 30, bottom: 10 })
		.dimension(ParticipanteDim)
		.group(PerguntasHist)
		.label(function(d){return d.key})
		.width($("#Grafico4").innerWidth())
		.height( 50 )	
		;


	dc.renderAll();	

}
	

	GerarGrafico();

	
	$('body>.tooltip').remove();
	
	$("#CARREGANDO").html('');
	$("#CarregarGrafico").html('');		
	
}
});		

}
	
	

	
</script>
