<script setup>
import { ref, computed, onMounted } from 'vue';
import Board from './Board.vue';

const boardSize = ref({ cols: 4, rows: 4 }); // Default board size
const cardImages = ['card1.png', 'card2.png', 'card3.png', 'card4.png', 'card5.png', 'card6.png']; // Add your card images
const gameStatus = ref(''); // Game status message

const updateBoardSize = (cols, rows) => {
  boardSize.value = { cols, rows };
};

const handleCardFlipped = (index) => {
  // Logic for handling a card flip (if needed)
};

const handleStatusChanged = (status) => {
  if (status === 'win') {
    gameStatus.value = 'You won!';
  }
};

onMounted(() => {
  boardSize.value = { cols: 4, rows: 4 }; // Initialize the default board
});
</script>

<template>
  <div class="memory-game">
    <div class="button-group">
      <button
        @click="updateBoardSize(3, 4)"
        class="w-36 h-12 leading-10 text-center rounded-xl border-none text-white bg-green-500 cursor-pointer hover:bg-green-600">
        3x4
      </button>
      <button
        @click="updateBoardSize(4, 4)"
        class="w-36 h-12 leading-10 text-center rounded-xl border-none text-white bg-green-500 cursor-pointer hover:bg-green-600">
        4x4
      </button>
      <button
        @click="updateBoardSize(6, 6)"
        class="w-36 h-12 leading-10 text-center rounded-xl border-none text-white bg-green-500 cursor-pointer hover:bg-green-600">
        6x6
      </button>
    </div>

    <Board
      :cols="boardSize.cols"
      :rows="boardSize.rows"
      :cardImages="cardImages"
      @cardFlipped="handleCardFlipped"
      @statusChanged="handleStatusChanged"
    />

    <div v-if="gameStatus" class="status">
      <p>{{ gameStatus }}</p>
    </div>
  </div>
</template>


<style scoped>
.button-group button {
  width: 9rem; /* equivalent to w-36 */
  height: 3rem; /* equivalent to h-12 */
  font-size: 1rem; /* equivalent to leading-10 */
  text-align: center;
  background-color: #48bb78; /* equivalent to bg-green-500 */
  color: white;
  border-radius: 0.75rem; /* equivalent to rounded-xl */
  cursor: pointer;
  border: none;
  transition: background-color 0.3s ease;
}

.button-group button:hover {
  background-color: #38a169; /* equivalent to hover:bg-green-600 */
}
</style>

