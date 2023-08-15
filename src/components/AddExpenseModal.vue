<script setup>
import { ref, toRefs, reactive, watch } from "vue";
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
} from "naive-ui";

const props = defineProps({
  dialog: { typr: Boolean },
  activeUser: { type: [String, Number] },
});
const showModal = ref(false);
const typeSplit = ref("1");
const checkedMember = ref([]);
const formData = reactive({});
const expenseDialog = toRefs(props).dialog;
const expenseData = reactive({
  summ: null,
  name: null,
});

watch(expenseDialog, () => {
  console.log(expenseDialog.value);
  if (expenseDialog.value == true) {
    formData = JSON.parse(localStorage.getItem("_eventData"));
  }
  showModal.value = expenseDialog;
});
</script>
<template>
  <n-modal
    v-model:show="showModal"
    style="width: 400px"
    preset="card"
    title="Добавить расход"
  >
    <n-form>
      <n-form-item label="Сумма">
        <n-input
          v-model:value="expenseData.summ"
          placeholder="Введите сумму"
        ></n-input>
      </n-form-item>
      <n-form-item label="За что?">
        <n-input
          v-model:value="expenseData.name"
          placeholder="За что платили"
        ></n-input>
      </n-form-item>
      <n-form-item :show-feedback="false" :show-label="false">
        <n-space vertical>
          <n-radio-group v-model:value="typeSplit">
            <n-radio value="1">Делить поровну</n-radio>
            <n-radio value="2">Делить по-другому</n-radio>
          </n-radio-group>
        </n-space>
      </n-form-item>
      <n-form-item v-if="typeSplit == 2">
        <n-tabs type="line" animated>
          <n-tab-pane name="equally" tab="Поровну">
            <n-card>
              <n-checkbox-group v-model:value="checkedMember">
                <n-space>
                  <n-checkbox
                    v-for="itm in formData.members"
                    :key="itm.id"
                    :value="itm.name"
                    :label="itm.name"
                  />
                </n-space>
              </n-checkbox-group>
            </n-card>
          </n-tab-pane>
          <n-tab-pane name="summ" tab="Сумма"> </n-tab-pane>
          <n-tab-pane name="proportion" tab="Пропорции"> </n-tab-pane>
        </n-tabs>
      </n-form-item>
    </n-form>
    <template #action>
      <n-button block type="info">Добавить</n-button>
    </template>
  </n-modal>
</template>


<style scoped>
</style>