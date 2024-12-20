import './assets/index.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import { io } from "socket.io-client"

import App from './App.vue'
import router from './router'

import ErrorMessage from './components/common/ErrorMessage.vue'

const app = createApp(App)
app.provide('socket', io("http://localhost:8081"))   

app.use(createPinia())
app.use(router)

// Default Axios configuration
axios.defaults.baseURL = 'http://localhost/api'

app.component('ErrorMessage', ErrorMessage)

// Crie a instância do Pinia e a use no app
const pinia = createPinia();
app.use(pinia);

app.mount('#app')
