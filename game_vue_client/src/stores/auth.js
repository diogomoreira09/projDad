import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import { useErrorStore } from '@/stores/error'
import { useRouter } from 'vue-router'

export const useAuthStore = defineStore('auth', () => {
    const router = useRouter()
    const storeError = useErrorStore()
    const socket = inject('socket')

    const user = ref(null)
    const token = ref('')
    
    const userId = computed(() => {
        return user.value ? user.value.id : -1
    })

    const userName = computed(() => {
        return user.value ? user.value.name : ''
    })

    const getFirstLastName = (fullName) => {
        const names = fullName.trim().split(' ')
        const firstName = names[0] ?? ''
        const lastName = names.length > 1 ? names[names.length -1 ] : ''
        return (firstName + ' ' + lastName).trim()
    }

    const userFirstLastName = computed(() => {
        return getFirstLastName(userName.value)
    })

    const userEmail = computed(() => {
        return user.value ? user.value.email : ''
    })

    // This function is "private" - not exported by the store
    const clearUser = () => {
        resetIntervalToRefreshToken()
        if (user.value) {
            socket.emit('logout', user.value)
        }        
        user.value = null
        token.value = ''
        localStorage.removeItem('token')
        axios.defaults.headers.common.Authorization = ''        
    }
    
const login = async (credentials) => {
    storeError.resetMessages()
    console.log('Attempting to login with credentials:', credentials)

    try {
        const responseLogin = await axios.post('auth/login', credentials)
        console.log('Login successful:', responseLogin.data)

        token.value = responseLogin.data.token
        localStorage.setItem('token', token.value)
        axios.defaults.headers.common.Authorization = 'Bearer ' + token.value

        const responseUser = await axios.get('users/me')
        console.log('User fetched:', responseUser.data)

        user.value = responseUser.data.data
        socket.emit('login', user.value)
        repeatRefreshToken()
        router.push({ name: 'singlePlayerGame' })
        return user.value
    } catch (e) {
        console.error('Login error:', e.response?.data || e.message)
        clearUser()
        storeError.setErrorMessages(
            e.response?.data.message,
            e.response?.data.errors,
            e.response?.status,
            'Authentication Error!'
        )
        return false
    }
}


    const logout = async () => {
        storeError.resetMessages()
        try {
            await axios.post('auth/logout')
            clearUser()
            return true
        } catch (e) {
            clearUser()
            storeError.setErrorMessages(e.response.data.message, [], e.response.status, 'Authentication Error!')
            return false
        }
    }

    // These 2 functions and intervalToRefreshToken variable are "private" - not exported by the store
    let intervalToRefreshToken = null

    const resetIntervalToRefreshToken = () => {
        if (intervalToRefreshToken) {
            clearInterval(intervalToRefreshToken)
        }
        intervalToRefreshToken = null
    }

    const repeatRefreshToken = () => {
        if (intervalToRefreshToken) {
            clearInterval(intervalToRefreshToken)
        }
        intervalToRefreshToken = setInterval(async () => {
            try {
                const response = await axios.post('auth/refreshtoken')
                token.value = response.data.token
                localStorage.setItem('token', token.value)
                axios.defaults.headers.common.Authorization = 'Bearer ' + token.value
                return true
            } catch (e) {
                clearUser()
                storeError.setErrorMessages(e.response.data.message, e.response.data.errors, e.response.status, 'Authentication Error!')
                return false
            }
        }, 1000 * 60 * 110)  // repeat every 110 minutes

        // To test the refresh token, replace previous line with the following code
        // This will repeat the refreshtoken endpoint every 10 seconds:
        //}, 1000 * 10)

        return intervalToRefreshToken
    }

    const restoreToken = async function () {
        let storedToken = localStorage.getItem('token')
            if (storedToken) {
                try {
                    token.value = storedToken
                    axios.defaults.headers.common.Authorization = 'Bearer ' + token.value
                    const responseUser = await axios.get('users/me')
                    user.value = responseUser.data.data
                    socket.emit('login', user.value)
                    repeatRefreshToken()
                    return true                 
                } catch {
                    clearUser()
                    return false 
                }
        }
        return false
    }

    return {
        user, userId, userName, userFirstLastName, userEmail, 
        login, logout, restoreToken, getFirstLastName
    }
})
