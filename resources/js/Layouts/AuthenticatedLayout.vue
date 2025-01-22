<template>
    <div class="h-screen flex w-full gap-4">
        <Navigation />

        <main @drop.prevent="handleDrop" @dragover.prevent="onDragOver" @dragleave.prevent="onDragLeave"
            class="flex flex-col flex-1 px-4" :class="dragOver ? 'dropzone' : ''">
            <template v-if="dragOver">
                <div class="text-gray-500 dark:text-gray-100 text-center py-8 text-sm">
                    Drop files here to upload
                </div>
            </template>
            <template v-else>
                <div class="flex items-center justify-between w-full">
                    <SearchForm />
                    <UserSettingsDropdown />
                </div>
                <div class="flex flex-1 flex-col overflow-auto">
                    <slot />
                </div>
            </template>
        </main>
    </div>
</template>

<script setup>
    // Imports
    import Navigation from '@/Components/AuthenticationPage/Navigation.vue';
    import SearchForm from '@/Components/AuthenticationPage/SearchForm.vue';
    import UserSettingsDropdown from '@/Components/AuthenticationPage/UserSettingsDropdown.vue';
    import {
        emitter,
        FILE_UPLOAD_STARTED
    } from '@/event-bus';
    import { useForm, usePage } from '@inertiajs/vue3';
    import {
        onMounted,
        ref
    } from 'vue';

    // Uses
    const page = usePage();

    const fileUploadForm = useForm({
        files: [],
        parent_id: null
    });

    // Refs
    const dragOver = ref(false);

    // Props & Emits

    // Computed

    // Methods
    const onDragOver = () => {
        dragOver.value = true;
    }

    const onDragLeave = () => {
        dragOver.value = false;
    }

    const handleDrop = (event) => {
        dragOver.value = false;
        const files = event.dataTransfer.files;
        if (!files.length) {
            return;
        }

        uploadFiles(files);
    }

    const uploadFiles = (files) => {
        fileUploadForm.parent_id = page.props.folder.id;
        fileUploadForm.files = files;

        fileUploadForm.post(route('files.store'));
    }

    // Hooks
    onMounted(() => {
        emitter.on(FILE_UPLOAD_STARTED, uploadFiles);
    });
</script>
<style scoped>
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-track {
        background-color: #959595;
    }

    .dropzone {
        width: 100%;
        height: 100%;
        border: 2px dashed gray;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
