<script setup>
import { ref } from 'vue';
import Card from './Card.vue';
import { useGameLogic } from './gameLogic';

const game = useGameLogic(4, 4); // Pass in the dimensions for a 4x4 grid
const { cards, turns, isGameWon, flipCard, revealHint } = game;
</script>

<template>
  <div>
    <div class="game-header">
      <h2>Memory Game (4x4)</h2>
      <p>Turns: {{ turns }}</p>
      <p v-if="isGameWon">You won the game!</p>
      <button @click="revealHint">Hint</button>
    </div>

    <div class="board" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
      <Card 
        v-for="(card, index) in cards" 
        :key="index"
        :card="card" 
        @flip="flipCard(card)"
      />
    </div>
  </div>
</template>
