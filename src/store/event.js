import { defineStore } from "pinia";
import instance from "../axios";
import router from "../router";

export const useEvent = defineStore("event", {
  state: () => ({
    event_data: {},
    payments_data: {},
    debtors: [],
  }),
  getters: {
    getEventReuslt(state) {
      return state.event_data;
    },
    getPaymentsResult(state) {
      return state.payments_data;
    },
    getDebtorsResult(state) {
      return state.debtors;
    },
  },
  actions: {
    async getEvent(id) {
      await instance.get(`event?id=${id}`).then((res) => {
        if (res.data.length !== 0) {
          this.event_data = res.data;
        } else {
          router.push("/404");
        }
      });
    },
    async addEvent(body) {
      await instance.post("event/add", body).then((res) => console.log(res));
    },
    // Payments
    async getPayments(id) {
      await instance.get(`payments?id=${id}`).then((res) => {
        this.payments_data = res.data;
      });
    },
    async updatePayment(body) {
      await instance.post("pay/update", body).then((res) => console.log(res));
    },
    async addPayments(body) {
      await instance.post("pay", body).then((res) => {
        console.log(res);
      });
    },
    async getPayback(id) {
      await instance
        .get(`payback_transaction?id=${id}`)
        .then((res) => (this.debtors = res.data));
    },
  },
});
