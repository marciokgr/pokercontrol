var tempo;
var parou;

function cronInicia() {
	$("lnkCronIniciar").style.display = 'none';
	$("lnkCronParar").style.display = '';
	
	tempo = $("selTempo").value;
	parou = false;
	
	cronometro();
}

function cronPara() {
	$("lnkCronIniciar").style.display = '';
	$("lnkCronParar").style.display = 'none';
	
	tempo = 0;
	parou = true;
	
	cronometro();
}


function cronometro() {
	var ss = tempo;
	var mm = parseInt(ss / 60);
	
	ss = ss - (mm * 60);
					
	if (mm < 10)
		mm = '0' + mm;
	
	if (ss < 10)
		ss = '0' + ss;
	
	document.getElementById('divTempo').innerHTML = mm + ':' + ss;
	
	if (tempo > 0) {
		tempo--;
		setTimeout("cronometro()", 1000);
	}
	else if (!parou) {
		alarme();
		
		$("lnkCronIniciar").style.display = '';
		$("lnkCronParar").style.display = 'none';
	}
}

function alarme() {
	document.getElementById('divAlarme').innerHTML = "<embed type='application/x-mplayer2' src='alarme.mp3' height='0' width='0' autostart='1'";
}