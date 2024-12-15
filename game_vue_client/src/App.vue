<script setup>
import { useTemplateRef, provide, inject } from 'vue'
import { RouterView } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useChatStore } from '@/stores/chat'   
import Toaster from '@/components/ui/toast/Toaster.vue'
import GlobalAlertDialog from '@/components/common/GlobalAlertDialog.vue'
import GlobalInputDialog from './components/common/GlobalInputDialog.vue'

const storeAuth = useAuthStore()
const storeChat = useChatStore()
const socket = inject('socket')

const alertDialog = useTemplateRef('alert-dialog')
provide('alertDialog', alertDialog)

const inputDialog = useTemplateRef('input-dialog')
provide('inputDialog', inputDialog)

const logoutConfirmed = () => {
    storeAuth.logout()
}

const logout = () => {
    alertDialog.value.open(logoutConfirmed, 'Logout confirmation?', 'Cancel', `Yes, I want to log out`,
        `Are you sure you want to log out? You can still access your account later with your credentials.`)
}

let userDestination = null
socket.on('privateMessage', (messageObj) => {
    userDestination = messageObj.user   
    inputDialog.value.open(
        handleMessageFromInputDialog,
        'Message from ' + messageObj.user.name,
        `This is a private message sent by ${messageObj?.user?.name}!`,
        'Reply Message', '',
        'Close', 'Reply',
        messageObj.message
    )
})
const handleMessageFromInputDialog = (message) => {
    storeChat.sendPrivateMessageToUser(userDestination, message)
}
</script>

<template>
    <Toaster />
    <GlobalAlertDialog ref="alert-dialog"></GlobalAlertDialog>
    <GlobalInputDialog ref="input-dialog"></GlobalInputDialog>
    <div class="p-8 mx-auto max-w-7xl">
    <div>
        <h1 class="text-4xl">TicTacToe</h1>
        <p class="pt-1 pb-4 text-xl">{{ storeAuth.user ? 'User: ' + storeAuth.userFirstLastName : ''}}</p>
    </div>

    <nav class="flex space-x-1 border-b-2 border-gray-800 text-base">
        <RouterLink to="/game3x4" class="w-36 h-10 leading-10 text-center rounded-t-xl 
        border-none text-white select-none bg-gray-400 cursor-pointer hover:bg-gray-500">
        3x4 Game
      </RouterLink>
      <RouterLink to="/game4x4" class="w-36 h-10 leading-10 text-center rounded-t-xl 
        border-none text-white select-none bg-gray-400 cursor-pointer hover:bg-gray-500">
        4x4 Game
      </RouterLink>
      <RouterLink to="/game6x6" class="w-36 h-10 leading-10 text-center rounded-t-xl 
        border-none text-white select-none bg-gray-400 cursor-pointer hover:bg-gray-500">
        6x6 Game
      </RouterLink>
        <RouterLink :to="{ name: 'multiPlayerGames' }" class="w-36 h-10 leading-10 text-center rounded-t-xl 
                border-none  text-white select-none bg-gray-400 cursor-pointer hover:bg-gray-500"
            activeClass="bg-gray-800 hover:bg-gray-800">
            Multi Player
        </RouterLink>
        <span class="grow"></span>
        <RouterLink v-show="!storeAuth.user" :to="{ name: 'login' }" class="w-24 h-10 leading-10 text-center rounded-t-xl 
            border-none  text-white select-none bg-gray-400 cursor-pointer hover:bg-gray-500"
            activeClass="bg-gray-800 hover:bg-gray-800">
            Login
        </RouterLink>
        <button v-show="storeAuth.user" @click="logout" class="w-24 h-10 leading-10 text-center rounded-t-xl 
            border-none  text-white select-none bg-gray-400 cursor-pointer hover:bg-gray-500">
            Logout
        </button>
    </nav>
    <RouterView></RouterView>
    </div>
</template>

