<?php
$colors = array('#CCCCCC ','#0000FF ','#CC8CCC ','#0000FF ','#CCC5CC ','#0000FF ','#666666 ');
echo ('<h1>Affichage des couleurs</h1>');
foreach ($colors as $color ) {
	echo'<div style="color: '.$color.'">'.$color.'</div>';
}
$days_meteo = array(
    'lundi' => array('pluvieux', '#CCCCCC '),
    'mardi' => array('ensoleillé', '#0000FF '),
    'mercredi' => array('pluvieux', '#CCCCCC '),
    'jeudi' => array('ensoleillé', '#0000FF '),
    'vendredi' => array('pluvieux', '#CCCCCC '),
    'samedi' => array('ensoleillé', '#0000FF '),
    'dimanche' => array('orageux', '#666666 ')
);
	echo ('<h1>Affichage des jours</h1>');
	foreach ($days_meteo as $jour => $temps) {
		echo($jour.'<br>');
	}

	echo ('<h1>Affichage des jours & le temps</h1>');
	echo ('<p>version avec concatenation</p>');

	foreach ($days_meteo as $jour => $temps) {
		$meteo = $temps[0];
		$color = $temps[1];
		print_r ('<div style="color: '.$color.'">'.$jour.' : '.$temps.'</div>'.PHP_EOL);

	}