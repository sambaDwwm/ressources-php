<?php

/***************************************
style="background: #ff0000;"
*
* $m = mois
* $y = année
*
****************************************/

function calendar($m, $y, $idMedecinPost)
{
	$sem = array(6,0,1,2,3,4,5); // Correspondance des jours de la semaine : lundi = 0, dimanche = 6
	$mois = array('','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août'
		,'Septembre','Octobre','Novembre','Décembre');
	$week = array('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche');
	$t = mktime(12, 0, 0, $m, 1, $y); // Timestamp du premier jour du mois

	
	echo '<table><tbody>';
	echo '<form method="post" action="index.php?action=reserver">';
	// Le mois
	//--------
	echo '<tr><td colspan="7"><span class="button is-large is-fullwidth is-primary is-inverted">'
			.$mois[$m].
		'</span></td></tr>';

	// Les jours de la semaine
	//------------------------
	echo '<tr>';
	for ($i = 0 ; $i < 7 ; $i++)
	{
		echo '<td><span class="button is-fullwidth is-primary">'.$week[$i].'</span></td>';
	}
	echo '</tr>';

	// Le calendrier
	//--------------
	for ($l = 0 ; $l < 6 ; $l++) // calendrier sur 6 lignes
	{
		echo '<tr>';
		for ($i = 0 ; $i < 7 ; $i++) // 7 jours de la semaine
		{
			$w = $sem[(int)date('w',$t)]; // Jour de la semaine à traiter
			$m2 = (int)date('n',$t); // Tant que le mois reste celui du départ
			if (($w == $i) && ($m2 == $m)) // Si le jours de semaine et le mois correspondent
			{
				if ($w == 6)
				{
					$dataJour = date('j',$t);
					echo '<td><center>'.$dataJour.'</center><div class="select is-primary">
						<fieldset>
  								<select name="horaireReservation'.$dataJour.'">
  									<option value="Indisponible" >Indisponible</option>
  								</select>
  						</fieldset>
								</div> </td>'; // Affiche le jour du mois
					$t += 86400; // Passe au jour suivant
				}
				elseif ($w == 5) {
					$dataJour = date('j',$t);
					echo '<td><center>'.$dataJour.'</center><div class="select is-primary">
  								<select name="horaireReservation'.$dataJour.'">
  									<option>Choisir l heure</option>
    								<option value="10h00 - 10h30">10h00 - 10h30</option>
    								<option value="10h30 - 11h00">10h30 - 11h00</option>
    								<option value="11h30 - 12h00">11h30 - 12h00</option>
    								<option value="12h00 - 12h30">12h00 - 12h30</option>
  								</select>
  								<input type="hidden" name="jourR'.$dataJour.'" value="'.$dataJour.'">
								</div> </td>'; // Affiche le jour du mois
					$t += 86400; // Passe au jour suivant
				}
				else
				{
					$dataJour = date('j',$t);
					echo '<td><center>'.$dataJour.'</center><div class="select is-primary">
  								<select name="horaireReservation'.$dataJour.'">
  									<option>Choisir l heure</option>
    								<option value="10h00 - 10h30">10h00 - 10h30</option>
    								<option value="10h30 - 11h00">10h30 - 11h00</option>
    								<option value="11h30 - 12h00">11h30 - 12h00</option>
    								<option value="13h00 - 13h30">13h00 - 13h30</option>
    								<option value="13h30 - 14h00">13h30 - 14h00</option>
    								<option value="14h30 - 15h00">14h30 - 15h00</option>
    								<option value="15h00 - 15h30">15h00 - 15h30</option>
    								<option value="15h30 - 16h00">15h30 - 16h00</option>
  								</select>
  								<input type="hidden" name="jourR'.$dataJour.'" value="'.$dataJour.'">
								</div> </td>'; // Affiche le jour du mois
					$t += 86400; // Passe au jour suivant
				}
				
			}
			else
			{
				echo '<td>&nbsp;</td>'; // Case vide
			}
		}
		echo '</tr>';
	}
	echo '<tr><td colspan="7"><button type="submit" class="button is-large is-fullwidth is-primary is-outlined">Réserver à cette date</button></td></tr>';
	echo '<input type="hidden" name="mois" value="'.$m.'">';
	echo '<input type="hidden" name="annee" value="'.$y.'">';
	echo '<input type="hidden" name="idMedecin" value="'.$idMedecinPost.'">';
	echo '</form>';
	echo '</tbody></table>';
	
}
