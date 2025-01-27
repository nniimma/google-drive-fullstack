<template>
    <div class="w-[600px] h-[80px] flex items-center">
        <text-input class="block w-full mr-2" type="text" v-model="search" autocomplete @keyup.enter.prevent="onSearch"
            placeholder="Search for files and folders" />
    </div>
</template>

<script setup>
  // Imports
  import TextInput from '@/Components/TextInput.vue';
  import { onMounted, ref } from 'vue';
  import { router } from '@inertiajs/vue3';
import { emitter, ON_SEARCH } from '@/event-bus';

  // Uses

  // Refs
  const search = ref('');
  let params = '';

  // Props & Emits

  // Computed

  // Methods
  function onSearch() {
        // sending the written words to the url
        params.set('search', search.value);
        router.get(window.location.pathname + '?' + params.toString());
        // sending the written words to the url

        emitter.emit(ON_SEARCH, search.value)
    }

  // Hooks
  onMounted(() => {
        params = new URLSearchParams(window.location.search);
        if(params.get('search')){
          search.value = params.get('search');
        }
    });

</script>

<style scoped>
</style>