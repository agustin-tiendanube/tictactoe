<?php

namespace Tests\Unit;

use App\Match;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

// REVIEW: Los tests cubren funcionalidad básica,
// No validan toda la lógica del juego.
class TicTacToeBasicTest extends TestCase
{
	use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHomeSmoke()
    {
        $this->get('/')
        	->assertStatus(200);
    }

    public function testGetMatches()
    {
    	$res = $this->get('/api/match');
        $res->assertStatus(200);
    }

    public function testCreateMatch()
    {
    	$res = $this->post('api/match');
        $res->assertStatus(200);
    }

    public function testMakeMoveMatch()
    {
    	Match::create(['name' => 'MatchTest', 'next' => 1, 'winner' => 0, 'board' => Match::EMPTYBOARD]);
    	$input = array("position" => 2);
    	$res = $this->put('api/match/1', $input);
        $res->assertStatus(200);
    }

    public function testDeleteMatch()
    {
    	$res = $this->delete('api/match/1');
        $res->assertStatus(200);
    }
}
