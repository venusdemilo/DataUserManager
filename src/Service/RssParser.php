<?php
namespace App\Service;

class RssParser
{
  function lit_rss($fichier,$objets) {

  	// on lit tout le fichier
  	if($chaine = @implode("",@file($fichier))) {

  		// on découpe la chaine obtenue en items
  		$tmp = preg_split("/<\/?"."item".">/",$chaine);

  		// pour chaque item
  		for($i=1;$i<sizeof($tmp)-1;$i+=2)

  			// on lit chaque objet de l'item
  			foreach($objets as $objet) {

  				// on découpe la chaine pour obtenir le contenu de l'objet
  				$tmp2 = preg_split("/<\/?".$objet.">/",$tmp[$i]);

  				// on ajoute le contenu de l'objet au tableau resultat
  				$resultat[$i-1][] = @$tmp2[1];
  			}

  		// on retourne le tableau resultat
  		return $resultat;
  	}
  }
}
