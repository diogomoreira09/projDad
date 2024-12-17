<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import Card from './Card.vue';
import { generateBoard } from './gameLogic'; // Import the game logic for board generation

// Get board size from route parameters
const route = useRoute();
const rows = parseInt(route.params.rows);
const cols = parseInt(route.params.cols);

// Game state
const board = ref([]);
const revealedCards = ref([]);
const matchedCards = ref([]); // Store matched cards
const waiting = ref(false);

// Generate the board when the component is mounted
onMounted(() => {
  board.value = generateBoard(rows, cols); // Use the function from gameLogic.js to generate the board
});

// Card flip logic
const handleFlip = (rowIndex, colIndex) => {
  if (waiting.value) return;

  const card = { row: rowIndex, col: colIndex };

  if (revealedCards.value.length === 1) {
    revealedCards.value.push(card);
    waiting.value = true;

    const [first, second] = revealedCards.value;
    const firstCard = board.value[first.row][first.col];
    const secondCard = board.value[second.row][second.col];

    // Check if the two flipped cards match
    if (firstCard.image === secondCard.image) {
      // Mark both cards as matched
      matchedCards.value.push(first, second);
      board.value[first.row][first.col].matched = true;
      board.value[second.row][second.col].matched = true;
    }

    setTimeout(() => {
      revealedCards.value = [];
      waiting.value = false;
    }, 1000);
  } else {
    revealedCards.value = [card];
  }
};
</script>

<template>
  <div class="board">
    <div v-for="(row, rowIndex) in board" :key="rowIndex" class="row">
      <Card
        v-for="(card, colIndex) in row"
        :key="colIndex"
        :image="card.image"
        :isRevealed="
          revealedCards.some((c) => c.row === rowIndex && c.col === colIndex) ||
          matchedCards.some((c) => c.row === rowIndex && c.col === colIndex)
        "
        :isMatched="card.matched"
        @flip="() => handleFlip(rowIndex, colIndex)"
      />
    </div>
  </div>
</template>

<style scoped>
.board {
  display: grid;
  gap: 10px;
  margin: 20px auto;
  max-width: 500px;
}

.row {
  display: flex;
  gap: 10px;
}
</style>
