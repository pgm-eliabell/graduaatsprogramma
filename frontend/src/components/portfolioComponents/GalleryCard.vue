<template>
    <div class="gallery">
      <img v-for="(image, index) in images" :key="index" :src="image" alt="Gallery Image" />
    </div>
  </template>
  
  <script>
  export default {
    props: {
      images: Array
    }
  };
  </script>
  
  <style scoped>
  .gallery {
    display: flex;
    gap: 1rem;
  }
  .gallery img {
    width: 100px;
    height: auto;
    border-radius: 8px;
  }
  </style>
  