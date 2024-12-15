<script setup>
const props = defineProps({
  card: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['flip']);

const flip = () => {
  if (!props.card.flipped) {
    emit('flip', props.card);
  }
};
</script>

<template>
  <div class="card" @click="flip">
    <!-- Display card back when not flipped -->
    <div v-if="!card.flipped">
      <img src="/img/cards/semFace.png" alt="Card back" />
    </div>
    <!-- Display the front image when flipped -->
    <div v-else>
      <img :src="`/img/cards/${card.imageId}.png`" alt="Card front" />
    </div>
  </div>
</template>

<style scoped>
.card {
  width: 100px;
  height: 150px;
  cursor: pointer;
  background-color: #ddd;
  display: flex;
  justify-content: center;
  align-items: center;
}

.card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
