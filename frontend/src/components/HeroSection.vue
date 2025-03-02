<template>
  <section class="hero">
    <h2>Showcase Your Work. Connect. Inspire.</h2>
    <p>Create and customize your portfolio, share your experiences, and engage with others.</p>
    <router-link to="/create" class="cta-button">Start Building</router-link>
  </section>
</template>

<style scoped>
.hero {
  text-align: center;
  padding: 60px 20px;
  background: linear-gradient(135deg, #0d1e4e, #2a5298); 
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
  transition: transform 0.3s ease-in-out; 
  color: white; 
}

.hero:hover {
  transform: scale(1.02); 
}

.hero h2 {
  font-size: 2.5em;
  margin-bottom: 0.5em;
}

.hero p {
  font-size: 1.2em;
  margin-bottom: 1.5em;
}


.cta-button {
  display: inline-block;
  margin-top: 20px;
  padding: 15px 30px;
  background: linear-gradient(to right, #007bff, #0056b3); 
  color: white;
  border-radius: 25px;
  text-decoration: none;
  font-size: 1.1em;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
  transition: background 0.3s ease, transform 0.3s ease; 
}

.cta-button:hover {
  background: linear-gradient(to right, #0056b3, #007bff);
  transform: translateY(-3px); 
}
</style>
