<template>
    <nav>
      <router-link to="/">Home</router-link>
      <router-link to="/About">About</router-link>
      <router-link to="/contact">Contact</router-link>
    </nav>
  </template>
  
  <script>
  export default {
    name: 'NavBar',
  };
  </script>
  
  <style scoped>
  li {
    color: white;
  }
  nav {
    background-color: #333;
    padding: 1rem;
  }
  
  ul {
    list-style: none;
    display: flex;
    gap: 1rem;
  }
  
  li {
    margin: 0;
  }
  
  a {
    color: white;
    text-decoration: none;
  }
  
  a:hover {
    text-decoration: underline;
  }
  </style>