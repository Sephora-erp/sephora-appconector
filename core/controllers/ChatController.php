<?php

namespace App\modules\basicapp\core\controllers;

use App\Http\Controllers\Controller;
use App\modules\customers\core\models\Customer;
use App\modules\basicapp\core\models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\TriggerHelper;
use DB;

class ChatController extends Controller {
    /*
     * Loads / renders the basic chat view
     */

    public function actionIndex() {
        view()->addLocation(app_path() . '/modules/basicapp/core/views');
        return view('index');
    }

    /*
     * This function returns the different threads for the chat
     */

    public function ajaxGetThread() {
        //Check if any thread is older than today
        $this->purgeOldThreads();
        $threads = DB::table('chat')->select('sender')->orderBy('created_at','desc')->distinct()->get();
        print_r(json_encode($threads));
    }

    /*
     * This function will purge the old threads
     */
    public function purgeOldThreads()
    {
        //Get threads > 1 day
        $threads = Chat::whereRaw('DATE(created_at) < "'.date('Y-m-d').'"')->get();
        foreach($threads as $thread){
            $thread->delete();
        }
    }
    /*
     * This function returns how many un-readen essages are for a sender
     */

    public function ajaxGetCount(Request $request) {
        $data = $request->all();
        $qty = Chat::whereRaw('sender = "' . $data['sender'] . '" AND seen = 0')->get();
        print_r(count($qty));
    }

    /*
     * This function will return all the data for a chat thread
     */

    public function ajaxfetchMessages(Request $request) {
        $data = $request->all();
        $messages = Chat::where('sender', '=', $data['sender'])->get();
        //Set the messages as readen
        foreach ($messages as $message) {
            $message->seen = 1;
            $message->save();
        }
        //print the data in the json-way
        print_r(json_encode($messages));
    }

    /*
     * This function will return all the data for a chat thread
     */

    public function ajaxAppFetchMessages() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $messages = Chat::where('sender', '=', $request->sender)->get();
        //print the data in the json-way
        print_r(json_encode($messages));
    }

    /*
     * This function will send a message to the server
     */

    public function ajaxSendMessage(Request $request) {
        $data = $request->all();
        $message = new Chat;
        //Set atributtes
        $message->sender = $data['sender'];
        $message->message = $data['message'];
        $message->mine = $data['mine'];
        $message->seen = 0;
        //Save it
        $message->save();
    }
    /*
     * This function will send a message to the server
     */

    public function ajaxAppSendMessage() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $message = new Chat;
        //Set atributtes
        $message->sender = $request->sender;
        $message->message = $request->message;
        $message->mine = 0;
        $message->seen = 0;
        //Save it
        $message->save();
    }

    /*
     * This function will generate a new token for the current chat session
     */

    public function ajaxGetUUID() {
        header("Access-Control-Allow-Origin: *");
        //generate a random number
        $uuid = rand(0, 99999) * time();
        print_r($uuid);
    }

}
