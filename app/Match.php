<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $table = 'matches';
    public $timestamps = true;
    const EMPTYBOARD = '0,0,0,0,0,0,0,0,0';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'next', 'winner', 'board',
    ];

    /**
     * This function gets the match and transforms the board into an array of integers
     */
    public function getMatch($id)
    {
        $match = parent::find($id);
        if($match)
        {
            $match->board = array_map('intval', explode(',', $match->board));
            return $match;
        }
        return false;
    }

    public function changeNext()
    {
        // REVIEW podría ser resuelto con:
        // $this->next = $this->next == 1 ? 2 : 1;
        if($this->next == 1)
        {
            $this->next = 2;
        }
        else
        {
            $this->next = 1;
        }
    }

    public function makeMove($pos)
    {
        $board = $this->board;
        $board[$pos] = $this->next;
        $this->board = implode(",",$board);
    }

    public function detectWinner()
    {
        // REVIEW: Llamar a horizontalWinner acá y adentro del if hace que se
        // repita el proceso de validación. Igual que en el resto
        if($this->horizontalWinner() !== false)
        {
            $this->winner = $this->horizontalWinner();
            $this->next = 0;
        }

        if($this->verticalWinner() !== false)
        {
            $this->winner = $this->verticalWinner();
            $this->next = 0;
        }

        if($this->diagonalWinner() !== false)
        {
            $this->winner = $this->diagonalWinner();
            $this->next = 0;
        }
    }

    // REVIEW: Esta solución funciona y es clara, pero repite mucho código
    public function horizontalWinner()
    {
        $boardArray = explode(",", $this->board);

        // First Row
        if($boardArray[0] != 0 && $boardArray[0] == $boardArray[1] && $boardArray[0] == $boardArray[2])
        {
            return $boardArray[0];
        }

        // Second Row
        if($boardArray[3] != 0 && $boardArray[3] == $boardArray[4] && $boardArray[3] == $boardArray[5])
        {
            return $boardArray[3];
        }

        // Third Row
        if($boardArray[6] != 0 && $boardArray[6] == $boardArray[7] && $boardArray[6] == $boardArray[8])
        {
            return $boardArray[6];
        }

        return false;
    }

    public function verticalWinner()
    {
        $boardArray = explode(",", $this->board);

        // First Column
        if($boardArray[0] != 0 && $boardArray[0] == $boardArray[3] && $boardArray[0] == $boardArray[6])
        {
            return $boardArray[0];
        }

        // Second Column
        if($boardArray[1] != 0 && $boardArray[1] == $boardArray[4] && $boardArray[1] == $boardArray[7])
        {
            return $boardArray[1];
        }

        // Third Column
        if($boardArray[2] != 0 && $boardArray[2] == $boardArray[5] && $boardArray[2] == $boardArray[8])
        {
            return $boardArray[2];
        }

        return false;
    }

    public function diagonalWinner()
    {
        $boardArray = explode(",", $this->board);

        // First Diagonal
        if($boardArray[0] != 0 && $boardArray[0] == $boardArray[4] && $boardArray[0] == $boardArray[8])
        {
            return $boardArray[0];
        }

        // Second Diagonal
        if($boardArray[2] != 0 && $boardArray[2] == $boardArray[4] && $boardArray[2] == $boardArray[6])
        {
            return $boardArray[2];
        }

        return false;
    }
}
