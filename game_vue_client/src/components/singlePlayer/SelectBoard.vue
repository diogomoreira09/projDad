<template>
    <div>
      <h1>Select a Board Size</h1>
      <div v-for="board in boards" :key="board.id" class="board-button">
        <button @click="selectBoard(board.id)">
          {{ board.board_cols }} x {{ board.board_rows }}
        </button>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        boards: [],
      };
    },
    created() {
      // Fetch board sizes from the backend
      axios.get('/api/boards')
        .then(response => {
          this.boards = response.data;
        })
        .catch(error => {
          console.error('Error fetching boards:', error);
        });
    },
    methods: {
      selectBoard(boardId) {
        axios.post('/api/memory-game', { board_id: boardId })
          .then(response => {
            // Redirect to the SinglePlayerGame with the new game ID
            this.$router.push({ name: 'SinglePlayerGame', params: { gameId: response.data.id } });
          })
          .catch(error => {
            console.error('Error creating game:', error);
          });
      },
    },
  };
  </script>
  
  <style>
  .board-button {
    margin-bottom: 10px;
  }
  </style>
  