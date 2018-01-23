<?php

namespace App\Http\Controllers;
use DB;
use App\Guests;
use App\Reservation;
use App\SendingMail;
use App\Http\Controllers\PusherController;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
	public function __construct()
	{
        $this->middleware('jwt.auth');
    }

    public function index()
    {
    	$rezervacije = Reservation::select('guests.*','reservations.*')
                                    ->leftJoin('guests', 'reservations.guest_id', '=', 'guests.id')
                                    ->orderBy('dolazak', 'asc')
                                    ->where('status', 'Aktivno')
                                    ->get();

    	return $rezervacije;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $guest = new Guests;

        $guest->guest = $request->gost;
        $guest->zemlja = $request->zemlja;
        $guest->tel_broj = $request->kontakt;
        $guest->email = $request->email;

        if($guest->save())
        {
            $guest_id = $guest->id;
            $reservation = new Reservation;

            $reservation->apartman = $request->apartman;
            $reservation->dolazak = $request->dolazak;
            $reservation->odlazak = $request->odlazak;
            $reservation->rez_preko = $request->rez_preko;
            $reservation->cijena = $request->cijena;
            $reservation->provizija = $request->provizija;
            $reservation->pdv = $request->pdv;
            $reservation->no_guests = $request->brOsoba;
            $reservation->napomena = $request->napomena;
            $reservation->color = $request->color;
            $reservation->guest_id = $guest_id;
            $reservation->status = "Aktivno";


            if($reservation->save()) 
            {
                $salji = new SendingMail;
                $salji->send('Nova', $request->dolazak, $request->odlazak,$request->cijena, $request->rez_preko, $request->gost );

                $controller = new PusherController;
                $controller->sendNotification();

                return "Success";
            }
            return json(['error' => 'could_not_create_reservation'], 406);
        }
        else
        {
            return json(['error' => 'could_not_create_guest'], 401);
        }
    }



     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWithoutGuest(Request $request)
    {

            $reservation = new Reservation;

            $reservation->apartman = $request->apartman;
            $reservation->dolazak = $request->dolazak;
            $reservation->odlazak = $request->odlazak;
            $reservation->rez_preko = $request->rez_preko;
            $reservation->cijena = $request->cijena;
            $reservation->provizija = $request->provizija;
            $reservation->pdv = $request->pdv;
            $reservation->no_guests = $request->brOsoba;
            $reservation->napomena = $request->napomena;
            $reservation->color = $request->color;
            $reservation->guest_id = $request->guest_id;
            $reservation->status = "Aktivno";


            if($reservation->save()) 
            {
                $salji = new SendingMail;
                $salji->send('Nova', $request->dolazak, $request->odlazak,$request->cijena, 
                $request->rez_preko, Guests::find($request->guest_id)->guest  );

                $controller = new PusherController;
                $controller->sendNotification();

                return "Success";
            };
            return json(['error' => 'could_not_create_reservation'], 500);
    }




     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {        
        $reservation = Reservation::find($id);

        $reservation->apartman = $request->apartman;
        $reservation->dolazak = $request->dolazak;
        $reservation->odlazak = $request->odlazak;
        $reservation->rez_preko = $request->rez_preko;
        $reservation->cijena = $request->cijena;
        $reservation->provizija = $request->provizija;
        $reservation->pdv = $request->pdv;
        $reservation->no_guests = $request->no_guests;
        $reservation->napomena = $request->napomena;
        $reservation->color = $request->color;
        $reservation->status = $request->status;

        if($reservation->save())
        {
            $salji = new SendingMail;

            ($request->status == 'Otkazano') ? $salji->send('Otkazano', 
                $request->dolazak, 
                $request->odlazak,  
                $request->cijena, 
                $request->rez_preko,
                Guests::find(Reservation::find($id)->guest_id)->guest ) : null;

                $controller = new PusherController;
                $controller->sendNotification();
        }
    }


    public function getRezByYear($year)
    {
        return Reservation::whereYear('dolazak', $year)->get();
    }

}
