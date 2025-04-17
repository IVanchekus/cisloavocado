import { createRouter, createWebHistory } from "vue-router";
import authMiddleware from "./middlewares/auth";

const routes = [
  {
    path: "/login",
    name: "login",
    component: () => import("@/pages/Auth/Auth.vue"),
    meta: {
      isShowNavbar: false,
      pageTemplateHeight: "100vh",
    },
    props: {
      type: "login",
    },
  },
  {
    path: "/register",
    name: "register",
    component: () => import("@/pages/Auth/Auth.vue"),
    meta: {
      isShowNavbar: false,
      pageTemplateHeight: "100vh",
    },
    props: {
      type: "register",
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  authMiddleware(to, from, next);
});

export default router;