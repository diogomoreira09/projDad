import { createRouter, createWebHistory } from 'vue-router';
import SinglePlayerGame from '@/components/singlePlayer/SinglePlayerGame.vue';
import MultiPlayerGames from '@/components/multiPlayer/MultiPlayerGames.vue';
import Login from '@/components/auth/Login.vue';
import SelectBoard from '@/components/singlePlayer/SelectBoard.vue';
import Board from '@/components/singlePlayer/Board.vue'; // Import the Board.vue component
import Register from '@/components/auth/Register.vue';
import Profile from '@/components/user/Profile.vue';
import GameHistory from '@/components/game/GameHistory.vue';
import TopScores from '@/components/game/TopScores.vue';
import { useAuthStore } from '@/stores/auth';
import Statistics from '@/components/statistics/Statistics.vue';
import Transactions from '@/components/user/Transactions.vue';

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
    {
      path: '/games/history',
      name: 'gameHistory',
      component: GameHistory,
    },
    {
      path: '/games/top-scores',
      name: 'topScores',
      component: TopScores,
    },
    { 
      path: '/statistics', 
      name: 'statistics', 
      component: Statistics 
    },
    { 
      path: '/transactions', 
      name: 'transactions', 
      component: Transactions
    },
    { 
      path: '/register', 
      name: 'register', 
      component: Register 
    },
    {
      path: '/profile',
      name: 'profile',
      component: Profile,
      meta: { requiresAuth: true }, // Protegido por autenticação
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
