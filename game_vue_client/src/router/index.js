import { createRouter, createWebHistory } from 'vue-router';
import SinglePlayerGame from '@/components/singlePlayer/SinglePlayerGame.vue';
import MultiPlayerGames from '@/components/multiPlayer/MultiPlayerGames.vue';
import Login from '@/components/auth/Login.vue';
import SelectBoard from '@/components/singlePlayer/SelectBoard.vue';
import Board from '@/components/singlePlayer/Board.vue'; // Import the Board.vue component
import { useAuthStore } from '@/stores/auth';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'singlePlayerGame',
      component: SinglePlayerGame,
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
    },
    {
      path: '/multi',
      name: 'multiPlayerGames',
      component: MultiPlayerGames,
    },
    {
      path: '/select-board',
      name: 'selectBoard',
      component: SelectBoard,
    },
    {
      path: '/game/:rows/:cols', // Dynamic route for the game board
      name: 'board',
      component: Board,
      props: true, // Pass route parameters as props to the component
    },
  ],
});

// Restore user token on first route load
let handlingFirstRoute = true;

router.beforeEach(async (to, from, next) => {
  const storeAuth = useAuthStore();
  if (handlingFirstRoute) {
    handlingFirstRoute = false;
    await storeAuth.restoreToken();
  }
  next();
});

export default router;
