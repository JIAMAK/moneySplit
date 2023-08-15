import { defineStore } from "pinia";
import instance from "../axios";
import router from "@/router";

export const useUser = defineStore("user", {
  state: () => ({
    token: null,
    username: null,
    redirect: null,
  }),
  getters: {
    getUserName(state) {
      return state.username;
    },
  },
  actions: {
    async userLogin(body) {
      await instance.post("login", body).then((res) => {
        if (res.response.jwt_token) {
          this.token = res.response.jwt_token;
          this.username = res.response.username;
          if (this.redirect) {
            window.location.href = `${this.redirect}`; //?token=${this.token}`;
          } else {
            router.push("/apps");
          }
        }
      });
    },
    async logOut() {
      await instance.post("logout", { username: this.username }).then((res) => {
        if (res.success) {
          console.log(123);
          router.push("/login");
        }
      });
    },
  },
});
