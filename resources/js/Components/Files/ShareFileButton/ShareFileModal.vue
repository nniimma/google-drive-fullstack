<template>
<Dialog :dismissableMask="true" v-model:visible="localShareDialog" modal header="Edit Profile" :style="{ width: '25rem' }">
    <div class="mt-6">
                <input-label for="shareEmail" value="Email:" />

                <text-input ref="emailInput" type="text" id="shareEmail" v-model="form.email" class="mt-1 block w-full"
                    :class="form.errors.email ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''"
                    placeholder="example@mail.com" @keyup.enter="share" />

                <input-error :message="form.errors.email" class="mt-2" />
            </div>
    <div class="mt-6 flex justify-end">
            <secondary-button @click="closeDialog">Cancle</secondary-button>
            <primary-button class="ml-3" :class="{ 'opacity-25': form.processing }" @click="share"
                :disable="form.processing">
                Submit
            </primary-button>
        </div>
</Dialog>
<Toast/>
</template>

<script setup>
  // Imports
  import { ref, watch } from 'vue';
  import InputLabel from "@/Components/InputLabel.vue";
  import TextInput from "@/Components/TextInput.vue";
  import InputError from "@/Components/InputError.vue";
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import SecondaryButton from "@/Components/SecondaryButton.vue";
  import {
    useForm,
    usePage
  } from "@inertiajs/vue3";
  import {
    useToast
  } from 'primevue/usetoast';

  // Uses
  const form = useForm({
        email: '',
        all: false,
        ids: [],
        parent_id: null
    });
    const page = usePage();
    const toast = useToast();

  // Props & Emits
  const props = defineProps({
        shareDialog: {
            type: Boolean,
            required: true
        },
        allSelected: Boolean,
        selectedIds: Array
    });

    const emit = defineEmits(['update-share-dialog']);

  // Refs
  const emailInput = ref(null);
  const localShareDialog = ref(props.shareDialog);

  // Computed

  //watch:
  watch(() => props.shareDialog, (newVal) => {
    localShareDialog.value = newVal;
    });

    watch(localShareDialog, (newVal) => {
        emit('update-share-dialog', newVal);
    });

  // Methods
  function share() {
        form.parent_id = page.props.folder.id
        if (props.allSelected) {
            form.all = true;
            form.ids = [];
        } else {
            form.ids = props.selectedIds;
        }
        const email = form.email
        form.post(route('files.share'), {
            preserveScroll: true,
            onSuccess: () => {
                closeDialog();
                form.reset();
                toast.add({
                    severity: 'success',
                    summary: 'Confirmed',
                    detail: `Selected files have been shared to "${email}" successfully.`,
                    life: 3000
                });

            },
            onError: () => emailInput.value.focus()
        })
    }
    const closeDialog = () => {
        localShareDialog.value = false;
        form.clearErrors();
        form.reset();
    }

  // Hooks

</script>