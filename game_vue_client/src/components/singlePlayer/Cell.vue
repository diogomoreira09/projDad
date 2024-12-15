<script setup>
const props = defineProps({
  card: String,
  isRevealed: Boolean,
});

const emit = defineEmits(['click']);
</script>

<template>
  <div class="cell" @click="emit('click')">
    <img v-if="isRevealed" :src="`/img/cards/${card}`" alt="Card front" class="card-front" />
    <img v-else src="/img/cards/semFace.png" alt="Card back" class="card-back" />
  </div>
</template>

<style scoped>
.cell {
  width: 100px;
  height: 140px;
  cursor: pointer;
  position: relative;
  perspective: 1000px;
}

.card-front,
.card-back {
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  position: absolute;
  top: 0;
  left: 0;
  border-radius: 10px;
  transition: transform 0.6s;
}

.card-front {
  transform: rotateY(0deg);
}

.card-back {
  transform: rotateY(180deg);
}

.cell:hover .card-back {
  transform: rotateY(90deg);
}
</style>
