<template>
    <div class="p-6 max-w-4xl mx-auto">
      <h2 class="text-2xl font-bold mb-4">Game History</h2>
      <table class="w-full border-collapse border border-gray-200">
        <thead class="bg-gray-200">
          <tr>
            <th class="border border-gray-300 p-3 text-left">Created User</th>
            <th class="border border-gray-300 p-3 text-left">Winner</th>
            <th class="border border-gray-300 p-3 text-left">Total Time</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="game in games" :key="game.id" class="hover:bg-gray-100">
            <td class="border border-gray-300 p-3">{{ game.created_user.name }}</td>
            <td class="border border-gray-300 p-3">{{ game.winner_user.name }}</td>
            <td class="border border-gray-300 p-3">{{ game.total_time }} segundos</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        games: [],
      };
    },
    async created() {
      try {
        const { data } = await axios.get('/api/games/history', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        });
        this.games = data;
      } catch (error) {
        console.error('Erro ao carregar histórico de jogos:', error);
      }
    },
  };
  </script>
  