<template>
  <div class="home-container">
    <section class="hero">
      <h2>Showcase Your Work. Connect. Inspire.</h2>
      <p>Create and customize your portfolio, share your experiences, and engage with others.</p>
      <router-link to="/create" class="cta-button">Start Building</router-link>
    </section>

    <FeaturedPortfolio />

    <!-- Latest Posts Section -->
    <!-- Add your latest posts section here -->
  </div>
</template>

<script setup>
import FeaturedPortfolio from '@/components/FeaturedPortfolio.vue';
</script>

<script>
export default {
  components: {
    FeaturedPortfolio
  }
};
</script>
<style scoped>
.home-container {
  font-family: Arial, sans-serif;
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}
.hero {
  text-align: center;
  padding: 50px 20px;
  background: #f4f4f4;
  border-radius: 10px;
}
.cta-button {
  display: inline-block;
  margin-top: 10px;
  padding: 10px 20px;
  background: #007bff;
  color: white;
  border-radius: 5px;
  text-decoration: none;
}
</style>