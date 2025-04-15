import { createRouter, createWebHistory } from "vue-router";
import Home from "@/pages/Home/Home.vue";
import Maths from "@/pages/Maths/Maths.vue";
import Physics from "@/pages/Physics/Physics.vue";
import Login from "@/pages/Auth/Login/Login.vue";
import Register from "@/pages/Auth/Register/Register.vue";

const routes = [
  {
    path: "/",
    name: "home",
    component: Home
  },
  {
    path: "/physics",
    name: "physics",
    component: Physics 
  },
  {
    path: "/maths",
    name: "maths",
    component: Maths
  },
  {
    path: "/login",
    name: "login",
    component: Login,
    meta: {
      isShowNavbar: false,
      pageTemplateHeight: '100vh'
    }
  },
  {
    path: "/register",
    name: "register",
    component: Register,
    meta: {
      isShowNavbar: false,
      pageTemplateHeight: '100vh'
    }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router;