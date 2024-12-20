exports.createGameEngine = () => {
    const initGame = (gameFromDB) => {
        gameFromDB.gameStatus = null;
        // null -> game has not started yet 
        // 0 -> game has started and running
        // 1 -> player 1 is the winner
        // 2 -> player 2 is the winner
        // 3 -> draw

        gameFromDB.currentPlayer = 1; // Player 1 starts
        gameFromDB.board = generateBoard(); // Generate a shuffled board of card pairs
        gameFromDB.flippedCards = []; // Store currently flipped cards
        gameFromDB.scores = { player1: 0, player2: 0 }; // Initialize player scores
        return gameFromDB;
    };

    // ------------------------------------------------------
    // Utility Functions
    // ------------------------------------------------------

    // Generate a shuffled board with pairs of cards
    const generateBoard = () => {
        const numPairs = 8; // Example: 8 pairs of cards
        const cards = Array.from({ length: numPairs * 2 }, (_, i) => Math.floor(i / 2)); // Create pairs
        return shuffle(cards); // Shuffle cards
    };

    // Shuffle an array (Fisher-Yates Shuffle)
    const shuffle = (array) => {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    };

    // Check if the game has ended (all pairs are matched)
    const gameEnded = (game) => game.board.every(card => card === null);

    // Check and update the game status based on scores
    const changeGameStatus = (game) => {
        if (gameEnded(game)) {
            if (game.scores.player1 > game.scores.player2) {
                game.gameStatus = 1; // Player 1 wins
            } else if (game.scores.player1 < game.scores.player2) {
                game.gameStatus = 2; // Player 2 wins
            } else {
                game.gameStatus = 3; // Draw
            }
        } else {
            game.gameStatus = 0; // Game is still ongoing
        }
    };

    // ------------------------------------------------------
    // Actions / Methods
    // ------------------------------------------------------

    // Play a turn by flipping a card
    const play = (game, index, playerSocketId) => {
        if ((playerSocketId !== game.player1SocketId) && (playerSocketId !== game.player2SocketId)) {
            return {
                errorCode: 10,
                errorMessage: 'You are not playing this game!',
            };
        }
        if (gameEnded(game)) {
            return {
                errorCode: 11,
                errorMessage: 'Game has already ended!',
            };
        }
        if (((game.currentPlayer === 1) && (playerSocketId !== game.player1SocketId)) ||
            ((game.currentPlayer === 2) && (playerSocketId !== game.player2SocketId))) {
            return {
                errorCode: 12,
                errorMessage: 'Invalid play: It is not your turn!',
            };
        }
        if (game.flippedCards.length === 2) {
            return {
                errorCode: 13,
                errorMessage: 'Invalid play: Two cards are already flipped!',
            };
        }
        if (game.board[index] === null) {
            return {
                errorCode: 14,
                errorMessage: 'Invalid play: This card is already matched!',
            };
        }

        // Flip the card
        game.flippedCards.push({ index, value: game.board[index] });

        // Check if two cards are flipped
        if (game.flippedCards.length === 2) {
            const [card1, card2] = game.flippedCards;

            if (card1.value === card2.value) {
                // It's a match! Remove the cards from the board
                game.board[card1.index] = null;
                game.board[card2.index] = null;

                // Award a point to the current player
                if (game.currentPlayer === 1) {
                    game.scores.player1 += 1;
                } else {
                    game.scores.player2 += 1;
                }

                // Check if the game has ended
                changeGameStatus(game);
            } else {
                // No match: Switch to the next player
                game.currentPlayer = game.currentPlayer === 1 ? 2 : 1;
            }

            // Clear flipped cards
            game.flippedCards = [];
        }

        return true;
    };

    // One of the players quits the game. The other one wins.
    const quit = (game, playerSocketId) => {
        if ((playerSocketId !== game.player1SocketId) && (playerSocketId !== game.player2SocketId)) {
            return {
                errorCode: 10,
                errorMessage: 'You are not playing this game!',
            };
        }
        if (gameEnded(game)) {
            return {
                errorCode: 11,
                errorMessage: 'Game has already ended!',
            };
        }
        game.gameStatus = playerSocketId === game.player1SocketId ? 2 : 1; // The other player wins
        game.status = 'ended';
        return true;
    };

    // Check if the game can be closed (game must have ended)
    const close = (game, playerSocketId) => {
        if ((playerSocketId !== game.player1SocketId) && (playerSocketId !== game.player2SocketId)) {
            return {
                errorCode: 10,
                errorMessage: 'You are not playing this game!',
            };
        }
        if (!gameEnded(game)) {
            return {
                errorCode: 14,
                errorMessage: 'Cannot close a game that has not ended!',
            };
        }
        return true;
    };

    return {
        initGame,
        gameEnded,
        play,
        quit,
        close,
    };
};
