<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use Response;
use App\Player;
use App\Team;
use View;


class PlayersController extends Controller
{
    /**
    * @var array
    */
    protected $rules =
    [
        'name' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
    ];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dd();


        $players = Player::orderBy('id', 'desc')->get();
        $teams = Team::orderBy('id', 'desc')->get();

        $admin = false;

        if($user = Auth::user())
        {
            if($user->id == 1){
                $admin = true;
            }
        }


        return view('players.index', ['players' => $players, 'teams' => $teams, 'admin' => $admin]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(), $this->rules);
        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        } else {
            $player = new Player();
            $player->name = $request->name;
            $player->team_id = $request->team_id;
            $player->save();

            $player->team_name = Team::findOrFail($request->team_id)->name;

//            dd($player);

            return response()->json($player);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $player = Player::findOrFail($id);


        return view('player.show', ['player' => $player]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $validator = Validator::make(Input::all(), $this->rules);
        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        } else {
            $player = Player::findOrFail($id);
            $player->name = $request->name;
            $player->team_id = $request->team_id;
            $player->save();
            $player->team_name = Team::findOrFail($request->team_id)->name;
            return response()->json($player);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();

        return response()->json($player);
    }


    /**
     * Change resource status.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus() 
    {
        $id = Input::get('id');

        $player = Player::findOrFail($id);
        $player->is_published = !$player->is_published;
        $player->save();

        return response()->json($player);
    }
}