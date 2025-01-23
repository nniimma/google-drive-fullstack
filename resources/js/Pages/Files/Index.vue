<template>

    <Head title="Files" />
    <authenticated-layout>
        <nav class="flex items-center justify-between p-1 mb-3">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li v-for="ancestor of ancestors.data" :key="ancestor.id" class="inline-flex items-center">

                    <Link v-if="!ancestor.parent_id" :href="route('files.index')"
                        class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-white hover:text-blue-100 cursor-pointer gap-1">
                    <i class="pi pi-home"></i>
                    My Files
                    </Link>
                    <div v-else class="flex items-center">
                      <i class="pi pi-angle-right"></i>
                      <Link :href="route('files.index', { folder: ancestor.path })"
                      class="ml-1 text-sm font-medium text-gray-700 dark:text-white hover:text-blue-100 md:ml-2">
                        {{ ancestor.name }}
                        </Link>
                    </div>
                </li>
            </ol>

            <!-- <div class="flex">
                <label class="mr-2 border-2 border-gray-400 rounded-md p-2">
                    Only Favorites
                    <checkbox @change="showOnlyFavorites" v-model:checked="onlyFavorites" />
                </label>
                <share-files-button :all-selected="allSelected" :selected-ids="selectedIds"/>
                <download-files-button class="mr-2" :all="allSelected" :ids="selectedIds" />
                <delete-files-button :delete-all="allSelected" :delete-ids="selectedIds" @delete="onDelete" />
            </div> -->
        </nav>

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
                <tr v-for="file in files.data" :key="file.id" @dblclick="openFolder(file)"
                    class=" border-b transition duration-300 ease-in-out hover:bg-gray-900 cursor-default">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 flex gap-2">
                            <file-icon :file="file" />
                            {{ file . name }}
                        </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 gap-2">
                        {{ file . path }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 gap-2">
                        {{ file . owner }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 gap-2">
                        {{ file . updated_at }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 gap-2">{{ file . size }}</td>
                </tr>
            </tbody>
        </table>
        <div v-if="!files.data.length" class="py-8 text-center text-md text-gray-400">
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
        Link,
        router
    } from '@inertiajs/vue3';
    import {
        StarIcon as StarSolidIcon,
    } from '@heroicons/vue/20/solid'
    import FileIcon from '@/Components/Files/FileIcon.vue';

    // Uses

    // Refs

    // Props & Emits
    const props = defineProps({
        files: Object,
        folder: Object,
        ancestors: Object
    });

    // Computed

    // Methods
    const openFolder = (file) => {
        if (!file.is_folder) {
            return
        }

        router.visit(route('files.index', {
            folder: file.path
        }))
    }

    // Hooks
</script>

<style scoped>
</style>
