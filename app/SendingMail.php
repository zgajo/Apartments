<?php

namespace App;
use Mail;
use Illuminate\Database\Eloquent\Model;

class SendingMail extends Model
{
    public function send($akcija, $dolazak, $odlazak, $cijena, $stranica, $gost){

    	if($akcija == "Otkazano"){

    		$subject = "Otkazana rezervacija";
    		$bodyTitle = "Podaci otkazane rezervacije";
    		$bodyContent = 	'<h2>'.$bodyTitle.'</h2><br>'.
    						'<h4 style="display:inline">Gost: </h4> <p style="display:inline">'.$gost.'</p><br>'.
						    '<h4 style="display:inline">Dolazak: </h4> <p style="display:inline">'.date('d.m.Y', strtotime($dolazak)).'</p><br>'.
						    '<h4 style="display:inline">Odlazak: </h4> <p style="display:inline">'.date('d.m.Y', strtotime($odlazak)).'</p><br>'.
						    '<h4 style="display:inline">Cijena: </h4> <p style="display:inline">'.$cijena.' €</p><br>'.
						    '<h4 style="display:inline">Rezervirano preko: </h4> <p style="display:inline">'.$stranica.'</p>';
    	}
    	elseif($akcija == "Nova"){
    		$subject = "Upisana nova rezervacija";
    		$bodyTitle = "Podaci rezervacije";
    		$bodyContent = 	'<h2>'.$bodyTitle.'</h2><br>'.
    						'<h4 style="display:inline">Gost: </h4> <p style="display:inline">'.$gost.'</p><br>'.
						    '<h4 style="display:inline">Dolazak: </h4> <p style="display:inline">'.date('d.m.Y', strtotime($dolazak)).'</p><br>'.
						    '<h4 style="display:inline">Odlazak: </h4> <p style="display:inline">'.date('d.m.Y', strtotime($odlazak)).'</p><br>'.
						    '<h4 style="display:inline">Cijena: </h4> <p style="display:inline">'.$cijena.' €</p><br>'.
						    '<h4 style="display:inline">Rezervirano preko: </h4> <p style="display:inline">'.$stranica.'</p>';

    	}


    	Mail::send([], ['subject' => $subject, 'bodyContent' => $bodyContent], function ($message) use ($subject, $bodyContent) {
		  	$message->to(["darkopranjic.4@gmail.com", "tonipranjic8@gmail.com", "anapranjich@gmail.com" , "josippranjic61@gmail.com"])
		  			//->to(["darkopranjic.4@gmail.com"])
		  			->from("pranjic.apartmani@gmail.com", "Apartmani Web stranica")
		    		->subject($subject)
				    ->setBody($bodyContent , 'text/html'); // for HTML rich messages
		});
    }
}
