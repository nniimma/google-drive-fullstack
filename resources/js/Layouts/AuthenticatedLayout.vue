<template>
    <div class="h-screen flex w-full gap-4 p-5">
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
    <Toast />
    <FormProgress :form="fileUploadForm" />
</template>

<script setup>
    // Imports
    import Navigation from '@/Components/AuthenticationPage/Navigation.vue';
    import SearchForm from '@/Components/AuthenticationPage/SearchForm.vue';
    import UserSettingsDropdown from '@/Components/AuthenticationPage/UserSettingsDropdown.vue';
    import FormProgress from '@/Components/AuthenticationPage/FormProgress.vue'
    import {
        emitter,
        FILE_UPLOAD_STARTED
    } from '@/event-bus';
    import {
        useForm,
        usePage
    } from '@inertiajs/vue3';
    import {
        useToast
    } from 'primevue';
    import {
        onMounted,
        ref
    } from 'vue';
    import {
        onBeforeUnmount
    } from 'vue';

    // Uses
    const page = usePage();

    const fileUploadForm = useForm({
        files: [],
        relative_paths: [],
        parent_id: null
    });

    const toast = useToast();

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
        fileUploadForm.relative_paths = [...files].map(file => file.webkitRelativePath);

        fileUploadForm.post(route('files.store'), {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success Message',
                    detail: `${files.length} files uploaded successfully.`,
                    life: 3000
                });
            },
            onError: errors => {
                let message = '';
                if (Object.keys(errors).length > 0) {
                    message = errors[Object.keys(errors)[0]];
                } else {
                    message = 'Error during file upload please try again later.';
                }

                toast.add({
                    severity: 'error',
                    summary: 'Error Message',
                    detail: message,
                    life: 3000
                });
            },
            onFinish: () => {
                fileUploadForm.clearErrors();
                fileUploadForm.reset();
            }
        });
    }

    // Hooks
    onMounted(() => {
        // Remove any previously registered listeners
        emitter.off(FILE_UPLOAD_STARTED, uploadFiles);

        // Add the listener
        emitter.on(FILE_UPLOAD_STARTED, uploadFiles);
    });

    onBeforeUnmount(() => {
        // Clean up the listener when the component is destroyed
        emitter.off(FILE_UPLOAD_STARTED, uploadFiles);
    });
</script>
<style scoped>
    .dropzone {
        width: 100%;
        height: 100%;
        border: 2px dashed gray;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
