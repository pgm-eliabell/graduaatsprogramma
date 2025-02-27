<template>
  <section class="featured-portfolios">
    <h3>Featured Portfolios</h3>
    <div class="grid-container">
      <div
        class="grid-item"
        v-for="portfolio in paginatedPortfolios"
        :key="portfolio.id"
      >
        <router-link :to="`/portfolio/${portfolio.id}`">
          <img :src="portfolio.image" :alt="portfolio.name" />
          <div class="overlay">
            <h4>{{ portfolio.name }}</h4>
            <span>View Portfolio</span>
          </div>
        </router-link>
      </div>
    </div>
    <PaginationComponent
      :totalItems="portfolios.length"
      :itemsPerPage="itemsPerPage"
      @page-changed="handlePageChange"
    />
  </section>
</template>

<script>
import PaginationComponent from './PaginationComponent.vue';

export default {
  components: {
    PaginationComponent
  },
  data() {
    return {
      portfolios: [
        { id: 1, name: "John Doe", image: "https://meafoliobackendfolder.ddev.site/images/portfolioThumbnails/placeholder.jpg" },
        { id: 2, name: "Jane Smith", image: "https://meafoliobackendfolder.ddev.site/images/portfolioThumbnails/placeholder.jpg" },
        { id: 3, name: "Eliaz Bello Medrano", image: "https://meafoliobackendfolder.ddev.site/images/portfolioThumbnails/placeholder.jpg" }
      ],
      currentPage: 1,
      itemsPerPage: 6
    };
  },
  computed: {
    paginatedPortfolios() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.portfolios.slice(start, end);
    }
  },
  methods: {
    handlePageChange(page) {
      this.currentPage = page;
    }
  }
};
</script>

<style scoped>
.featured-portfolios {
  margin-top: 40px;
  background-color: green;
  padding: 20px;
}

.grid-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 10px;
}

.grid-item {
  position: relative;
  overflow: hidden;
  border-radius: 8px;
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
</style>
