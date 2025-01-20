<template>
    <Dialog :dismissableMask="true" v-model:visible="localFolderDialog" modal header="Create New Folder"
        :style="{ width: '25rem' }">
        <div class="mt-6">
            <input-label for="folderName" value="Folder Name" />

            <text-input ref="folderNameInput" type="text" id="folderName" v-model="form.name" class="mt-1 block w-full"
                :class="form.errors.name ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''"
                placeholder="New Folder" @keyup.enter="createFolder" />

            <input-error :message="form.errors.name" class="mt-2" />
        </div>
        <div class="mt-6 flex justify-end">
            <secondary-button @click="closeDialog">Cancle</secondary-button>
            <primary-button class="ml-3" :class="{ 'opacity-25': form.processing }" @click="createFolder"
                :disable="form.processing">
                Submit
            </primary-button>
        </div>
    </Dialog>
</template>

<script setup>
    // Imports
    import {
        ref,
        watch
    } from 'vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SecondaryButton from "@/Components/SecondaryButton.vue";
    import InputError from "@/Components/InputError.vue";
    import {
        useForm
    } from "@inertiajs/vue3";

    // Uses
    const form = useForm({
        name: '',
        parent_id: null
    })

    // Props & Emits
    const props = defineProps({
        folderDialog: {
            type: Boolean,
            required: true
        }
    });

    const emit = defineEmits(['update-folder-dialog']); 

    // Refs
    const localFolderDialog = ref(props.folderDialog);

    // Computed

    //watch
    watch(() => props.folderDialog, (newVal) => {
        localFolderDialog.value = newVal;
    });

    watch(localFolderDialog, (newVal) => {
        emit('update-folder-dialog', newVal);
    });

    // Methods
    const createFolder = () => {
        //
    }

    const closeDialog = () => {
        localFolderDialog.value = false;
    };

    // Hooks
</script>
