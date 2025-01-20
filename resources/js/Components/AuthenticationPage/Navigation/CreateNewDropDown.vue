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
                    <a href="#" @click.prevent="confirm1"
                        class="text-gray-700 block px-4 py-2 text-sm">New Folder</a>
                    </MenuItem>
                </div>
                <div class="px-1 py-1">
                    <!-- <file-upload-menu-item/>
                    <folder-upload-menu-item/> -->
                </div>
            </MenuItems>
        </transition>
    </Menu>
    <ConfirmDialog :dismissableMask="true"></ConfirmDialog>
    <Toast />
</template>

<script setup>
    // Imports
    import {
        Menu,
        MenuButton,
        MenuItems,
        MenuItem
    } from '@headlessui/vue'
    import {
        ref
    } from 'vue'
    import ConfirmDialog from 'primevue/confirmdialog';
    import Toast from 'primevue/toast';
    import {
        useConfirm
    } from "primevue/useconfirm";
    import { useToast } from 'primevue/usetoast';
    // import FileUploadMenuItem from '@/Components/AuthenticationPage/Navigation/CreateNewDropDown/FileUploadMenuItem.vue'
    // import FolderUploadMenuItem from '@/Components/AuthenticationPage/Navigation/CreateNewDropDown/FolderUploadMenuItem.vue'

    // Uses
    const confirm = useConfirm();
    const toast = useToast();

    // Refs

    // Props & Emits

    // Computed

    // Methods
    const confirm1 = () => {
        confirm.require({
            
            message: 'Are you sure you want to proceed?',
            header: 'Confirmation',
            // npm install primeicons
            icon: 'pi pi-exclamation-triangle',
            rejectProps: {
                label: 'Cancel',
                severity: 'secondary',
                outlined: true
            },
            acceptProps: {
                label: 'Save',
                severity: 'primary',
                outlined: true
            },
            accept: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Confirmed',
                    detail: 'You have accepted',
                    life: 3000
                });
            },
            reject: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Rejected',
                    detail: 'You have rejected',
                    life: 3000
                });
            }
        });
    };

    // Hooks
</script>

<style scoped>

</style>
