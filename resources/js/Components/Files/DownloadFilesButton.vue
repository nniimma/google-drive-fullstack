<template>
    <primary-button @click="download">
        <ArrowDownOnSquareStackIcon class="h-5 w-5 mr-2" />
        Download
    </primary-button>
</template>
<Toast />
<script setup>
    // Imports
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import {
        httpGet
    } from '@/Helper/Http-helper';
    import {
        ArrowDownOnSquareStackIcon
    } from '@heroicons/vue/20/solid';
    import {
        usePage
    } from '@inertiajs/vue3';
    import {
        useToast
    } from 'primevue';

    // Uses
    const page = usePage();
    const toast = useToast();

    // Refs

    // Props & Emits
    const props = defineProps({
        all: {
            type: Boolean,
            required: false,
            default: false
        },
        ids: {
            type: Array,
            required: false
        },
        sharedWithMe: false,
        sharedByMe: false
    });

    // Computed
    // Methods
    function download() {
        if (!props.all && props.ids.length == 0) {
            toast.add({
                severity: 'error',
                summary: 'Error Message',
                detail: 'Please select at least one file to download.',
                life: 3000
            });
            return;
        };


        const p = new URLSearchParams()
        if (page.props.folder?.id) {
            p.append('parent_id', page.props.folder?.id)
        }
        if (props.all) {
            p.append('all', props.all ? 1 : 0)
        } else {
            for (let id of props.ids) {
                p.append('ids[]', id)
            }
        }

        let url = route('files.download');
        if (props.sharedWithMe) {
            url = route('files.downloadSharedWithMe');
        } else if (props.sharedByMe) {
            url = route('files.downloadSharedByMe');
        }

        httpGet(url + '?' + p.toString()).then(response => {
            if (response.message) {
                toast.add({
                    severity: 'error',
                    summary: 'Error Message',
                    detail: response.message,
                    life: 3000
                });

            }

            if (!response.url) return;

            const a = document.createElement('a');
            a.download = response.fileName;
            a.href = response.url;
            a.click();

        });
    }

    // Hooks
</script>

<style scoped>

</style>
