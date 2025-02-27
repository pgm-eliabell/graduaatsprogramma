<template>
    <div class="social-links">
      <a v-for="link in links" :key="link.url" :href="link.url" target="_blank">{{ link.name }}</a>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      links: Array
    }
  };
  </script>
  
  <style scoped>
  .social-links {
    display: flex;
    gap: 1rem;
  }
  .social-links a {
    color: #007bff;
    text-decoration: none;
  }
  </style>
  