<template>
  <div class="create-portfolio-container">
    <h1 class="text-3xl font-bold mb-6">
      Create Your Portfolio
    </h1>

    <!-- Example for portfolio name -->
    <div class="mb-4">
      <label class="block font-semibold mb-2" for="portfolioName">
        Portfolio Name
      </label>
      <input
        v-model="portfolioData.name"
        id="portfolioName"
        type="text"
        class="border border-gray-300 p-2 rounded w-full"
      />
    </div>

    <!-- Existing “Save All” button -->
    <div class="space-x-2 mt-4">
      <button
        @click="saveAll"
        class="px-4 py-2 bg-green-600 text-white rounded"
      >
        Save All
      </button>

      <!-- New: Edit Portfolio (rename, toggle visibility, etc.) -->
      <button
        @click="editPortfolio"
        class="px-4 py-2 bg-blue-600 text-white rounded"
      >
        Edit
      </button>

      <!-- New: Delete entire portfolio -->
      <button
        @click="deletePortfolio"
        class="px-4 py-2 bg-red-600 text-white rounded"
      >
        Delete
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "CreatePortfolioPage",
  data() {
    return {
      portfolioData: {
        id: 123, // Suppose we loaded an existing portfolio with ID=123
        name: "My Portfolio",
        visible: true,
        // plus your "blocks"/"components" as needed
      },
    };
  },
  methods: {
    async saveAll() {
      // This calls your /api/portfolios/save route
      try {
        const body = {
          blocks: [ /* array of components */ ],
          layout_config: {},
          // etc.
        };

        const response = await axios.post("/api/portfolios/save", body);
        console.log(response.data);
        alert("All components saved!");
      } catch (error) {
        console.error(error);
        alert("Error saving components.");
      }
    },

    async editPortfolio() {
      // Example: rename or toggle “visible” via the new route
      try {
        // For a PUT or PATCH, you can do:
        const response = await axios.patch(
          `/api/portfolios/${this.portfolioData.id}/edit`,
          {
            name: this.portfolioData.name,
            visible: this.portfolioData.visible,
          }
        );
        console.log("Edit success:", response.data);
        alert("Portfolio updated!");
      } catch (error) {
        console.error(error);
        alert("Error editing portfolio.");
      }
    },

    async deletePortfolio() {
      // Confirm action with user
      if (!confirm("Are you sure you want to DELETE the portfolio?")) {
        return;
      }

      try {
        const response = await axios.delete(
          `/api/portfolios/${this.portfolioData.id}/delete`
        );
        console.log("Delete success:", response.data);
        alert("Portfolio deleted!");

        // Optionally, redirect somewhere else now:
        // window.location.href = "/";
      } catch (error) {
        console.error(error);
        alert("Error deleting portfolio.");
      }
    },
  },
};
</script>

<style scoped>
.create-portfolio-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}
</style>
