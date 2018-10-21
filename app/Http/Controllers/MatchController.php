<?php

namespace App\Http\Controllers;

use App\Match;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller {

    public function index() {
        return view('index');
    }

    /**
     * Returns a list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function matches() {
        return response()->json($this->getMatches());
    }

    /**
     * Returns the state of a single match
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function match($id) {
        $match = $this->getMatch($id);
        return response()->json($match);
    }

    /**
     * Returns a match with the board as an array
     *
     * @param $id
     * @return \App\Match
     */
    public function getMatch($id)
    {
        $match = New Match;
        $match = $match->getMatch($id);
        return $match;
    }

    /**
     * Makes a move in a match
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function move($id) {
        $position = Input::get('position');
        $match = $this->getMatch($id);
        if($match)
        {
            $match->makeMove($position);
            $match->changeNext();
            $match->detectWinner();
            $match->save();
            $match->board = array_map('intval', explode(',', $match->board));
            $matchArray = array("id" => $match->id, 'name' => $match->name, 'next' => $match->next, 'winner' => $match->winner, 'board' => $match->board);
        }
        else
        {
            $matchArray = false;
        }
        return response()->json($matchArray);
    }

    /**
     * Creates a new match and returns the new list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create() {
        $id = 1;
        $lastMatch = Match::orderBy('id', 'DESC')->first();
        if($lastMatch)
        {
            $id = $lastMatch->id + 1;
        }
        Match::create(['name' => 'Match'.$id, 'next' => 1, 'winner' => 0, 'board' => Match::EMPTYBOARD]);
        return response()->json($this->getMatches());
    }

    /**
     * Deletes the match and returns the new list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id) {
        Match::destroy($id);
        return response()->json($this->getMatches());
    }

    /**
     * Get an array of matches
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMatches() {
        return Match::all();
    }

    /**
     * Creates a fake array of matches
     *
     * @return \Illuminate\Support\Collection
     */
    private function fakeMatches() {
        return collect([
            [
                'id' => 1,
                'name' => 'Match1',
                'next' => 2,
                'winner' => 1,
                'board' => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 2, 1,
                ],
            ],
            [
                'id' => 2,
                'name' => 'Match2',
                'next' => 1,
                'winner' => 0,
                'board' => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 0, 0,
                ],
            ],
            [
                'id' => 3,
                'name' => 'Match3',
                'next' => 1,
                'winner' => 0,
                'board' => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 2, 0,
                ],
            ],
            [
                'id' => 4,
                'name' => 'Match4',
                'next' => 2,
                'winner' => 0,
                'board' => [
                    0, 0, 0,
                    0, 0, 0,
                    0, 0, 0,
                ],
            ],
        ]);
    }

}