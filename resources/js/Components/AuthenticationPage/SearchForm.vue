<template>
    <div class="w-[600px] h-[80px] flex items-center">
        <text-input class="block w-full mr-2" type="text" v-model="search" autocomplete 
        placeholder="Search for files and folders" />
        <!-- @keyup.enter.prevent="onSearch" -->
    </div>
</template>

<script setup>
  // Imports
  import TextInput from '@/Components/TextInput.vue';
  import { ref } from 'vue';

  // Uses

  // Refs
  const search = ref('')

  // Props & Emits

  // Computed

  // Methods

  // Hooks

</script>

<style scoped>
</style>