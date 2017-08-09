<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
	protected $casts = [
        'apartman' => 'integer',
        'no_guests' => 'integer',
        'pdv' => 'float',
        'provizija' => 'float',
        'cijena' => 'float',
    ];
	public $timestamps = false;
	
    public function guest()
    {
    	return $this->belongsTo('App\Guests');
    }
    public function getAll()
    {
    	return  DB::table('reservations')->join('guests', 'guests.id', '=', 'reservations.guest_id')->get();
    }
}
