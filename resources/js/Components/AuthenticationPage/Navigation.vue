<template>
    <nav class="min-w-[200px]">
            <Link :href="route('files.index')" class="h-[80px] px-3 flex items-center gap-3">
            <img src="/icons/logo.svg" alt="logo">
            Google Drive
            </Link>
            <!-- <pre>{{ $page }}</pre> -->
         <div class="px-3">
            <CreateNewDropDown/>
            <div class="py-3 mt-5 p-2 rounded border border-gray-300">
                <nav-link :href="route('files.index')" :active="$page.props.auth.route_name == 'files.index'">My Files</nav-link>
                <NavLink :href="route('dashboard')" :active="$page.props.auth.route_name == 'files.sharedWithMe'">Shared with me</NavLink>
                <NavLink :href="route('dashboard')" :active="$page.props.auth.route_name == 'files.sharedByMe'">Shared by me</NavLink>
                <nav-link :href="route('dashboard')" :active="$page.props.auth.route_name == 'trash'">Trash</nav-link>
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