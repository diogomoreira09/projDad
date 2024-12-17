<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  image: String,
  isRevealed: Boolean,
  isMatched: Boolean, // New prop to check if the card is matched
});

const emit = defineEmits(['flip']);
const flipped = ref(false);

// Watch the prop `isRevealed` to flip the card
watch(
  () => props.isRevealed,
  (newVal) => {
    flipped.value = newVal;
  }
);
</script>

<template>
  <div v-if="!isMatched" class="card" @click="emit('flip')"> <!-- Hide matched cards -->
    <img
      v-if="flipped"
      :src="`/img/cards/${image}`"
      alt="Card"
      class="card-img"
    />
    <img
      v-else
      src="/img/cards/semFace.png"
      alt="Back"
      class="card-img"
    />
  </div>
</template>

<style scoped>
.card {
  width: 80px;
  height: 100px;
  perspective: 1000px;
  cursor: pointer;
}

.card-img {
  width: 100%;
  height: 100%;
  border-radius: 8px;
}
</style>
