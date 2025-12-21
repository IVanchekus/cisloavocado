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
        path: "create",
        name: "training-option-create",
        component: () => import("@/pages/TrainingOption/TrainingOptionCreate.vue"),
        meta: {
          requiredRoles: ["admin", "teacher"],
        },
      },
      {
        path: "edit/:id",
        name: "training-option-edit",
        component: () => import("@/pages/TrainingOption/TrainingOptionEdit.vue"),
        meta: {
          requiredRoles: ["admin", "teacher"],
        },
      },
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
  },
  {
    path: "/exercise",
    name: "exercise",
    children: [
      {
        path: "create",
        name: "exercise-create",
        component: () => import('@/pages/TrainingOption/ExerciseCreate.vue'),
      },
      {
        path: "edit/:id",
        name: "exercise-edit",
        component: () => import('@/pages/TrainingOption/ExerciseEdit.vue'),
      },
    ]
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