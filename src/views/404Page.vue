<script setup>
import router from "@/router/";
import { NResult, NButton } from "naive-ui";
</script>
<template>
  <div class="wrap-404 content-style">
    <n-result
      status="404"
      title="404 Not Found"
      description="Событие не найдено"
    >
      <template #footer>
        <n-button type="primary" @click="router.push('/')">На главную</n-button>
      </template>
    </n-result>
  </div>
</template>


<style scoped>
.wrap-404 {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}
</style>