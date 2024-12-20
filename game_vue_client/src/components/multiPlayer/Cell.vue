<script setup>
const props = defineProps(['piece', 'index'])
const emit = defineEmits(['play'])

const play = () => {
    emit('play', props.index)
}
</script>

<template>
    <div class="cell">
      <div v-if="card" class="card revealed">{{ card }}</div>
      <div v-else class="card hidden"></div>
    </div>
  </template>