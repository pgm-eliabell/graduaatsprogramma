<template>
    <section class="hero">
      <h1>{{ title }}</h1>
      <p>{{ description }}</p>
    </section>
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
  .hero {
    text-align: center;
    padding: 2rem;
    background: #222;
    color: #fff;
  }
  </style>
  