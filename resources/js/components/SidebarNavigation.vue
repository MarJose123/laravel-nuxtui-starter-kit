<script setup lang="ts">
import UserMenu from '@/components/UserMenu.vue'
import type { NavigationMenuItem } from '@nuxt/ui'
import { computed } from 'vue'

const sidebarNavigationItems = computed<NavigationMenuItem[][]>(() => [
    [
        {
            label: 'Home',
            icon: 'i-lucide-house',
            to: route('dashboard', {}, false),
            target: '_self',
        },
    ],
    [
        {
            label: 'Feedback',
            icon: 'i-lucide-message-circle',
            to: 'https://github.com/marjose123/starter-kit/issues',
            target: '_blank',
        },
        {
            label: 'Github Repository',
            icon: 'i-lucide-info',
            to: 'https://github.com/marjose123/starter-kit',
            target: '_blank',
        },
    ],
])
</script>

<template>
    <UDashboardSidebar
        collapsible
        :min-size="15"
        :default-size="15.5"
        :max-size="23"
        :ui="{ footer: 'border-t border-default' }"
    >
        <template #header="{ collapsed }">
            <div v-if="!collapsed" class="flex w-full flex-row items-center justify-center">
                <Logo class="h-10 w-auto shrink-0" />Starter Kit
            </div>
            <Logo v-else class="h-8 w-auto shrink-0 justify-center" />
        </template>

        <template #default="{ collapsed }">
            <UNavigationMenu
                type="single"
                highlight
                highlight-color="primary"
                :collapsed="collapsed"
                :tooltip="!!collapsed"
                :popover="!!collapsed"
                :items="sidebarNavigationItems[0]"
                orientation="vertical"
            />

            <UNavigationMenu
                type="single"
                :collapsed="collapsed"
                :tooltip="!!collapsed"
                :popover="!!collapsed"
                :items="sidebarNavigationItems[1]"
                orientation="vertical"
                class="mt-auto"
            />
        </template>

        <template #footer="{ collapsed }">
            <UserMenu :collapsed="collapsed" />
        </template>
    </UDashboardSidebar>
</template>
