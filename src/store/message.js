import { defineStore } from "pinia";

export const useUserMessage = defineStore("message", {
  state: () => ({
    isVisible: false,
    message: {
      type: null,
      content: null,
    },
  }),
  getters: {
    getMessageParams(state) {
      return state.message;
    },
  },
  actions: {
    setMessageParams(params) {
      this.message = params;
    },
  },
});
