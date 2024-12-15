<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'player1_id' => $this->player1_id,
            'player2_id' => $this->player2_id,
            'winner_id' => $this->winner_id,
            'player1_name' => $this->player1 ? $this->player1->name : null,
            'player2_name' => $this->player2 ? $this->player2->name : null,
            'winner_name' => $this->winner ? $this->winner->name : null,
        ];
    }
}
