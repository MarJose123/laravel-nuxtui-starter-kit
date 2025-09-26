<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import type { NavigationMenuItem } from '@nuxt/ui'

const page = usePage()
const user = page.props.auth.user

const sidebarNavigationItems: NavigationMenuItem[][] = [
    [
        {
            label: 'Home',
            icon: 'i-lucide-house',
            active: true,
        },
        {
            label: 'Inbox',
            icon: 'i-lucide-inbox',
            badge: '4',
        },
        {
            label: 'Contacts',
            icon: 'i-lucide-users',
        },
        {
            label: 'Settings',
            icon: 'i-lucide-settings',
            defaultOpen: true,
            children: [
                {
                    label: 'General',
                },
                {
                    label: 'Members',
                },
                {
                    label: 'Notifications',
                },
            ],
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
]
</script>

<template>
    <UDashboardSidebar
        collapsible
        :min-size="20"
        :default-size="23"
        :max-size="60"
        :ui="{ footer: 'border-t border-default' }"
    >
        <template #header="{ collapsed }">
            <div v-if="!collapsed" class="flex w-full flex-row items-center justify-center">
                <Logo class="h-10 w-auto shrink-0" />Starter Kit
            </div>
            <Logo v-else class="h-8 w-auto shrink-0 justify-center" />
        </template>

        <template #default="{ collapsed }">
            <UNavigationMenu :collapsed="collapsed" :items="sidebarNavigationItems[0]" orientation="vertical" />

            <UNavigationMenu
                :collapsed="collapsed"
                :items="sidebarNavigationItems[1]"
                orientation="vertical"
                class="mt-auto"
            />
        </template>

        <template #footer="{ collapsed }">
            <div class="group flex w-full flex-row justify-between">
                <UUser
                    :name="collapsed ? undefined : user.name"
                    :description="collapsed ? undefined : user.email"
                    :avatar="{
                        src: 'https://github.com/marjose123.png',
                    }"
                    :chip="{
                        color: 'primary',
                        position: 'top-right',
                    }"
                    :class="collapsed ? 'transition-opacity duration-200 group-hover:opacity-0' : ''"
                />
                <UTooltip text="Sign Out">
                    <UButton
                        icon="i-uil:signout"
                        size="md"
                        color="neutral"
                        variant="link"
                        :class="
                            collapsed
                                ? 'absolute opacity-0 transition-opacity duration-200 group-hover:opacity-100'
                                : ''
                        "
                    />
                </UTooltip>
            </div>
        </template>
    </UDashboardSidebar>
</template>
