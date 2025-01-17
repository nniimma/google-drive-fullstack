<template>
    <div class="h-screen flex w-full gap-4">
        <Navigation />

        <main class="flex flex-col flex-1 px-4 overflow-hidden ">
            <div class="flex items-center justify-between w-full">
                <SearchForm />
                <UserSettingsDropdown/>
            </div>
            <div class="flex flex-1 flex-col overflow-hidden">
                <slot />
            </div>
        </main>
    </div>

</template>

<script setup>
    // Imports
    import Navigation from '@/Components/AuthenticationPage/Navigation.vue';
    import SearchForm from '@/Components/AuthenticationPage/SearchForm.vue';
    import UserSettingsDropdown from '@/Components/AuthenticationPage/UserSettingsDropdown.vue';
</script>
<style scoped>
</style>
