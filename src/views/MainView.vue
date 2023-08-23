<script setup>
import { ref, reactive, watch } from "vue";
import { v4 as uuidv4 } from "uuid";
import { useRoute, useRouter } from "vue-router";
import { useEvent } from "@/store/event";
import { useUserMessage } from "@/store/message";
import { storeToRefs } from "pinia";

import {
  NLayout,
  NLayoutHeader,
  NLayoutContent,
  NLayoutFooter,
  NCard,
  NSpace,
  NGrid,
  NGi,
  NForm,
  NFormItem,
  NInput,
  NButton,
  NDivider,
  NH4,
  NIcon,
  NInputGroup,
  useMessage,
} from "naive-ui";
import { Add16Filled as AddIcon } from "@vicons/fluent";

const message = useMessage();
const userMessage = useUserMessage();
const { isVisible } = storeToRefs(userMessage);

const startID = ref(1);
const router = useRouter();
const l_save = ref(false);
const formData = reactive({
  id: null,
  eventName: null,
  creatorName: null,
  creatorEmail: null,
  members: [
    {
      id: uuidv4(),
      label: "Участник 1",
      name: null,
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

watch(isVisible, () => {
  if (isVisible.value === true) {
    const messageInfo = userMessage.message;
    if (messageInfo.type === "error") {
      message.error(messageInfo.content);
    } else if (messageInfo.type == "success") {
      message.success(messageInfo.content);
    }
  }
});

const addMember = () => {
  startID.value++;
  formData.members.push({
    id: uuidv4(),
    label: `Участник ${startID.value}`,
    name: null,
  });
};

const removeMember = (id) => {
  startID.value--;
  formData.members = formData.members
    .filter((itm) => itm.id !== id)
    .map((itm, idx) => ({
      id: String(idx),
      label: `Участник ${idx + 1}`,
      name: itm.name,
      expense: [],
    }));
};

const createEvent = () => {
  formRef.value?.validate((errors) => {
    if (!errors) {
      l_save.value = true;
      const eventID = uuidv4();
      formData.id = eventID;
      const event = useEvent();
      event
        .addEvent(formData)
        .then(() => {
          l_save.value = false;
          localStorage.setItem("_eventData", JSON.stringify(formData));
          router.push(`/event/${eventID}`);
        })
        .catch(() => {
          l_save.value = false;
        });
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
      <n-grid item-responsive>
        <n-gi offset="0 400:0 600:0 800:9" span="24 400:24 600:24 800:6">
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
                <n-button
                  type="info"
                  :loading="l_save"
                  :disabled="l_save"
                  @click="createEvent"
                  >Создать</n-button
                >
              </n-space>
            </n-form>
          </n-card>
        </n-gi>
      </n-grid>
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