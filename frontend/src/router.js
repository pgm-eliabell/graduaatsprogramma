// this is where routes are defined 
//you dont have to add this to the main.js file, it is already imported in the main.js file automatically 
import { createRouter, createWebHistory } from 'vue-router';
import HomePage from './templates/HomePage.vue';
import AboutPage from './templates/AboutPage.vue';
import ContactPage from './templates/ContactPage.vue';
import LoginPage from './templates/LoginPage.vue';
import RegisterPage from './templates/RegisterPage.vue';
import NotFoundPage from './templates/NotFoundPage.vue';
import CreatePage from './templates/portolio/CreatePage.vue';

const routes = [
  { path: '/', component: HomePage },
  { path: '/about', component: AboutPage },
  { path: '/contact', component: ContactPage },
  { path: '/login', component: LoginPage},
  { path: '/register', component: RegisterPage},
  { path: '/:pathMatch(.*)*', component: NotFoundPage },
  { path: '/create', component: CreatePage},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
