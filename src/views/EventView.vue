<script setup>
import { reactive, ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import {
  NLayout,
  NLayoutHeader,
  NLayoutContent,
  NLayoutFooter,
  NSpace,
  NGrid,
  NGi,
  NButton,
  NDivider,
  NH3,
  NH4,
  NButtonGroup,
} from "naive-ui";

import AddExpenseModal from "../components/AddExpenseModal.vue";

const route = useRoute();
const formData = reactive({});
const activeUser = ref(null);
const expenseModal = ref(true);

onMounted(() => {
  const data = JSON.parse(localStorage.getItem("_eventData"));
  Object.assign(formData, data);
});

const bClick = (user) => {
  activeUser.value = user;
};
</script>
<template>
  <n-layout>
    <n-layout-content class="content-style" content-style="padding: 24px;">
      <n-grid :y-gap="12" :x-gap="12">
        <n-gi :span="24">
          <n-h3>Ваше имя</n-h3>
        </n-gi>
        <n-gi :span="24">
          <n-button-group>
            <n-space>
              <n-button
                type="primary"
                :ghost="b.id == activeUser ? false : true"
                v-for="b in formData.members"
                :key="b.id"
                @click="bClick(b.id)"
                >{{ b.name }}
              </n-button>
            </n-space>
          </n-button-group>
        </n-gi>
        <n-gi :span="4">
          <n-button
            type="info"
            :disabled="!activeUser"
            @click="expenseModal = true"
          >
            Добавить расход
          </n-button>
        </n-gi>
        <n-gi :span="24"> <n-divider></n-divider> </n-gi>
        <n-gi :span="24">
          <pre>{{ formData }}</pre>
        </n-gi>
      </n-grid>
      <AddExpenseModal
        v-model:dialog="expenseModal"
        :active-user="activeUser"
      />
    </n-layout-content>
  </n-layout>
</template>

<style>
</style>