<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance'
import { useColorUi } from '@/composables/useColorUi'
import { router, usePage } from '@inertiajs/vue3'
import type { NavigationMenuItem } from '@nuxt/ui'
import { computed, ref, watch } from 'vue'

const page = usePage()
const user = ref(page.props.auth.user)
const { updateAppearance } = useAppearance()
const { updateUi } = useColorUi()

const settingNavOpen = ref(false)

const handleLogout = () => {
    router.post(
        route('logout'),
        {},
        {
            onSuccess: () => {
                updateAppearance('system')
                // reset app ui colors
                updateUi('green', 'slate')
                // flush
                router.flushAll()
            },
        },
    )
}

const sidebarNavigationItems = computed<NavigationMenuItem[][]>(() => [
    [
        {
            label: 'Home',
            icon: 'i-lucide-house',
            to: route('dashboard', {}, false),
            target: '_self',
        },
        {
            label: 'Settings',
            icon: 'i-lucide-settings',
            active: settingNavOpen.value,
            defaultOpen: settingNavOpen.value,
            children: [
                {
                    label: 'Account',
                    icon: 'i-lucide-user-cog',
                    description: 'Configuration for user profile',
                    to: route('settings.account.edit', {}, false),
                    target: '_self',
                },
                {
                    label: 'Appearance',
                    icon: 'i-lucide-swatch-book',
                    description: 'Define preference for your application themes',
                    to: route('settings.appearance.edit', {}, false),
                    target: '_self',
                },
                {
                    label: 'Authentication',
                    icon: 'i-hugeicons-security',
                    description: 'Secure your password',
                    to: route('settings.password.edit', {}, false),
                    target: '_self',
                },
                {
                    label: 'Two Factor Auth (2FA)',
                    icon: 'i-streamline-plump:padlock-key',
                    description: 'Secure your account by adding 2FA',
                    to: route('settings.two-factor.show', {}, false),
                    target: '_self',
                },
                {
                    label: 'Sessions',
                    icon: 'i-heroicons-signal',
                    description: 'Configuration for user profile',
                    to: route('settings.sessions.edit', {}, false),
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
])

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

watch(
    () => page.url,
    () => {
        settingNavOpen.value = route().current('settings.*')
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
            <div class="group flex w-full flex-row justify-between">
                <UUser
                    :name="collapsed ? undefined : user.name"
                    :description="collapsed ? undefined : user.email"
                    :avatar="{
                        src: undefined, // you can replace it with your user avatar
                        alt: user.name, // fallback image holder if the 'src' is undefined
                    }"
                    :class="collapsed ? 'transition-opacity duration-200 group-hover:opacity-0' : ''"
                />
                <UTooltip text="Sign Out">
                    <UButton
                        icon="i-uil:signout"
                        size="md"
                        color="neutral"
                        variant="link"
                        @click.prevent="handleLogout"
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
