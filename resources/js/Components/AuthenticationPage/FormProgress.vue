<template>
    <div v-if="form.progress" class="absolute bottom-[50px] right-[15px] bg-gray-200 dark:bg-gray-800 p-5 rounded-xl border dark:border-white">
        <p class="mb-2 text-sm">Uploading {{ form.files.length }} files</p>
        <ProgressBar :value="form.progress.percentage" style="height: 10px; width: 250px;" />
    </div>
</template>

<script setup>
  // Imports
  import ProgressBar from 'primevue/progressbar';

  // Uses

  // Props & Emits
  const props = defineProps({
        form: Object
    });

  // Refs

  // Computed

  // Methods

  // Hooks

</script>
