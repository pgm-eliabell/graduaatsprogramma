<template>
  <section class="featured-users">
    <h3>Featured Users</h3>
    <div v-if="loading" class="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="paginatedUsers.length" class="bento-grid">
      <div
        v-for="(user, index) in paginatedUsers"
        :key="user.id"
        :class="['grid-item', getGridClass(index)]"
      >
        <router-link :to="`/user/${user.id}`">
          <img :src="getImageUrl(user.profilePicture)" :alt="user.username" class="user-image" />
          <div class="overlay">
            <h4>{{ user.firstName }} {{ user.lastName }}</h4>
            <span>View User</span>
          </div>
        </router-link>
      </div>
    </div>

    <!-- Pagination Controls -->
    <div class="pagination">
      <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
      <span>Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { getUsers } from '../api/api.js';

const loading = ref(false);
const featuredUsers = ref([]);
const error = ref(null);
const currentPage = ref(1);
const usersPerPage = 9; // Number of users per page

onMounted(async () => {
  loading.value = true;
  try {
    const users = await getUsers();
    featuredUsers.value = users;
  } catch (err) {
    error.value = err.message;
  } finally {
    loading.value = false;
  }
});

const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * usersPerPage;
  const end = start + usersPerPage;
  return featuredUsers.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(featuredUsers.value.length / usersPerPage);
});

const getGridClass = (index) => {
  if (index % 3 === 0) return 'large';
  if (index % 3 === 1 || index % 3 === 2) return 'small';
  return 'medium';
};

const getImageUrl = (imagePath) => {
  return `http://127.0.0.1:8001/uploads/profilePictures${imagePath}`;
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};
</script>

<style scoped>
.featured-users {
  margin-top: 40px;
  padding: 20px;
  background-color: #1e3a8a; /* Gentle dark blue background */
  color: white;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.bento-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 15px;
  animation: fadeIn 1s ease-in-out;
}

.grid-item {
  position: relative;
  overflow: hidden;
  border-radius: 8px;
  background: #2a5298; /* Slightly lighter blue for grid items */
  transition: transform 0.3s ease-in-out;
}

.grid-item:hover {
  transform: scale(1.05);
}

.large {
  grid-column: span 2;
  grid-row: span 2;
}

.small {
  grid-column: span 1;
  grid-row: span 1;
}

.medium {
  grid-column: span 2;
  grid-row: span 1;
}

.user-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  border-radius: 8px;
  transition: opacity 0.5s ease-in-out;
}

.user-image:hover {
  opacity: 0.8;
}

.overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.5);
  color: white;
  padding: 10px;
  text-align: center;
  opacity: 0;
  transition: opacity 0.3s ease-in-out;
}

.grid-item:hover .overlay {
  opacity: 1;
}

.overlay h4 {
  margin: 0;
}

.overlay span {
  color: #ffd700;
  text-decoration: none;
}

.loading {
  color: #ffd700;
}

.error {
  color: #ff4d4d;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.pagination button {
  background: #0056b3;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.pagination button:hover {
  background: #007bff;
}

.pagination span {
  margin: 0 10px;
  color: white;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
