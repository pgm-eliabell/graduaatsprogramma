<template>
  <Navbar/>


  <router-view/>
</template>
<!-- <Hero title="Welcome to my Portfolio" description="This is a description of my portfolio"/>
<About/>
<Hero title="Projects" description="Here are some of my projects"/>
<Hero title="Contact" description="Contact me here"/> -->


<script>
// import HelloWorld from './components/HelloWorld.vue'
// import TestTemplate from './components/TestTemplate.vue'
import Navbar from './components/Navbar.vue'
// import About from './components/AboutPage.vue'
// import Hero from './components/portfolioComponents/HeroSection.vue'

export default {
  name: 'App',
  components: {
    Navbar, 
    // Hero,
    // About, 
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
