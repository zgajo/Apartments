<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;

class PusherController extends Controller
{

    public function sendNotification()
    {
        //Remember to change this with your cluster name.
        $options = array(
            'cluster' => 'eu'
        );

       //Remember to set your credentials below.
       $pusher = new Pusher(
        '8d75855e8041f4e13e10',
        '2591d98bea7641781f52',
        '461654',
        $options
      );
        
      
        $data['message'] = 'Reservations updated';
        $pusher->trigger('my-channel', 'my-event', $data);
        dd($pusher);
		
    }
}