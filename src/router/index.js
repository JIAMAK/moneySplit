import { createRouter, createWebHistory } from "vue-router";
import MainView from "@/views/MainView.vue";
import AddEventView from "@/views/AddEventView.vue";
import EventView from "@/views/EventView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/404",
      name: "Error404",
      component: () => import("@/views/404Page.vue"),
    },
    {
      path: "/:pathMatch(.*)*",
      redirect: "/404",
    },
    {
      path: "/",
      name: "mainPage",
      component: MainView,
    },
    {
      path: "/add",
      name: "addEvent",
      component: AddEventView,
    },
    {
      path: "/event/:id",
      name: "event",
      component: EventView,
    },
  ],
});

export default router;
