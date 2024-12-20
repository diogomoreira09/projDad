<template>
    <div class="p-6 max-w-4xl mx-auto">
      <h2 class="text-2xl font-bold mb-4">Top Scores</h2>
      <table class="w-full border-collapse border border-gray-200">
        <thead class="bg-gray-200">
          <tr>
            <th class="border border-gray-300 p-3 text-left">Player</th>
            <th class="border border-gray-300 p-3 text-left">Games Won</th>
            <th class="border border-gray-300 p-3 text-left">Total Time</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="score in topScores" :key="score.winner_user.id" class="hover:bg-gray-100">
            <td class="border border-gray-300 p-3">{{ score.winner_user.name }}</td>
            <td class="border border-gray-300 p-3">{{ score.games_won }}</td>
            <td class="border border-gray-300 p-3">{{ score.total_time }} segundos</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
import { ref } from 'vue';

export default {
  name: 'TopScores',
  setup() {
    const topScores = ref([]);
    const errorMessage = ref('');

    const fetchTopScores = async () => {
      try {
        const response = await axios.get('http://localhost/api/games/top-scores');
        topScores.value = response.data;
      } catch (error) {
        console.error('Erro ao carregar os melhores scores:', error);
        errorMessage.value = 'Erro ao carregar os melhores scores.';
      }
    };

    fetchTopScores();

    return { topScores, errorMessage };
  },
};

  </script>
  