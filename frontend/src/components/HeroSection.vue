<template>
    <div class="hero">
      <h1>{{ title }}</h1>
      <p>{{ description }}</p>
    </div>
    <p>HERO SECTION COMPONENT</p>
  </template>
  
  <script>
  export default {
    props: ["title", "description"]
  };
  </script>
  
  <style>
  .hero {
    text-align: center;
    padding: 3rem 0;
    background: #282c34;
    color: white;
  }
  .hero h1 {
    font-size: 2.5rem;
  }
  .hero p {
    font-size: 1.2rem;
  }
  </style>