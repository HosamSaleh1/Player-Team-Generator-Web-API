<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

class TeamController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $allPlayers = [];
        for ($i=0; $i < count($request->all()); $i++) { 
                // $players = Player::where('position', $request->all()[$i]['position'])->limit($request->all()[$i]['numberOfPlayers'])->get();
                $players = Player::where('position', $request->all()[$i]['position'])
                                    // ->skills()->max($request->all()[$i]['mainSkill'])
                                    // ->with(['skills' => function($query){
                                        // $query->where('skill', $request->all()[$i]['mainSkill']);
                                        // ->max('value');
                                    // }]
                                    // )
                                    ->limit($request->all()[$i]['numberOfPlayers'])->get();

                if(!$players || count($players) < $request->all()[$i]['numberOfPlayers']){
                    return response()->json(['message' => 'Insufficient number of players for position: '. $request->all()[$i]['position']], 404);
                }
                $allPlayers = array_merge($allPlayers, $players->toArray());
            }
        
        return response()->json($allPlayers);
    }
}
