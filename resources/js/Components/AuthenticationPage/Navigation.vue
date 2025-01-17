<template>
    <nav class="min-w-[200px]">
            <Link :href="route('files.index')" class="h-[80px] px-3 flex items-center gap-3">
            <img src="/icons/logo.svg" alt="logo">
            Google Drive
            </Link>
         <div class="px-3">
            <CreateNewDropDown/>

            <div class="py-3">
                <NavLink
                :href="route('files.index')" :active="$page.props.route_name == 'files.index'">
                    My Files
                </NavLink>
                <!-- <NavLink :href="route('files.sharedWithMe')" :active="$page.props.route_name == 'files.sharedWithMe'">Shared with me</NavLink>
                <NavLink :href="route('files.sharedByMe')" :active="$page.props.route_name == 'files.sharedByMe'">Shared by me</NavLink>
                <NavLink :href="route('trash')" :active="$page.props.route_name == 'trash'">Trash</NavLink> -->
            </div>
         </div>
    </nav>
</template>

<script setup>
    // Imports
    import { Link } from '@inertiajs/vue3';
    import NavLink from '@/Components/NavLink.vue';
    import CreateNewDropDown from '@/Components/AuthenticationPage/Navigation/CreateNewDropDown.vue';

    // Uses

    // Refs

    // Props & Emits

    // Computed

    // Methods

    // Hooks
</script>

<style scoped>

</style>