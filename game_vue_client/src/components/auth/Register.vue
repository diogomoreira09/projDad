<template>
    <form @submit.prevent="register" class="space-y-4 max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
        <input
          v-model="name"
          id="name"
          type="text"
          placeholder="Nome"
          class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>
  
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
        <input
          v-model="email"
          id="email"
          type="email"
          placeholder="E-mail"
          class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>
  
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
        <input
          v-model="password"
          id="password"
          type="password"
          placeholder="Senha"
          class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>
  
      <div>
        <label for="passwordConfirmation" class="block text-sm font-medium text-gray-700">Confirme a Senha</label>
        <input
          v-model="passwordConfirmation"
          id="passwordConfirmation"
          type="password"
          placeholder="Confirme a Senha"
          class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>
  
      <div>
        <label for="nickname" class="block text-sm font-medium text-gray-700">Nickname</label>
        <input
          v-model="nickname"
          id="nickname"
          type="text"
          placeholder="Nickname"
          class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>
  
      <div>
        <button
          type="submit"
          class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Registrar
        </button>
      </div>
    </form>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        name: "",
        email: "",
        password: "",
        passwordConfirmation: "",
        nickname: "",
        // O tipo é agora fixado como "player"
        type: "player",
      };
    },
    methods: {
      async register() {
        try {
          await axios.post("/register", {
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.passwordConfirmation,
            nickname: this.nickname,
            type: this.type, // O tipo "player" é automaticamente enviado
          });
          this.$router.push("/login"); // Redireciona para a página de login
        } catch (error) {
          console.error(error);
          alert("Erro ao registrar o usuário.");
        }
      },
    },
  };
  </script>
  