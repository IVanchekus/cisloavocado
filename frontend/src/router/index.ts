import { createRouter, createWebHistory } from "vue-router";
import Home from "@/pages/Home/Home.vue";
import Maths from "@/pages/Maths/Maths.vue";
import Physics from "@/pages/Physics/Physics.vue";
import Auth from "@/pages/Auth/Auth.vue";

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
    component: Auth,
    meta: {
      isShowNavbar: false,
      pageTemplateHeight: '100vh'
    },
    props: {
      type: 'login'
    }
  },
  {
    path: "/register",
    name: "register",
    component: Auth,
    meta: {
      isShowNavbar: false,
      pageTemplateHeight: '100vh'
    },
    props: {
      type: 'register'
    }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router;