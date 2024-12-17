<script setup>
import { defineProps } from 'vue'

defineProps({
  card: String // The card content or null if not revealed
})
</script>

<template>
  <div class="cell">
    <div v-if="card" class="card revealed">{{ card }}</div>
    <div v-else class="card hidden"></div>
  </div>
</template>

<style scoped>
.cell {
  aspect-ratio: 1;
  cursor: pointer;
  border: 2px solid #ccc;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #fff;
}

.card {
  font-size: 1.5rem;
  font-weight: bold;
}

.hidden {
  background-color: #888;
}

.revealed {
  background-color: #fff;
}
</style>
