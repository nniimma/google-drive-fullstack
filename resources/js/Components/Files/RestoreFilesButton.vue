<template>
    <secondary-button @click="onClick"
        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900  border border-gray-200 rounded-lg hover:bg-blue-800 focus:z-10 focus:ring-2 focus:ring-blue-500 ">
        <ArrowLeftOnRectangleIcon class="h-4 w-4 mr-2" />
        Restore
    </secondary-button>
    <ConfirmDialog :dismissableMask="true"></ConfirmDialog>
    <Toast />
</template>

<script setup>
    // Imports
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import {
        ArrowLeftOnRectangleIcon
    } from '@heroicons/vue/20/solid';
    import {
        useForm,
        usePage
    } from '@inertiajs/vue3';
    import {
        ref
    } from 'vue';
    import {
        useConfirm,
        useToast
    } from 'primevue';
    import ConfirmDialog from 'primevue/confirmdialog';

    // Uses
    const page = usePage();
    const toast = useToast();
    const confirm = useConfirm();
    const form = useForm({
        all: null,
        ids: [],
        parent_id: null
    });

    // Refs

    // Props & Emits
    const props = defineProps({
        allSelected: {
            type: Boolean,
            required: false,
            default: false
        },
        selectedIds: {
            type: Array,
            required: false
        }
    });
    const emit = defineEmits(['restore']);

    // Computed

    // Methods
    function onClick() {
        if (!props.allSelected && !props.selectedIds.length) {
            toast.add({
                severity: 'error',
                summary: 'Error Message',
                detail: 'Please select at least one file to restore.',
                life: 3000
            });
        } else {

            confirm.require({
                message: 'Are you sure you want to restore?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                rejectProps: {
                    label: 'Cancel',
                    severity: 'secondary',
                    outlined: true
                },
                acceptProps: {
                    label: 'Restore',
                    severity: 'success',
                    outlined: true
                },
                accept: () => {
                    onRestoreConfirm();
                },
                reject: () => {
                    //
                }
            });
        }

    }

    const onRestoreConfirm = () => {
        if (props.allSelected) {
            form.all = true;
        } else {
            form.ids = props.selectedIds;
        }

        form.post(route('files.restore'), {
            onSuccess: () => {
                emit('restore');
                toast.add({
                    severity: 'success',
                    summary: 'Success Message',
                    detail: 'The files/folders has been restored.',
                    life: 3000
                });
            },
            onError: (error) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error Message',
                    detail: error,
                    life: 3000
                });
            }
        })
    }

    // Hooks
</script>

<style scoped>
</style>
