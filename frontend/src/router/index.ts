import { createRouter, createWebHistory } from "vue-router";
import Home from "@/pages/Home/Home.vue";
import Maths from "@/pages/Maths/Maths.vue";
import Physics from "@/pages/Physics/Physics.vue";

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
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router;