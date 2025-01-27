<template>

    <Head title="Shared With Me" />

    <AuthenticatedLayout>
        <nav class="flex items-center justify-end p-1 mb-3">
            <div class="flex">
                <download-files-button class="mr-2" :all="allSelected" :ids="selectedIds" :shared-with-me="true"/>
            </div>
        </nav>
        <div class="flex-1 overflow-auto">
            <DataTable v-model:selection="selectedProduct" @update:selection="handleSelectionChange" paginator
                :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]" :value="files.data" dataKey="id"
                tableStyle="min-width: 50rem;" class="cursor-default" scrollHeight="600px"
                :rowClass="rowClass">

                <template #empty>
                    <div class="p-4 text-center text-gray-500 dark:text-gray-200">
                        There is no data in this folder.
                    </div>
                </template>

                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column header="Name">
                    <template #body="slotProps">
                        <div class="flex gap-2">
                            <file-icon :file="slotProps.data" />
                            {{ slotProps . data . name }}
                        </div>
                    </template>
                </Column>
                <Column field="path" header="Path"></Column>
            </DataTable>

        </div>

    </AuthenticatedLayout>
</template>

<script setup>
    // Imports
    import {
        Head,
    } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import FileIcon from '@/Components/Files/FileIcon.vue';
    import {
        computed,
        ref
    } from 'vue';
    import DataTable from 'primevue/datatable';
    import Column from 'primevue/column';
    import DownloadFilesButton from '@/Components/Files/DownloadFilesButton.vue';

    // Uses

    // Props & Emits
    const props = defineProps({
        files: Object,
        folder: Object,
        ancestors: Object
    });

    // Refs
    const allSelected = ref(false);
    const selectedIds = ref([]);
    const selectedProduct = ref([]);

    // Computed
    const rowClass = computed(() => {
        return (rowData) => {
            return selectedProduct.value.some(product => product.id === rowData.id) ?
                '!bg-gray-800' :
                '';
        };
    });
    
    // Methods
    const handleSelectionChange = () => {
        allSelected.value = selectedProduct.value.length == props.files.data.length;

        if (selectedProduct.value.length == 0) {
            selectedIds.value = [];
        }

        selectedProduct.value.forEach(item => {
            if (!selectedIds.value.includes(item.id)) {
                selectedIds.value.push(item.id);
            }

            selectedIds.value = selectedIds.value.filter(id =>
                selectedProduct.value.some(item => item.id === id)
            );
        });
    }
    
    // Hooks

</script>
