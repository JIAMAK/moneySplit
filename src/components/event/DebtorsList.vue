<script setup>
import { NDivider, NRow, NCol, NSpace, NH3, NList, NListItem } from "naive-ui";

const props = defineProps({
  items: Array,
});
</script>
<template>
  <n-divider></n-divider>
  <n-row>
    <n-col :span="24">
      <n-space justify="center">
        <n-h3>Кто кому должен</n-h3>
      </n-space>
      <n-row style="justify-content: center">
        <n-col :span="18">
          <n-list bordered>
            <n-list-item v-for="(itm, idx) in items" :key="idx">
              <n-space align="center" justify="space-between">
                <div style="display: flex">
                  <span class="stat"
                    >{{ itm.sender_name }} → {{ itm.receiver_name }}</span
                  >
                </div>
                <span class="stat">{{ itm.price }} р.</span>
              </n-space>
            </n-list-item>
          </n-list>
        </n-col>
      </n-row>
    </n-col>
  </n-row>
</template>


<style scoped>
</style>