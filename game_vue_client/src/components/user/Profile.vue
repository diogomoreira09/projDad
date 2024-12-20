<template>
    <div class="p-8 mx-auto max-w-7xl">
      <!-- Formulário de Atualização de Perfil -->
      <form @submit.prevent="updateProfile" class="space-y-6 bg-white p-6 rounded-lg shadow-lg">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
          <input
            v-model="name"
            type="text"
            id="name"
            placeholder="Nome"
            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
  
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
          <input
            v-model="email"
            type="email"
            id="email"
            placeholder="E-mail"
            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
  
        <div>
          <label for="nickname" class="block text-sm font-medium text-gray-700">Nickname</label>
          <input
            v-model="nickname"
            type="text"
            id="nickname"
            placeholder="Nickname"
            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
  
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Nova Senha (opcional)</label>
          <input
            v-model="password"
            type="password"
            id="password"
            placeholder="Nova Senha"
            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
  
        <div>
          <label for="photo" class="block text-sm font-medium text-gray-700">Fotografia</label>
          <input
            ref="photo"
            type="file"
            id="photo"
            accept="image/*"
            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
  
        <div class="text-lg font-semibold">
          Brain Coins: {{ brainCoins }}
        </div>
  
        <button
          type="submit"
          class="w-full py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          Salvar Alterações
        </button>
      </form>
  
      <!-- Botão de Remover Conta -->
      <div class="flex flex-col items-center space-y-4 mt-8">
        <button
          @click="openConfirmModal"
          class="w-full py-3 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
        >
          Remover Conta
        </button>
      </div>
  
      <!-- Modal de Confirmação -->
      <div
        v-if="showModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50"
      >
        <div class="bg-white rounded-lg p-6 max-w-md w-full text-center">
          <h2 class="text-lg font-bold mb-4">Confirmação de Remoção</h2>
          <p class="mb-6">Tem certeza que deseja remover sua conta? Esta ação não pode ser desfeita.</p>
          <div class="flex justify-between space-x-4">
            <button
              @click="closeConfirmModal"
              class="bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              Cancelar
            </button>
            <button
              @click="deleteAccount"
              class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
            >
              Confirmar
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  import { useAuthStore } from "@/stores/auth";
  import { ref } from "vue";
  
  export default {
    data() {
      return {
        name: "",
        email: "",
        nickname: "",
        password: "",
        brainCoins: 0,
      };
    },
    async created() {
      try {
        const { data } = await axios.get("/user", {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.name = data.name;
        this.email = data.email;
        this.nickname = data.nickname;
        this.brainCoins = data.brain_coins_balance;
      } catch (error) {
        console.error(error);
      }
    },
    methods: {
      async updateProfile() {
        const formData = new FormData();
        formData.append("name", this.name);
        formData.append("email", this.email);
        formData.append("nickname", this.nickname);
        if (this.password) {
          formData.append("password", this.password);
        }
        if (this.$refs.photo.files[0]) {
          formData.append("photo", this.$refs.photo.files[0]);
        }
  
        try {
          // Use PUT para atualizações
          await axios.put("/user", formData, {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
              "Content-Type": "multipart/form-data",
            },
          });
          alert("Perfil atualizado!");
        } catch (error) {
          console.error(error);
        }
      },
    },
  
    setup() {
      const storeAuth = useAuthStore();
      const showModal = ref(false);
  
      // Abre o modal de confirmação
      const openConfirmModal = () => {
        showModal.value = true;
      };
  
      // Fecha o modal de confirmação
      const closeConfirmModal = () => {
        showModal.value = false;
      };
  
      // Função para deletar a conta
      const deleteAccount = async () => {
        try {
          await axios.delete("/user", {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          });
          alert("Conta removida com sucesso.");
          storeAuth.logout(); // Remove dados locais do usuário
          this.$router.push({ name: "login" }); // Redireciona para a página de login
        } catch (error) {
          console.error(error);
          alert("Erro ao remover a conta.");
        } finally {
          showModal.value = false; // Fecha o modal
        }
      };
  
      return {
        showModal,
        openConfirmModal,
        closeConfirmModal,
        deleteAccount,
      };
    },
  };
  </script>
  