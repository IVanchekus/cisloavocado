import router from "@/router";
import { useNavsStore } from "@/store/navsStore";

export default async () => {
  const navsStore = useNavsStore();
  await navsStore.getNavs();

  navsStore.navs.forEach((nav: {name: string, component: string}) => {
    router.addRoute({
      path: `/${nav.name}`,
      name: nav.name,
      component: () => import(`../pages/${nav.component}/${nav.component}.vue`),
    })
  });
};