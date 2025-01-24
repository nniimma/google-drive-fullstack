<template>
    <danger-button @click="onDeleteClick"
        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900  border border-gray-200 rounded-lg hover:bg-red-700 focus:z-10 focus:ring-2 focus:ring-blue-500 ">
        <TrashIcon class="h-4 w-4 mr-2" />
        Delete
    </danger-button>
    <Toast />
    <ConfirmDialog :dismissableMask="true"></ConfirmDialog>
</template>

<script setup>
    // Imports
    import DangerButton from '@/Components/DangerButton.vue';
    import ConfirmDialog from 'primevue/confirmdialog';
    import {
        TrashIcon
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

    // Uses
    const page = usePage();
    const deleteFilesForm = useForm({
        all: null,
        ids: [],
        parent_id: null
    });
    const confirm = useConfirm();
    const toast = useToast();

    // Refs

    // Props & Emits
    const props = defineProps({
        deleteAll: {
            type: Boolean,
            required: false,
            default: false
        },
        deleteIds: {
            type: Array,
            required: false
        }
    })

    const emit = defineEmits(['delete']);

    // Computed

    // Methods
    const onDeleteClick = () => {
        if(!props.deleteAll && !props.deleteIds.length){
            toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Please select at least one file.',
                    life: 3000
                });
        }else{
            confirm.require({
                message: 'Are you sure you want to delete?',
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
                    onDeleteConfirm();
                },
                reject: () => {
                    //
                }
            });
        }
    };

    function onDeleteConfirm() {
        deleteFilesForm.parent_id = page.props.folder.id;

        if (props.deleteAll) {
            deleteFilesForm.all = true;
        } else {
            deleteFilesForm.ids = props.deleteIds;
        }

        deleteFilesForm.delete(route('files.destroy'), {
            onSuccess: () => {
                emit('delete');

                toast.add({
                    severity: 'success',
                    summary: 'Confirmed',
                    detail: 'The files deleted successfully.',
                    life: 3000
                });
            },
            onError: {

            }
        });
    }

    // Hooks
</script>
