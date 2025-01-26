<template>
    <danger-button @click="onClick"
        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900  border border-gray-200 rounded-lg hover:bg-red-800 focus:z-10 focus:ring-2 focus:ring-blue-500 ms-2">
        <TrashIcon class="h-4 w-4 mr-2" />
        Delete Permanently
    </danger-button>
    <Toast />
</template>

<script setup>
    // Imports
    import DangerButton from '@/Components/DangerButton.vue';
    import {
        TrashIcon
    } from '@heroicons/vue/20/solid';
    import {
        useForm,
    } from '@inertiajs/vue3';
    import {
        useConfirm,
        useToast
    } from 'primevue';

    // Uses
    const form = useForm({
        all: null,
        ids: [],
        parent_id: null
    });
    const toast = useToast();
    const confirm = useConfirm();

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

    const emit = defineEmits(['delete']);

    // Computed

    // Methods
    function onClick() {
        if (!props.allSelected && !props.selectedIds.length) {
            toast.add({
                severity: 'error',
                summary: 'Error Message',
                detail: 'Please select at least one file to delete.',
                life: 3000
            });
        } else {
            console.log('hi');
            confirm.require({
                message: 'Are you sure you want to delete permanently?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                rejectProps: {
                    label: 'Cancel',
                    severity: 'secondary',
                    outlined: true
                },
                acceptProps: {
                    label: 'Delete',
                    severity: 'danger',
                    outlined: true
                },
                accept: () => {
                    onConfirm();
                },
                reject: () => {
                    console.log("Dialog closed");
                }
            });
        }
    }

    function onConfirm() {
        if (props.allSelected) {
            form.all = true
            form.ids = []
        } else {
            form.ids = props.selectedIds
        }
        form.delete(route('files.destroyPermanently'), {
            onSuccess: () => {
                emit('delete');
                toast.add({
                    severity: 'success',
                    summary: 'Success Message',
                    detail: 'The files/folders has been deleted permanently',
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
