<script setup>
import Cell from './Cell.vue'


const props = defineProps({
    board: {
        type: Array,
        required: true
    }
})

const emit = defineEmits(['play'])

const playPieceOfBoard = (idx) => {
    emit('play', idx)
}
</script>

<template>
    <div class="board">
      <div v-for="(row, rowIndex) in board" :key="rowIndex" class="row">
        <Card v-for="(card, colIndex) in row" :key="colIndex" :image="card.image" :isRevealed="revealedCards.some((c) => c.row === rowIndex && c.col === colIndex) ||
          matchedCards.some((c) => c.row === rowIndex && c.col === colIndex)
          " :isMatched="card.matched" @flip="() => handleFlip(rowIndex, colIndex)" />
      </div>
    </div>
  </template>