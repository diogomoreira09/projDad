<script setup>
import { ref, onMounted } from 'vue';
import { generateBoard } from './gameLogic.js';
import Board from './Board.vue';

// You can dynamically assign these values based on user selection.
const rows = 4; // Example, replace with dynamic selection
const cols = 4; // Example, replace with dynamic selection

const board = ref([]); // Board state
const revealed = ref([]); // Cards that are flipped

// Generate the board on mounted
onMounted(() => {
  board.value = generateBoard(rows, cols);
});

// Handle card flip logic
const handleCardFlip = (row, col) => {
  if (revealed.value.length < 2) {
    revealed.value.push([row, col]);
  }
};
</script>

<template>
  <div class="game-container">
    <Board :board="board" :revealed="revealed" :onCardFlip="handleCardFlip" />
  </div>
</template>

<style scoped>
.game-container {
  max-width: 800px;
  margin: 0 auto;
}
</style>
