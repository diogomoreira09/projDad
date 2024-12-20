<template>
    <div class="p-8 mx-auto max-w-7xl">
      <h1 class="text-4xl mb-8 font-bold text-gray-800">Statistics</h1>
      <div v-if="loading" class="text-center text-gray-500">Loading...</div>
      <div v-if="error" class="text-center text-red-500">{{ error }}</div>
  
      <div v-if="stats" class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-lg">
          <h3 class="text-lg font-semibold text-gray-800">Número de Usuários</h3>
          <p class="text-4xl font-bold text-gray-900">{{ stats.users }}</p>
        </div>
  
        <div class="bg-white p-6 rounded-lg shadow-lg">
          <h3 class="text-lg font-semibold text-gray-800">Número de Jogos</h3>
          <p class="text-4xl font-bold text-gray-900">{{ stats.games }}</p>
        </div>
  
        <div class="bg-white p-6 rounded-lg shadow-lg">
          <h3 class="text-lg font-semibold text-gray-800">Número de Transações</h3>
          <p class="text-4xl font-bold text-gray-900">{{ stats.transactions }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script lang="ts">
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  
  export default {
    name: 'Stats',
    setup() {
      const stats = ref(null);
      const error = ref(null);
      const loading = ref(true);
  
      const fetchStats = async () => {
        try {
          const response = await axios.get('http://localhost/api/stats');
          stats.value = response.data;
        } catch (err) {
          error.value = 'Erro ao carregar as estatísticas.';
          console.error(err);
        } finally {
          loading.value = false;
        }
      };
  
      onMounted(() => {
        fetchStats();
      });
  
      return { stats, error, loading };
    },
  };
  </script>
  
  <style scoped>
  /* Nenhum estilo adicional necessário, o Tailwind CSS cobre tudo */
  </style>
  