<template>
    <Menu as="div" class="relative block text-left">
            <!-- 
                https://headlessui.com/
                npm install @headlessui/vue@latest 
            -->
        <MenuButton
            class="flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-gray-300 hover:bg-gray-500 hover:text-gray-100">
            Create New
        </MenuButton>

        <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
            <MenuItems
                class="w-full absolute left-0 mt-2 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                <div class="px-1 py-1">
                    <!-- <MenuItem v-slot="{ active }"> -->
                    <MenuItem>
                    <a href="#" @click.prevent="showCreateFolderModal" class="text-gray-700 block px-4 py-2 text-sm">New Folder</a>
                    </MenuItem>
                </div>
                <div class="px-1 py-1">
                    <!-- <file-upload-menu-item/>
                    <folder-upload-menu-item/> -->
                </div>
            </MenuItems>
        </transition>
    </Menu>

</template>

<script setup>
    // Imports
    import {
        Menu,
        MenuButton,
        MenuItems,
        MenuItem
    } from '@headlessui/vue'
    import { ref } from 'vue'
    // import FileUploadMenuItem from '@/Components/AuthenticationPage/Navigation/CreateNewDropDown/FileUploadMenuItem.vue'
    // import FolderUploadMenuItem from '@/Components/AuthenticationPage/Navigation/CreateNewDropDown/FolderUploadMenuItem.vue'

    // Uses

    // Refs
    const createFolderModal = ref(false)

    // Props & Emits

    // Computed

    // Methods
    function showCreateFolderModal(){
        createFolderModal.value = true
    }

    // Hooks
</script>

<style scoped>

</style>