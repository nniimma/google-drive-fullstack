<template>

    <Head title="Files" />
    <authenticated-layout>
        <nav class="flex items-center justify-between p-1 mb-3">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li v-for="ancestor of ancestors.data" :key="ancestor.id" class="inline-flex items-center">

                    <Link v-if="!ancestor.parent_id" :href="route('files.index')"
                        class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-white hover:text-blue-100 cursor-pointer gap-1">
                    <i class="pi pi-home"></i>
                    My Files
                    </Link>
                    <div v-else class="flex items-center">
                        <i class="pi pi-angle-right"></i>
                        <Link :href="route('files.index', { folder: ancestor.path })"
                            class="ml-1 text-sm font-medium text-gray-700 dark:text-white hover:text-blue-100 md:ml-2">
                        {{ ancestor . name }}
                        </Link>
                    </div>
                </li>
            </ol>

            <div class="flex">
                <label class="mr-2 border-2 border-gray-400 rounded-md p-2">
                    Only Favorites
                    <!-- <checkbox @change="showOnlyFavorites" v-model:checked="onlyFavorites" /> -->
                </label>
                <!-- <share-files-button :all-selected="allSelected" :selected-ids="selectedIds"/> -->
                <download-files-button class="mr-2" :all="allSelected" :ids="selectedIds" />
                <deleted-files-button :delete-all="allSelected" :delete-ids="selectedIds" @delete="onDelete" />
            </div>
        </nav>

        <DataTable v-model:selection="selectedProduct" @update:selection="handleSelectionChange" paginator
            :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]" :value="files.data" dataKey="id"
            tableStyle="min-width: 50rem;" @row-dblclick="openFolder" class="cursor-default" scrollHeight="600px"
            :rowClass="rowClass">

            <template #empty>
                <div class="p-4 text-center text-gray-500 dark:text-gray-200">
                    There is no data in this folder.
                </div>
            </template>

            <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
            <Column header="Favorite" style="width:20px">
                <template #body="slotProps">
                    <div class="flex gap-2" @click.stop.prevent="addRemoveFavorite(slotProps.data)">
                        <i class="pi pi-star-o"></i>
                        <StarSolidIcon v-if="slotProps.data.is_favourite" class="w-5 h-5 cursor-pointer" />
                        <StarOutlineIcon v-else class="w-5 h-5 cursor-pointer" />
                    </div>
                </template>
            </Column>
            <Column header="Name">
                <template #body="slotProps">
                    <div class="flex gap-2">
                        <file-icon :file="slotProps.data" />
                        {{ slotProps . data . name }}
                    </div>
                </template>
            </Column>
            <Column field="path" header="Path"></Column>
            <Column field="owner" header="Owner"></Column>
            <Column field="updated_at" header="Last Modified"></Column>
            <Column field="size" header="Size"></Column>
        </DataTable>
    </authenticated-layout>
    <Toast />
</template>

<script setup>
    // Imports
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import {
        computed,
        ref
    } from 'vue';
    import {
        Head,
        Link,
        router,
        useForm,
        usePage
    } from '@inertiajs/vue3';
    import {
        StarIcon as StarSolidIcon,
    } from '@heroicons/vue/20/solid';
    import {
        StarIcon as StarOutlineIcon
    } from '@heroicons/vue/24/outline'
    import FileIcon from '@/Components/Files/FileIcon.vue';
    import DataTable from 'primevue/datatable';
    import Column from 'primevue/column';
    import DeletedFilesButton from '@/Components/Files/DeletedFilesButton.vue';
    import DownloadFilesButton from '@/Components/Files/DownloadFilesButton.vue';
    import {
        useToast
    } from 'primevue';

    // Uses
    const toast = useToast();
    const page = usePage();
    const form = useForm({
        id: null,
        parent_id: null
    });


    // Refs
    const selectedProduct = ref([]);
    const allSelected = ref(false);
    const selectedIds = ref([]);

    // Props & Emits
    const props = defineProps({
        files: Object,
        folder: Object,
        ancestors: Object
    });

    // Computed

    // Methods
    const openFolder = (file) => {
        if (!file.data.is_folder) {
            return;
        }

        router.visit(route('files.index', {
            folder: file.data.path
        }));
    }

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

    const rowClass = computed(() => {
        return (rowData) => {
            return selectedProduct.value.some(product => product.id === rowData.id) ?
                '!bg-gray-800' :
                '';
        };
    });

    function onDelete() {
        allSelected.value = false;
        selectedProduct.value = [];
    }

    function addRemoveFavorite(file) {
        form.id = file.id;
        form.parent_id = page.props.folder.id;
        form.post(route('files.favorites'), {
            onSuccess: () => {
                const urlParams = new URLSearchParams(window.location.search);
                const stared = urlParams.get('stared');
                form.reset();
                if (stared == 0) {
                    toast.add({
                        severity: 'success',
                        summary: 'Success Message',
                        detail: 'File have been removed from favorite successfully.',
                        life: 3000
                    });
                } else {
                    toast.add({
                        severity: 'success',
                        summary: 'Success Message',
                        detail: 'File have been saved to favorite successfully.',
                        life: 3000
                    });
                }
            },
            onError: (error) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error Message',
                    detail: error,
                    life: 3000
                });
            }
        });
    }

    // Hooks
</script>

<style scoped>
</style>
