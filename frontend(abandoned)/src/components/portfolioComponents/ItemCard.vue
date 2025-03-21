<template>
    <div class="item-card">
      <h3>{{ title }}</h3>
      <p>{{ description }}</p>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      title: String,
      description: String
    }
  };
  </script>
  
  <style scoped>
  .item-card {
    border: 1px solid #ddd;
    padding: 1rem;
    border-radius: 8px;
  }
  </style>
  