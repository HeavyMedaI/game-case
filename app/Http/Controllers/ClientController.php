<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = Client::find(1);
        $client->update(['status' => 1, 'position' => '0,0,0', 'state' => 'pending']);
        $client->pos = explode(",", $client->position);
        return view('client', ['client' => $client]);
    }

    /**
     * @return JsonResponse
     */
    public function state() {
        $client = Client::find(1);
        $client->pos = explode(",", $client->position);
        return $this->sendResponse($client->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request, int $id)
    {
        //var_dump($request->input());
        try{
            $client = Client::find($id);
            $client->update($request->input('client'));
            return $this->sendResponse(["status" => true]);
        }catch (\Exception $e){
            return $this->sendResponse(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
