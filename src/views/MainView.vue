<script setup>
import { ref, onMounted } from "vue";
import {
  NLayout,
  NLayoutContent,
  NCard,
  NGrid,
  NGi,
  NH1,
  NH3,
  NList,
  NListItem,
  NThing,
  NButton,
} from "naive-ui";

const events = ref([]);

onMounted(() => {
  events.value = JSON.parse(localStorage.getItem("_events"));
});
</script>
<template>
  <n-layout>
    <n-layout-content class="content-style" content-style="padding: 24px;">
      <n-grid item-responsive>
        <n-gi offset="0 400:0 600:0 800:9" span="24 400:24 600:24 800:6">
          <n-card>
            <template #header>
              <n-h1 align="center">Добро пожаловать</n-h1>
            </template>
            <div v-if="events">
              <n-h3>Последние события:</n-h3>
              <n-list bordered hoverable clickable>
                <n-list-item v-for="itm in events" :key="itm.event_id">
                  <router-link
                    :to="{ name: 'event', params: { id: itm.event_id } }"
                  >
                    <n-thing :title="itm.event_name">
                      <template #description>
                        {{ new Date(itm.cr_datetime).toLocaleDateString() }}
                      </template>
                    </n-thing>
                  </router-link>
                </n-list-item>
              </n-list>
            </div>

            <template #footer>
              <router-link :to="{ path: '/add' }">
                <n-button type="info" block>Добавить событие</n-button>
              </router-link>
            </template>
          </n-card>
        </n-gi>
      </n-grid>
    </n-layout-content>
  </n-layout>
</template>


<style scoped>
a {
  text-decoration: none;
}
</style>