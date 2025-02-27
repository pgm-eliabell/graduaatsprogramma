// this is where routes are defined 
//dont forget to also add the router to the main.js file
import { createRouter, createWebHistory } from 'vue-router';
import HomePage from './templates/HomePage.vue';
import AboutPage from './templates/AboutPage.vue';
import ContactPage from './templates/ContactPage.vue';

const routes = [
  { path: '/', component: HomePage },
  { path: '/about', component: AboutPage },
  { path: '/contact', component: ContactPage },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
