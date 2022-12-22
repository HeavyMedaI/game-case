<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Directive;
use Illuminate\Http\Request;

class DirectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = Client::find(1);
        $client->update(['status' => 0, 'position' => '0,0,0', 'state' => 'sleep']);
        $client->pos = explode(",", $client->position);
        return view('director', ['client' => $client]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $moves = $request->post('moves');
        try{
            $directive = new Directive(["movements" => json_encode($moves)]);
            $directive->save();
            return $this->sendResponse(["status" => true]);
        }catch (\Exception $e){
            return $this->sendResponse(["status" => false, $e->getMessage()]);
        }
    }

    /**
     * Display the uncompleted directives in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getNewDirectives(Request $request)
    {
        $directives = Directive::all()->where("completed", 0);
        $new_directives = [];
        foreach ($directives as $index => $directive){
            $directive->movements = json_decode($directive["movements"]);
            $new_directives[] = $directive;
        }
        return $this->sendResponse($new_directives);
    }

    /**
     * Complete the specified directive in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function complete(Request $request, int $id)
    {
        try{
            $directive = Directive::find($id);
            $directive->completed = 1;
            $directive->save();
            return $this->sendResponse(["status" => true]);
        }catch (\Exception $e){
            return $this->sendResponse(["status" => false, "message" => $e->getMessage()]);
        }
    }
}

?>
