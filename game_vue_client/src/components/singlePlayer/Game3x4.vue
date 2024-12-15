<script setup>
import { ref } from 'vue';
import Card from './Card.vue'; // Card component
import { useGameLogic } from './gameLogic'; // Game logic composable

const game = useGameLogic(3, 4); // 3x4 grid
const { cards, turns, isGameWon, flipCard, revealHint } = game;

</script>

<template>
  <div>
    <div class="game-header">
      <h2>Memory Game (3x4)</h2>
      <p>Turns: {{ turns }}</p>
      <p v-if="isGameWon">You won the game!</p>
      <button @click="revealHint">Hint</button>
    </div>

    <div class="board" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
      <Card 
        v-for="(card, index) in cards" 
        :key="index"
        :card="card" 
        @flip="flipCard(card)"
      />
    </div>
  </div>
</template>
