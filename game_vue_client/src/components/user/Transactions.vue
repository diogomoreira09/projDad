<template>
  <div class="p-6 max-w-md mx-auto bg-white rounded-lg shadow-md space-y-6">
    <h2 class="text-2xl font-bold text-center">Brain Coins</h2>

    <div class="text-center text-lg font-semibold">
      Total Brain Coins: {{ brainCoins }}
    </div>

    <div class="space-y-4">
      <button
        v-for="amount in [10, 50, 100]"
        :key="amount"
        @click="purchaseCoins(amount)"
        class="w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        Purchase {{ amount }} Coins
      </button>
    </div>

    <h3 class="text-xl font-semibold mt-8">Transaction History</h3>
    <ul>
      <li v-for="transaction in transactions" :key="transaction.id" class="border-b py-2">
        <div>{{ transaction.transaction_datetime }} - {{ transaction.type }}</div>
        <div>{{ transaction.brain_coins }} Coins</div>
        <div v-if="transaction.euros">€{{ transaction.euros }}</div>
        <div v-if="transaction.payment_type">Payment: {{ transaction.payment_type }}</div>
      </li>
    </ul>
  </div>
</template>
  
<script>
import axios from "axios";

export default {
  data() {
    return {
      brainCoins: 0,
      transactions: [],
      isPurchasing: false,
    };
  },
  async created() {
    try {
      const userResponse = await axios.get("/user", {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      this.brainCoins = userResponse.data.brain_coins_balance;

      const transactionsResponse = await axios.get("/transactions", {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      this.transactions = transactionsResponse.data;
    } catch (error) {
      console.error(error);
    }
  },
  methods: {
    async purchaseCoins(amount) {
      this.isPurchasing = true; // Disable buttons during processing
      try {
        // Define the payment type and reference for the demo
        const paymentType = "MB";
        const paymentReference = "45634-123456789"; // Example; replace with user input or generated value

        const euros = amount / 10; // Assuming 10 coins = 1 euro

        // Validate before making the API call
        if (euros <= 0 || euros >= 100) {
          alert("Invalid amount. Must be between 1 and 99 euros.");
          return;
        }

        // Call the external payment gateway
        const response = await axios.post(
          "https://dad-202425-payments-api.vercel.app/api/debit",
          {
            type: paymentType,
            reference: paymentReference,
            value: euros,
          }
        );

        if (response.status === 201) {
          // On success, update the user's brain coins
          await axios.post(
            "/transactions",
            {
              type: "P",
              euros,
              payment_type: paymentType,
              payment_reference: paymentReference,
              brain_coins: amount,
            },
            {
              headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
              },
            }
          );

          this.brainCoins += amount;

          alert(`Purchase successful! You received ${amount} Brain Coins.`);
        }
      } catch (error) {
        if (error.response && error.response.status === 422) {
          alert("Payment failed. Please check your payment details and try again.");
        } else {
          console.error(error);
          alert("An unexpected error occurred. Please try again later.");
        }
      } finally {
        this.isPurchasing = false; // Re-enable buttons after processing
      }
    },
  },
};
</script>

  
  <style scoped>
  /* Add any necessary styles here */
  </style>
  