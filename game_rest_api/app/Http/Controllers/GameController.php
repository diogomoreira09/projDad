<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterGamesRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\GameUpdateRequest;
use App\Http\Requests\GameStoreRequest;
use App\Http\Resources\GameDetailedResource;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;



class GameController extends Controller
{



    public function gameHistory()
    {
        $user = Auth::user();

        $games = Game::where('created_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->get();

        return response()->json($games);
    }


    public function index(FilterGamesRequest $request)
    {
        $filterData = $request->validated();
        $qryGames = Game::query();
        if (array_key_exists("status", $filterData)) {
            if ($filterData["status"]) {
                $qryGames->where("status", $filterData["status"]);
            }
        }
        if (array_key_exists("player", $filterData)) {
            if ($filterData["player"]) {
                $player = $filterData["player"];
                $qryGames->where(function (Builder $query) use ($player) {
                    $query
                        ->where("player1_id", $player)
                        ->orWhere("player2_id", $player);
                });
            }
        }
        if (array_key_exists("secondplayer", $filterData)) {
            if ($filterData["secondplayer"]) {
                $player = $filterData["secondplayer"];
                $qryGames->where(function (Builder $query) use ($player) {
                    $query
                        ->where("player1_id", $player)
                        ->orWhere("player2_id", $player);
                });
            }
        }
        if (array_key_exists("winner", $filterData)) {
            if ($filterData["winner"]) {
                $qryGames->where("winner_id", $filterData["winner"]);
            }
        }
        $qryGames->with("player1", "player2", "winner");
        return GameResource::collection($qryGames->get());
    }

    public function createMemoryGame(Request $request)
{
    $boardId = $request->input('board_id');
    $board = Board::find($boardId);

    if (!$board) {
        return response()->json(['error' => 'Invalid board selection'], 400);
    }

    $cards = range(1, ($board->board_cols * $board->board_rows) / 2);
    $cards = array_merge($cards, $cards); // Duplicate for pairs
    shuffle($cards);

    $game = Game::create([
        'board_size' => "{$board->board_cols}x{$board->board_rows}",
        'game_state' => json_encode([
            'board' => array_chunk($cards, $board->board_cols),
            'revealed' => [],
        ]),
        'current_turn' => 1,
    ]);

    return response()->json($game);
}

    public function show(Game $game)
    {
        return new GameDetailedResource($game);
    }

    public function totalGames()
    {
        return Game::count();
    }


    public function store(GameStoreRequest $request)
    {
        $game = new Game();
        $game->status = "playing";
        $game->player1_id = $request->validated()["player1_id"];
        $game->player2_id = $request->validated()["player2_id"];
        $game->winner_id = null;
        $game->save();
        return new GameResource($game);
    }

    public function updateStatus(GameUpdateRequest $request, Game $game)
    {
        $data = $request->validated();
        $newStatus = $data["status"];
        // Only playing games can have their status changed (to ended or interrupted)
        if ($game->status != "playing") {
            throw ValidationException::withMessages([
                "status" =>
                    "Cannot change game #" .
                    $game->id .
                    " status from '" .
                    $game->status .
                    "' to '$newStatus'!",
            ]);
        }
        switch ($game->status) {
            case "pending":
                if (
                    $newStatus == "playing" &&
                    $request->user()->id == $game->player1_id
                ) {
                    return response()->json(
                        [
                            "message" =>
                                "Forbidden! Player 1 cannot start the game without player 2.",
                        ],
                        403
                    );
                }
                if ($newStatus == "ended") {
                    throw ValidationException::withMessages([
                        "status" =>
                            "Cannot change game #" .
                            $game->id .
                            " status from 'pending' to 'ended'!",
                    ]);
                }
                $game->status = $newStatus;
                $game->winner_id = null;
                if ($newStatus == "playing") {
                    $game->player2_id = $request->user()->id;
                }
                break;
            case "playing":
                if ($newStatus == "ended") {
                    $winner_id = null;
                    if (array_key_exists("winner_id", $data)) {
                        $winner_id = $data["winner_id"];
                        if ($winner_id != null) {
                            if (
                                $winner_id != $game->player1_id &&
                                $winner_id != $game->player2_id
                            ) {
                                throw ValidationException::withMessages([
                                    "winner_id" =>
                                        "Cannot change game #" .
                                        $game->id .
                                        " status from 'playing' to 'ended', because the winner is invalid!",
                                ]);
                            }
                        }
                    }
                    $game->status = $newStatus;
                    $game->winner_id = $winner_id;
                } else {
                    $game->status = $newStatus;
                }
                break;
            case "interrupted":
                throw ValidationException::withMessages([
                    "status" =>
                        "Cannot change game #" .
                        $game->id .
                        " status because it already has been interrupted!",
                ]);
                break;
            case "ended":
                throw ValidationException::withMessages([
                    "status" =>
                        "Cannot change game #" .
                        $game->id .
                        " status because it already has ended!",
                ]);
                break;
        }
        $game->save();
        return new GameResource($game);
    }
    public function getBoardOptions()
    {
    // Fetch board options
    $boards = Board::select('id', 'board_cols', 'board_rows', 'custom')->get();

    return response()->json($boards);
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return response()->json(null, 204);
    }
}
