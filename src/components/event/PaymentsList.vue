<script setup>
import { ref } from "vue";
import {
  NRow,
  NCol,
  NSpace,
  NList,
  NListItem,
  NH3,
  NButton,
  NIcon,
} from "naive-ui";
import { Edit20Filled as EditIcon } from "@vicons/fluent";
const props = defineProps({
  items: Array,
});

const emits = defineEmits(["edit:payment"]);
const visibleItems = ref(false);
</script>
<template>
  <n-row v-if="items.length !== 0">
    <n-col :span="24">
      <n-space justify="center">
        <n-h3>Обзор затрат </n-h3>
      </n-space>
    </n-col>
    <n-row style="justify-content: center">
      <n-col :span="18">
        <n-list bordered>
          <div v-for="(itm, idx) in items" :key="idx">
            <n-list-item v-if="idx < 4 || visibleItems">
              <n-space align="center" justify="space-between">
                <div style="display: flex; flex-direction: column">
                  <span class="stat">{{ itm.name }}</span>
                  <span class="stat-description">{{ itm.payment_name }}</span>
                </div>
                <span class="stat">
                  {{ itm.price }} р.
                  <n-button
                    text
                    style="margin-left: 5px; font-size: 20px"
                    @click="emits('edit:payment', itm)"
                  >
                    <n-icon :component="EditIcon"></n-icon>
                  </n-button>
                </span>
              </n-space>
            </n-list-item>
          </div>
        </n-list>
      </n-col>
    </n-row>
    <n-row style="justify-content: center">
      <n-col style="margin-top: 8px" :span="6">
        <n-button
          text
          block
          type="primary"
          @click="visibleItems = !visibleItems"
        >
          {{ !visibleItems ? "Посмотреть всех" : "Свернуть" }}
        </n-button>
      </n-col>
    </n-row>
  </n-row>
</template>


<style scoped>
</style>