<script setup>
import { ref } from 'vue'
import Cell from './Cell.vue'

// Props for dynamically passing board data and game logic from the parent
const props = defineProps({
  board: Array, // The board state (2D array of cards)
  revealed: Array, // List of revealed card indices
  onCardFlip: Function, // Function to handle card flips
})

// Expose board data and actions for testing or parent component access
defineExpose({
  board: props.board,
})

// Helper function to check if a card is revealed or matched
const isRevealed = (row, col) => {
  return props.revealed.some(([r, c]) => r === row && c === col)
}
</script>
<template>
    <div class="game-container">
      <h1 class="text-3xl pb-4">Memory Game</h1>
      
      <!-- Buttons to choose board size -->
      <div class="button-group">
        <button @click="setBoardSize(3, 4)" class="board-size-btn">3x4</button>
        <button @click="setBoardSize(4, 4)" class="board-size-btn">4x4</button>
        <button @click="setBoardSize(6, 6)" class="board-size-btn">6x6</button>
      </div>
  
      <!-- Render the Board component -->
      <Board 
        :board="board" 
        :revealed="revealed" 
        :onCardFlip="handleCardFlip" 
      />
    </div>
  </template>
  
  <style scoped>
  .button-group {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 20px;
  }
  
  .board-size-btn {
    padding: 10px 20px;
    background: #4caf50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
  }
  
  .board-size-btn:hover {
    background: #45a049;
  }
  </style>
  
  
  <style scoped>
  .board-grid {
    display: grid;
    gap: 10px; /* Space between cells */
    margin: 20px auto;
    max-width: 90%;
  }
  </style>
  