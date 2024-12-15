import { createRouter, createWebHistory } from 'vue-router'
import SinglePlayerGame from '@/components/singlePlayer/SinglePlayerGame.vue'
import MultiPlayerGames from '@/components/multiPlayer/MultiPlayerGames.vue'
import Login from '@/components/auth/Login.vue'
import { useAuthStore } from '@/stores/auth'
import SelectBoard from '@/components/singlePlayer/SelectBoard.vue'
import Game3x4 from '@/components/singlePlayer/Game3x4.vue';
import Game4x4 from '@/components/singlePlayer/Game4x4.vue';
import Game6x6 from '@/components/singlePlayer/Game6x6.vue';



const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
        {
            path: '/',
            name: 'singlePlayerGame',
            component: SinglePlayerGame
        },
        {
            path: '/single',
            redirect: { name: 'singlePlayerGame' }
        },    
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/multi',
            name: 'multiPlayerGames',
            component: MultiPlayerGames
        },
        {   path: '/select-board',
            name: 'SelectBoard', 
            component: SelectBoard 
        },
        {   path: '/game3x4',
            component: Game3x4
        },
        {   path: '/game4x4', 
            component: Game4x4 
        },
        {   path: '/game6x6', 
            component: Game6x6 },

  ],
})

let handlingFirstRoute = true

router.beforeEach(async (to, from, next) => {
    const storeAuth = useAuthStore()
    if (handlingFirstRoute) {
        handlingFirstRoute = false
        await storeAuth.restoreToken()
    }
    next()
})
    
    
export default router
    