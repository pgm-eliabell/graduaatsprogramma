<template>
  <div class="home-container">
    <HeroSection/>
    <FeaturedPortfolio />
    <PortfolioGrid/>
  </div>
</template>

<script setup>
import PortfolioGrid from '@/components/PortfolioGrid.vue';
import FeaturedPortfolio from '@/components/FeaturedPortfolio.vue';
import HeroSection from '@/components/HeroSection.vue';
</script>

<script>
export default {
  components: {
    FeaturedPortfolio,
    PortfolioGrid,
    HeroSection
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

</style>