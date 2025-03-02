<template>
  <section class="featured-users">
    <h3>Featured Users</h3>
    <div v-if="loading" class="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="featuredUsers.length" class="bento-grid">
      <!-- Dynamically render each user in the bento grid layout -->
      <div
        v-for="(user, index) in featuredUsers"
        :key="user.id"
        :class="['grid-item', getGridClass(index)]"
      >
        <router-link :to="`/user/${user.id}`">
          <img :src="getImageUrl(user.profilePicture)" :alt="user.username" />
          <div class="overlay">
            <h4>{{ user.firstName }} {{ user.lastName }}</h4>
            <span>View User</span>
          </div>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getUsers } from '../api/api.js'; 

const loading = ref(false);
const featuredUsers = ref([]);
const error = ref(null);

onMounted(async () => {
  loading.value = true;
  try {
    const users = await getUsers();
    featuredUsers.value = users.slice(0, 4); // Display up to 4 users
  } catch (err) {
    error.value = err.message;
  } finally {
    loading.value = false;
  }
});

// Function to determine the grid class based on index
const getGridClass = (index) => {
  if (index === 0) return 'large';
  if (index === 1 || index === 2) return 'small';
  if (index === 3) return 'medium';
  return '';
};

// Function to construct the image URL relative to the public directory
const getImageUrl = (imagePath) => {
  return `http://127.0.0.1:8001/uploads/profilePictures${imagePath}`;
};
</script>

<style scoped>
.featured-users {
  margin-top: 40px;
  background-color: #f0f0f0;
  padding: 20px;
}

.bento-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  grid-template-rows: 1fr 1fr;
  gap: 10px;
}

.grid-item {
  position: relative;
  overflow: hidden;
  border-radius: 8px;
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

.grid-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
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
}

.overlay h4 {
  margin: 0;
}

.overlay span {
  color: #ffd700;
  text-decoration: none;
}

.loading {
  color: blue;
}

.error {
  color: red;
}
</style>
