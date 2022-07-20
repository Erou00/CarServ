<?php

namespace App\Http\Controllers\Client;

use App\Events\PrivateMessage;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class ChatController extends Controller
{
    //

    public function chatPage(Request $request)
    {
        # code...
        $id = $request->car_id;
        //dd($id);
        $cars = null;
        $cars =  Car::whereHas('messages' ,function($q) {
            $q->where('from_user_id', Auth()->user()->id)
              ->orWhere('to_user_id', Auth()->user()->id);

       })
        ->with('user')->with('marque')->with('model')->with('messages')->get();


        $collection = $cars->map(function ($car) {
            return $car->id;
        });


       if ($cars->count() == 0 || !$collection->contains($id) ) {
        # code...
           if ($id != null) {
            # code...
            $car = Car::find($id);
             Message::create([
                'from_user_id' => Auth()->user()->id,
                'to_user_id' => $car->user_id ,
                'message' => 'Hi im '.Auth()->user()->first_name.' '.Auth()->user()->last_name,
                'car_id'=>  $car->id
             ]);

             $cars = Car::whereHas('messages' ,function($q) {
                $q->where('from_user_id', Auth()->user()->id)
                  ->orWhere('to_user_id', Auth()->user()->id);

              })->with('user')->with('marque')->with('model')->with('messages')->get();

           }
       }

        return view('client.chat.index')->with('cars',$cars);
    }

    public function privateUserMessages($id,$carId)
    {
        $user = User::find($id);
       // dd(auth()->id());
        $privateCommunication= Message::with('user')
        ->with('car')
        ->where('car_id',$carId)
        ->where(function($query) use ($user){
            $query->where(['from_user_id'=> auth()->id(),'to_user_id' => $user->id])
                  ->orWhere(['from_user_id'=> $user->id,'to_user_id' => auth()->id()]);

        })
        ->get();


       //dd($privateCommunication);

        return $privateCommunication;
    }

    public function sendPrivateMessage(Request $request,$carId)
    {


        $to_user_id = 0;

        foreach ($request->car as  $value) {
            # code...
            if ($value['from_user_id'] != Auth()->user()->id && $value['from_user_id'] != 0 ) {
                # code...
                $to_user_id = $value['from_user_id'];

            } elseif ($value['to_user_id'] != Auth()->user()->id && $value['to_user_id'] != 0 ) {
                # code...
                $to_user_id = $value['to_user_id'];
            }
             break;
        }

            $message=Message::create([
                'from_user_id' => Auth()->user()->id,
                'to_user_id' => $to_user_id,
                'message' => $request->message,
                'car_id'=> $carId

            ]);




        broadcast(new PrivateMessage($message->load('user')))->toOthers();

        return response(['status'=>'Message private sent successfully','message'=>$message]);

    }

    public function sendPrivateMessageFromClient(Request $request,$to,$carId)
    {

            $message=Message::create([
                'from_user_id' => Auth()->user()->id,
                'to_user_id' => $to,
                'content' => $request->content,
                'vidange_id'=> $carId
            ]);


        broadcast(new PrivateMessage($message->load('user')))->toOthers();

        return response(['status'=>'Message private sent successfully','message'=>$message]);

    }


}
