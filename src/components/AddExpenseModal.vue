<script setup>
import { ref, toRefs, reactive, watch } from "vue";
import { useEvent } from "../store/event";
import {
  NModal,
  NCard,
  NForm,
  NFormItem,
  NInput,
  NButton,
  NRadio,
  NRadioGroup,
  NSpace,
  NTabs,
  NTabPane,
  NCheckboxGroup,
  NCheckbox,
  NGrid,
  NGi,
} from "naive-ui";

const props = defineProps({
  dialog: { type: Boolean },
  activeUser: { type: [String, Number] },
  editItem: { type: Object, default: {} },
});
const emits = defineEmits(["update:dialog"]);
const typeSplit = ref("1");
const l_save = ref(false);
const formData = reactive({});
const expenseDialog = toRefs(props).dialog;
const formRef = ref(null);
const defaultExpenseData = {
  member_id: null,
  summ: null,
  name: null,
  debtor_ids: [],
};
const expenseData = reactive({
  payment_id: null,
  member_id: null,
  summ: null,
  name: null,
  debtor_ids: [],
});

const rules = {
  summ: {
    required: true,
    validator(rule, value) {
      if (!value) return new Error("Введите сумму");
      if (!/^\d*$/.test(value)) return new Error("Сумма должна быть числом");
      return true;
    },
    trigger: "change",
  },
  name: {
    required: true,
    message: "Введите за что платили",
    trigger: "change",
  },
  debtor_ids: {
    required: true,
    validator(rule, value) {
      if (!value) return false;
      if (value.length < 2) return new Error("Выберите минимум 2 пользователя");
      return true;
    },
    message: "Выберите минимум 2 пользователя",
    trigger: "change",
  },
};

watch(expenseDialog, () => {
  if (expenseDialog.value == true) {
    const event = useEvent();
    Object.assign(formData, event.getEventReuslt);
    expenseData.member_id = props.activeUser;
    expenseData.debtor_ids = formData.members.map((itm) => itm.member_id);
    if (Object.keys(props.editItem).length !== 0) {
      expenseData.payment_id = props.editItem.payment_id;
      expenseData.debtor_ids = JSON.parse(props.editItem.debtor_ids);
      expenseData.member_id = props.editItem.member_id;
      expenseData.summ = props.editItem.price;
      expenseData.name = props.editItem.payment_name;
    }
  }
});

const addPayments = () => {
  formRef.value?.validate((errors) => {
    if (!errors) {
      const event = useEvent();
      l_save.value = true;
      if (expenseData.payment_id !== null) {
        event.updatePayment(expenseData).then(() => {
          closeModal();
          l_save.value = false;
        });
      } else {
        event
          .addPayments(expenseData)
          .then(() => {
            closeModal();
            l_save.value = false;
          })
          .catch(() => {
            l_save.value = false;
          });
      }
    }
  });
};

const resetForm = () => {
  Object.assign(expenseData, defaultExpenseData);
  typeSplit.value = "1";
};

const closeModal = () => {
  emits("update:dialog", false);
  resetForm();
};
</script>
<template>
  <n-modal
    v-model:show="expenseDialog"
    style="width: 400px"
    preset="card"
    title="Добавить расход"
    :on-update:show="closeModal"
  >
    <n-form ref="formRef" :model="expenseData" :rules="rules">
      <n-form-item label="Сумма" path="summ">
        <n-input
          v-model:value="expenseData.summ"
          placeholder="Введите сумму"
        ></n-input>
      </n-form-item>
      <n-form-item label="За что?" path="name">
        <n-input
          v-model:value="expenseData.name"
          placeholder="За что платили"
        ></n-input>
      </n-form-item>
      <!-- <n-form-item :show-feedback="false" :show-label="false">
        <n-radio-group v-model:value="typeSplit">
          <n-space vertical>
            <n-radio value="1">Делить поровну</n-radio>
            <n-radio value="2">Делить по-другому</n-radio>
          </n-space>
        </n-radio-group>
      </n-form-item> -->
      <n-form-item path="debtor_ids" :show-label="false">
        <n-card>
          <n-checkbox-group v-model:value="expenseData.debtor_ids">
            <n-grid :y-gap="8" :cols="2">
              <n-gi v-for="itm in formData.members" :key="itm.member_id">
                <n-checkbox
                  v-model:checked="itm.member_id"
                  :checked="itm.member_id in expenseData.debtor_ids"
                  :value="itm.member_id"
                  :label="itm.name"
                  selected
                />
              </n-gi>
            </n-grid>
          </n-checkbox-group>
        </n-card>
      </n-form-item>
      <!-- <n-form-item v-if="typeSplit == 2">
        <n-tabs type="line" animated>
          <n-tab-pane name="equally" tab="Поровну">
            <n-card>
              <n-checkbox-group v-model:value="expenseData.debtor_ids">
                <n-grid :y-gap="8" :cols="2">
                  <n-gi v-for="itm in formData.members" :key="itm.member_id">
                    <n-checkbox
                      v-model:checked="itm.member_id"
                      checked
                      :value="itm.member_id"
                      :label="itm.name"
                      selected
                    />
                  </n-gi>
                </n-grid>
              </n-checkbox-group>
            </n-card>
          </n-tab-pane>
          <n-tab-pane name="summ" tab="Сумма"> </n-tab-pane>
          <n-tab-pane name="proportion" tab="Пропорции"> </n-tab-pane>
        </n-tabs>
      </n-form-item> -->
    </n-form>
    <template #action>
      <n-button
        block
        type="info"
        :loading="l_save"
        :disabled="l_save"
        @click="addPayments"
      >
        {{ !expenseData.payment_id ? "Добавить" : "Обновить" }}
      </n-button>
    </template>
  </n-modal>
</template>


<style scoped>
</style>