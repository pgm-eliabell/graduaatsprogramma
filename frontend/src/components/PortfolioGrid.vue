<template>
    <div class="portfolio-grid">
      <h2>Uitgelichte Portfolio's</h2>
      <div class="grid">
        <div v-for="portfolio in portfolios" :key="portfolio.id" class="portfolio-card">
          <img :src="portfolio.image" :alt="portfolio.name" />
          <p>{{ portfolio.name }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: ["portfolios"]
  };
  </script>
  
  <style>
  .portfolio-grid {
    flex: 1;
  }
  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
  }
  .portfolio-card {
    text-align: center;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  }
  .portfolio-card img {
    width: 100%;
    height: auto;
  }
  </style>