<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const selectBoard = (rows, cols) => {
  router.push({ name: 'board', params: { rows, cols } });
};
</script>

<template>
  <div class="p-8">
    <h2 class="text-3xl mb-4 text-center">Select Board Size</h2>
    <div class="button-group">
      <button @click="selectBoard(3, 4)" class="board-size-btn">3x4</button>
      <button @click="() => selectBoard(4, 4)" class="board-size-btn">4x4</button>
      <button @click="selectBoard(6, 6)" class="board-size-btn">6x6</button>
    </div>
  </div>
</template>

<style scoped>
.button-group {
  display: flex;
  justify-content: center;
  gap: 20px;
}

.board-size-btn {
  background: #4e4e4e;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
}

.board-size-btn:hover {
  background: #6b6b6b;
}
</style>
