<script setup>
import { ref, reactive } from "vue";
import { v4 as uuidv4 } from "uuid";
import { useRoute, useRouter } from "vue-router";

import {
  NLayout,
  NLayoutHeader,
  NLayoutContent,
  NLayoutFooter,
  NCard,
  NSpace,
  NGrid,
  NGi,
  NRow,
  NCol,
  NForm,
  NFormItem,
  NInput,
  NButton,
  NDivider,
  NH4,
  NIcon,
  NInputGroup,
} from "naive-ui";
import { Add16Filled as AddIcon } from "@vicons/fluent";

const startID = ref(1);
const router = useRouter();
const formData = reactive({
  id: null,
  eventName: null,
  creatorName: null,
  creatorEmail: null,
  members: [
    {
      id: 1,
      label: "Участник 1",
      name: null,
      expense: [],
    },
  ],
});

const formRef = ref(null);

const rules = {
  eventName: {
    required: true,
    message: "Обязательное поле",
  },
  creatorName: {
    required: true,
    message: "Обязательное поле",
  },
  creatorEmail: {
    required: false,
    validator(rule, value) {
      if (!value) return true;
      if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(value)) {
        return new Error("Введен не корректный email");
      }
      return true;
    },
    trigger: ["change"],
  },
};

const addMember = () => {
  startID.value++;
  formData.members.push({
    id: startID.value,
    label: `Участник ${startID.value + 1}`,
    name: null,
    expense: [],
  });
};

const removeMember = (id) => {
  startID.value--;
  formData.members = formData.members
    .filter((itm) => itm.id !== id)
    .map((itm, idx) => ({
      id: idx,
      label: `Участник ${idx + 1}`,
      name: itm.name,
      expense: [],
    }));
};

const createEvent = () => {
  formRef.value?.validate((errors) => {
    if (!errors) {
      const eventID = uuidv4();
      formData.id = eventID;
      localStorage.setItem("_eventData", JSON.stringify(formData));
      router.push(`/event/${eventID}`);
    } else {
      console.log(errors);
    }
  });
};
</script>
<template>
  <n-layout>
    <!-- <n-layout-header> HEADER </n-layout-header> -->
    <n-layout-content class="content-style" content-style="padding: 24px;">
      <n-row justify-content="center">
        <n-col :span="6">
          <n-card title="Создать событие">
            <n-form ref="formRef" :model="formData" :rules="rules">
              <n-form-item label="Название" path="eventName">
                <n-input
                  v-model:value="formData.eventName"
                  placeholder="Пример: День рождения"
                ></n-input>
              </n-form-item>
              <n-divider
                style="margin-top: 5px; margin-bottom: 5px"
              ></n-divider>
              <n-h4>Участники</n-h4>
              <n-form-item label="Вы" path="creatorName">
                <n-input
                  v-model:value="formData.creatorName"
                  placeholder="Ваше имя"
                ></n-input>
              </n-form-item>
              <n-form-item label="Email" path="creatorEmail">
                <n-input
                  v-model:value="formData.creatorEmail"
                  placeholder="Веедите Email (рекомендуется)"
                ></n-input>
              </n-form-item>
              <n-divider
                style="margin-top: 2px; margin-bottom: 5px"
              ></n-divider>
              <n-form-item
                v-for="(itm, idx) in formData.members"
                :key="idx"
                :label="itm.label"
                style="margin-top: 8px"
                :path="`members[${idx}].name`"
                :rule="{
                  required: true,
                  message: `Введите имя участника ${idx + 1}`,
                  trigger: ['input'],
                }"
              >
                <n-input-group>
                  <n-input v-model:value="itm.name" placeholder="Имя"></n-input>
                  <n-button
                    v-if="idx !== 0"
                    type="error"
                    @click="removeMember(itm.id)"
                  >
                    -
                  </n-button>
                </n-input-group>
              </n-form-item>
              <n-button
                style="margin-top: 10px"
                type="primary"
                @click="addMember"
              >
                <n-icon :component="AddIcon"></n-icon>
                Добавить участника
              </n-button>
              <n-divider
                style="margin-top: 18px; margin-bottom: 18px"
              ></n-divider>
              <n-space justify="end">
                <n-button type="info" @click="createEvent">Создать</n-button>
              </n-space>
            </n-form>
          </n-card>
        </n-col>
      </n-row>
      <n-row>
        <pre>{{ JSON.stringify(formData, null, 2) }}</pre>
      </n-row>
    </n-layout-content>
  </n-layout>
</template>


<style>
.content-style {
  background: rgb(255, 255, 255);
  background: linear-gradient(
      45deg,
      rgba(255, 255, 255, 1) 0%,
      rgba(239, 251, 219, 1) 100%
    ),
    linear-gradient(
      180deg,
      rgba(255, 255, 255, 1) 0%,
      rgba(239, 251, 219, 1) 100%
    );
}
</style>