<template>
    <MenuItem>
        <div>
            <label for="fileselector" class="text-gray-700 block px-4 py-2 text-sm cursor-pointer text-center hover:bg-gray-300 w-full border-b border-gray-100">
                Upload Files
            </label>
            <input id="fileselector" @change="onChange" type="file" multiple hidden>
        </div>
    </MenuItem>
</template>

<script setup>
    // Imports
    import {
        MenuItem
    } from "@headlessui/vue";

    // Uses

    // Refs

    // Props & Emits

    // Computed

    // Methods
    function onChange(event) {
        //
    }

    // Hooks
</script>
