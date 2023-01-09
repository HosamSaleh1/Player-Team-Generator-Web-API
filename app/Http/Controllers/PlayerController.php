<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Http\Requests\PlayerRequest;
use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::get();
        if (count($players) == 0) {
            return response()->json("No players found", 404);
        }
        return response()->json($players);
        // return response("Failed", 500);
    }

    public function show($idInThePath)
    {
        $player = Player::find($idInThePath);
        if ($player) {
            return response()->json($player);
        }
        return response()->json("Player not found", 404);
        // return response("Failed", 500);
    }

    public function store(Request $request)
    {
        if (!$request->name) {
            return response()->json("Name is required", 400);
        }elseif (!$request->position) {
            return response()->json("Position is required", 400);
        }elseif (!$request->playerSkills) {
            return response()->json("Player skills are required", 400);
        }
        $player = Player::create($request->except('playerSkills'));
        foreach ($request->playerSkills as $playerSkill) {
            $player->skills()->create($playerSkill);
        }
        $player = Player::find($player->id);
        return response()->json($player);
        // return response("Failed", 500);
    }

    public function update(PlayerRequest $request)
    {
        $player = Player::find($request->id);
        if (!$player) {
            return response()->json("Player not found", 404);
        }
        $player->update($request->except('playerSkills'));
        $skills = $player->skills()->get();
        if (count($skills)>0) {
            foreach ($skills as $skill) {
                $skill->delete();
            }
        }
        foreach ($request->playerSkills as $playerSkill) {
            $player->skills()->create($playerSkill);
        }
        $player = Player::find($player->id);
        return response()->json($player);
        // return response("Failed", 500);
    }

    public function destroy(Request $request, $idInThePath)
    {
        $token = $request->header('Authorization');
        if ($token != 'Bearer SkFabTZibXE1aE14ckpQUUxHc2dnQ2RzdlFRTTM2NFE2cGI4d3RQNjZmdEFITmdBQkE=') {
            return response("Unauthorized", 401);
        }
        $player = Player::find($idInThePath);
        if ($player) {
            $player->delete();
            return response()->json($player);
        }
        return response()->json("Player not found", 404);
        // return response("Failed", 500);
    }
}
