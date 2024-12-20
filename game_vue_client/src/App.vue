<script lang="ts" setup>
import { useTemplateRef, provide, inject } from 'vue';
import { RouterView, RouterLink } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useChatStore } from '@/stores/chat';
import Toaster from '@/components/ui/toast/Toaster.vue';
import GlobalAlertDialog from '@/components/common/GlobalAlertDialog.vue';
import GlobalInputDialog from '@/components/common/GlobalInputDialog.vue';


// Define the socket type explicitly
interface Socket {
  on(event: string, callback: Function): void;
  // Add more methods or properties as necessary, depending on what 'socket' does.
}

const storeAuth = useAuthStore();
const storeChat = useChatStore();

// Inject the socket and type it explicitly
const socket = inject('socket') as Socket;  // Assert socket type

// Dialog references for logout and chat input
const alertDialog = useTemplateRef<InstanceType<typeof GlobalAlertDialog>>('alert-dialog');
const inputDialog = useTemplateRef<InstanceType<typeof GlobalInputDialog>>('input-dialog');
provide('inputDialog', inputDialog);

// Logout functionality
const logoutConfirmed = () => {
  storeAuth.logout();
};

const logout = () => {
  alertDialog.value?.open(
    logoutConfirmed,
    'Logout confirmation?',
    'Cancel',
    'Yes, I want to log out',
    'Are you sure you want to log out? You can still access your account later with your credentials.'
  );
};

// Handle private messages
let userDestination = null
socket.on('privateMessage', (messageObj) => {
  userDestination = messageObj.user;

  // Ensure the input dialog is ready before trying to open it
  if (inputDialog?.value) {
    inputDialog.value.open(
      (replyMessage) => {
        // This function gets triggered when the user submits their reply
        storeChat.sendPrivateMessageToUser(userDestination, replyMessage);
      },
      'Private Message from ' + (messageObj.user.name), // Title
      'You received a private message:', // Description
      'Type your reply here', // Input label
      '', // Initial input value (empty for now)
      'Close', // Cancel button text
      'Reply', // Action button text
      messageObj.message // Main content (the received message)
    );
  } else {
    console.error('Input dialog is not initialized');
  }
});


const handleMessageFromInputDialog = (message) => {
  storeChat.sendPrivateMessageToUser(userDestination, message);
};
</script>

<template>
  <Toaster />
  <GlobalAlertDialog ref="alert-dialog" />
  <GlobalInputDialog ref="input-dialog" />

  <div class="p-8 mx-auto max-w-7xl">
    <!-- Header and User Info -->
    <div>
      <h1 class="text-4xl">Memory Game</h1>
      <p class="pt-1 pb-4 text-xl">{{ storeAuth.user ? 'User: ' + storeAuth.userFirstLastName : '' }}</p>
    </div>

    <!-- Navigation Bar -->
    <nav class="flex space-x-1 border-b-2 border-gray-800 text-base">
      <RouterLink
        to="/select-board"
        class="w-36 h-10 leading-10 text-center rounded-t-xl border-none text-white select-none bg-gray-400 cursor-pointer hover:bg-gray-500"
      >
        Single Player
      </RouterLink>

      <RouterLink
        :to="{ name: 'multiPlayerGames' }"
        class="w-36 h-10 leading-10 text-center rounded-t-xl border-none text-white select-none bg-gray-400 cursor-pointer hover:bg-gray-500"
        active-class="bg-gray-800 hover:bg-gray-800"
      >
        Multi Player
      </RouterLink>

      <span class="grow"></span>

      <RouterLink
        v-show="!storeAuth.user"
        :to="{ name: 'login' }"
        class="w-24 h-10 leading-10 text-center rounded-t-xl border-none text-white select-none bg-gray-400 cursor-pointer hover:bg-gray-500"
        active-class="bg-gray-800 hover:bg-gray-800"
      >
        Login
      </RouterLink>

      <button
        v-show="storeAuth.user"
        @click="logout"
        class="w-24 h-10 leading-10 text-center rounded-t-xl border-none text-white select-none bg-gray-400 cursor-pointer hover:bg-gray-500"
      >
        Logout
      </button>
    </nav>

    <!-- Route View -->
    <RouterView />
  </div>
</template>
