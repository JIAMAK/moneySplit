<script setup>
import { reactive, ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import {
  NLayout,
  NLayoutHeader,
  NLayoutContent,
  NLayoutFooter,
  NCard,
  NGrid,
  NGi,
  NRow,
  NCol,
  NSpace,
  NButton,
  NDivider,
  NH1,
  NH3,
  NH4,
  NList,
  NListItem,
  useMessage,
} from "naive-ui";
import { useEvent } from "../store/event";

import AddExpenseModal from "../components/AddExpenseModal.vue";
import PaymentsList from "../components/event/PaymentsList.vue";
import DebtorsList from "../components/event/DebtorsList.vue";

const route = useRoute();
const formData = reactive({});
const activeUser = ref(null);
const expenseModal = ref(false);
const paymentsList = ref([]);
const debtorList = ref([]);
const editedItem = reactive({});
const message = useMessage();

onMounted(() => {
  init();
});

const init = () => {
  const event = useEvent();
  const id = route.params.id;
  event.getEvent(id).then(() => {
    Object.assign(formData, event.getEventReuslt);
  });
  event.getPayments(id).then(() => {
    paymentsList.value = event.getPaymentsResult;
  });
  event.getPayback(id).then(() => {
    debtorList.value = event.getDebtorsResult;
  });
};

const bClick = (user) => {
  activeUser.value = user;
};

const isCloseDialog = (data) => {
  for (let member in editedItem) delete editedItem[member];
  init();
};

const copyLink = () => {
  navigator.clipboard.writeText(window.location.href);
  message.success("Ссылка скопирована");
};

const onEditPayment = (data) => {
  Object.assign(editedItem, data);
  expenseModal.value = true;
};
</script>
<template>
  <n-layout>
    <n-layout-content class="content-style" content-style="padding: 24px;">
      <n-grid v-if="formData.event_name" item-responsive>
        <n-gi
          offset="0 400:0 600:0 800:6 1000:8"
          span="24 400:24 600:24 800:12 1000:8"
        >
          <n-card style="width: 100%">
            <template #header>
              <n-row>
                <n-col :span="24">
                  <n-space justify="center" align="center">
                    <n-h1>{{ formData.event_name }}</n-h1>
                  </n-space>
                </n-col>
              </n-row>
            </template>
            <n-row>
              <n-col :span="24">
                <n-space justify="center">
                  <n-h3>Ваше имя</n-h3>
                </n-space>
              </n-col>
              <n-col :span="24">
                <n-space justify="center">
                  <n-button
                    type="primary"
                    v-for="b in formData.members"
                    :ghost="b.member_id == activeUser ? false : true"
                    :key="b.member_id"
                    @click="bClick(b.member_id)"
                    >{{ b.name }}
                  </n-button>
                </n-space>
              </n-col>
            </n-row>
            <n-row style="margin-top: 15px">
              <n-col :span="24">
                <n-space justify="center">
                  <n-button
                    type="info"
                    :disabled="!activeUser"
                    @click="expenseModal = true"
                  >
                    Добавить расход
                  </n-button>
                </n-space>
                <n-divider></n-divider>
              </n-col>
            </n-row>
            <PaymentsList :items="paymentsList" @edit:payment="onEditPayment" />
            <DebtorsList :items="debtorList" />
            <n-row
              style="margin-top: 18px; display: flex; justify-content: center"
            >
              <n-col :span="10">
                <n-button block ghost round @click="copyLink"
                  >Скопировать URL</n-button
                >
              </n-col>
            </n-row>
          </n-card>
        </n-gi>
      </n-grid>
      <AddExpenseModal
        v-model:dialog="expenseModal"
        :active-user="activeUser"
        :editItem="editedItem"
        @update:dialog="isCloseDialog"
      />
    </n-layout-content>
  </n-layout>
</template>

<style>
.stat-description {
  font-size: 0.875rem;
  color: #818181;
}
.stat {
  font-size: 1.1rem;
  font-weight: 500;
}
</style>