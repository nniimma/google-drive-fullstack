<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <!-- 
                https://headlessui.com/
                npm install @headlessui/vue@latest 
             -->
            <MenuButton
                class="group inline-flex w-full justify-center rounded-md 0 px-4 py-2 text-sm font-medium text-gray-800 dark:text-gray-700 dark:bg-gray-100 hover:bg-gray-500 hover:dark:text-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75">
                {{ $page.props.auth.user.name }}
                <!-- 
                    https://heroicons.com/
                    npm install @heroicons/vue
                  -->
                <ChevronDownIcon class="-mr-1 ml-2 h-5 w-5 text-gray-800 dark:text-gray-700 group-hover:dark:text-gray-100"
                    aria-hidden="true" />
            </MenuButton>
        </div>

        <transition enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0">
            <MenuItems
            class="absolute right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
            <div class="px-1 py-1">
                <MenuItem v-slot="{ active }">
                <ResponsiveNavLink class="flex cursor-pointer" :href="route('profile.edit')"
                    :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">

                    <UserIcon  class="size-4 mr-2" aria-hidden="true" />
                    Profile
                </ResponsiveNavLink>
                </MenuItem>
                <MenuItem v-slot="{ active }">
                <ResponsiveNavLink class="flex cursor-pointer" :href="route('logout')" method="post" as="button"
                    :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">

                    <ArrowRightOnRectangleIcon  class="size-4 mr-2" aria-hidden="true" />

                    Logout
                </ResponsiveNavLink >
                </MenuItem>
            </div>
        </MenuItems>
        </transition>
    </Menu>
</template>




<script setup>
// Imports
import {
    Menu,
    MenuButton,
    MenuItems,
    MenuItem
} from '@headlessui/vue'
import { ChevronDownIcon, UserIcon, ArrowRightOnRectangleIcon   } from '@heroicons/vue/20/solid';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

// Uses

// Refs

// Props & Emits

// Computed

// Methods

// Hooks
</script>

<style scoped>

</style>