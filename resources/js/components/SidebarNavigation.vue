<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3'
import type { NavigationMenuItem } from '@nuxt/ui'
import { ref, watch } from 'vue'

const page = usePage()
const user = ref(page.props.auth.user)

const sidebarNavigationItems: NavigationMenuItem[][] = [
    [
        {
            label: 'Home',
            icon: 'i-lucide-house',
            to: route('dashboard'),
            active: route().current('dashboard'),
            target: '_self',
        },
        {
            label: 'Settings',
            icon: 'i-lucide-settings',
            defaultOpen: route().current('settings.*'),
            children: [
                {
                    label: 'Account',
                    icon: 'i-lucide-user-cog',
                    description: 'Configuration for user profile',
                    to: route('settings.account.edit'),
                    active: route().current('settings.account.edit'),
                    target: '_self',
                },
                {
                    label: 'Preference',
                    icon: 'i-lucide-swatch-book',
                    description: 'Define preference for your application appearance',
                    to: '/docs/composables/define-shortcuts',
                    target: '_self',
                },
                {
                    label: 'Authentication',
                    icon: 'i-hugeicons-security',
                    description: 'Configuration for user profile',
                    to: '/docs/composables/define-shortcuts',
                    target: '_self',
                },
                {
                    label: 'Sessions',
                    icon: 'i-heroicons-signal',
                    description: 'Configuration for user profile',
                    to: '/docs/composables/define-shortcuts',
                    target: '_self',
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

watch(
    () => page.props.auth.user,
    () => {
        user.value = page.props.auth.user
    },
    {
        immediate: true,
        deep: true,
    },
)
</script>

<template>
    <UDashboardSidebar
        collapsible
        :min-size="15"
        :default-size="20"
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
                :collapsed="collapsed"
                :tooltip="!!collapsed"
                :popover="!!collapsed"
                :items="sidebarNavigationItems[0]"
                orientation="vertical"
            />

            <UNavigationMenu
                :collapsed="collapsed"
                :tooltip="!!collapsed"
                :popover="!!collapsed"
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
                        @click.prevent="router.post(route('logout'))"
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
