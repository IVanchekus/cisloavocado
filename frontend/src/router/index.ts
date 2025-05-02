import { createRouter, createWebHistory } from "vue-router";
import authMiddleware from "./middlewares/auth";

const routes = [
  {
    path: "/",
    redirect: { name: "home" },
  },
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
  {
    path: "/training-option",
    name: "training-option",
    redirect: { name: "home" },
    children: [
      {
        path: ':hash',
        name: "training-option-detail",
        component: () => import("@/pages/TrainingOption/TrainingOption.vue")
      }
    ]
  },
  {
    path: "/profile/:id",
    name: "profile",
    component: () => import("@/pages/Profile/Profile.vue")
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  authMiddleware(to, from, next);
});

export default router;