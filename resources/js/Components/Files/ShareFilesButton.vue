<template>
    <primary-button @click="onClick"
        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900  border border-gray-200 rounded-lg hover:bg-blue-800 focus:z-10 focus:ring-2 focus:ring-blue-500 mr-2">
        <ShareIcon   class="h-4 w-4 mr-2" />
        Share
    </primary-button>

    <Toast/>
    <share-file-modal :share-dialog="shareDialog" :all-selected="allSelected" :selected-ids="selectedIds" @update-share-dialog="shareDialog = $event"/>
</template>

<script setup>
    // Imports
    import ShareFileModal from './ShareFileButton/ShareFileModal.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import {
        ShareIcon  
    } from '@heroicons/vue/20/solid';
    import {
        ref
    } from 'vue';
    import { useToast } from 'primevue';

    // Uses
    const toast = useToast();


    // Refs
    const shareDialog = ref(false);

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

    // Computed

    // Methods
    const onClick = () => {
        if (!props.allSelected && !props.selectedIds.length) {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Please select at least one file to share.',
                life: 3000
            });
        }else{
            shareDialog.value = true;
        }
    }

    // Hooks
</script>