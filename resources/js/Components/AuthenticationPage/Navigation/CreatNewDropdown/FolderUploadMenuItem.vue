<template>
    <MenuItem>
        <div>
            <label for="folderselector" class="text-gray-700 block px-4 py-2 text-sm text-center hover:bg-gray-300 w-full cursor-pointer">
                Upload Folder
            </label>
            <input id="folderselector" @change="onChange" type="file" multiple directory webkitdirectory hidden>
        </div>
    </MenuItem>
</template>

<script setup>
    // Imports
    import {
        MenuItem
    } from "@headlessui/vue";
    import {
        emitter,
        FILE_UPLOAD_STARTED
    } from '@/event-bus';

    // Uses

    // Refs

    // Props & Emits

    // Computed

    // Methods
    function onChange(event) {
        emitter.emit(FILE_UPLOAD_STARTED, event.target.files);
    }

    // Hooks
</script>
