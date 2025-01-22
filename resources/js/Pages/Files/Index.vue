<template>

    <Head title="Files" />
    <authenticated-layout>
        <table class="min-w-full">
            <thead class="dark:bg-gray-800 border-b dark:border-gray-500 sticky top-0">
                <tr>
                    <th class="text-sm font-medium text-gray-900 dark:text-white px-6 py-4 text-left">
                      Name
                    </th>
                    <th class="text-sm font-medium text-gray-900 dark:text-white px-6 py-4 text-left">
                      Path
                    </th>
                    <th class="text-sm font-medium text-gray-900 dark:text-white px-6 py-4 text-left">
                      Owner
                    </th>
                    <th class="text-sm font-medium text-gray-900 dark:text-white px-6 py-4 text-left">
                      Last Modified
                    </th>
                    <th class="text-sm font-medium text-gray-900 dark:text-white px-6 py-4 text-left">
                      Size
                    </th>
                </tr>
            </thead>
            <tbody>
              <tr v-for="file in props.files.data" :key="file.id" class=" border-b transition duration-300 ease-in-out hover:bg-gray-900 cursor-default">
                <td @dblclick="openFolder(file)" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 gap-2">{{ file.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 gap-2">{{ file.path }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 gap-2">{{ file.owner }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 gap-2">{{ file.updated_at }}</td>
                <!-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 gap-2">{{ file.name }}</td> -->
              </tr>
            </tbody>
        </table>
        <div v-if="!props.files.data.length" class="py-8 text-center text-md text-gray-400">
                There is no data in this folder.
            </div>
    </authenticated-layout>
</template>

<script setup>
    // Imports
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import {
        ref
    } from 'vue';
    import {
        Head,
        router
    } from '@inertiajs/vue3';

    // Uses

    // Refs

    // Props & Emits
    const props = defineProps({
        files: Object
    });

    // Computed

    // Methods
    const openFolder = (file) => {
      if(!file.is_folder){
        return
      }

      router.visit(route('files.index', {folder: file.path}))
    }

    // Hooks
</script>

<style scoped>
</style>
