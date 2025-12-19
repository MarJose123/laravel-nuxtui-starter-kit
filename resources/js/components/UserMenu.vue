<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance'
import { useColorUi } from '@/composables/useColorUi'
import { useInitials } from '@/composables/useInitials'
import { router, usePage } from '@inertiajs/vue3'
import type { DropdownMenuItem } from '@nuxt/ui'
import { computed, ref, watch } from 'vue'

defineProps<{
    collapsed?: boolean
}>()

const page = usePage()
const auth = ref(page.props.auth)
const { getInitials } = useInitials()
const { updateAppearance } = useAppearance()
const { updateUi } = useColorUi()

const user = computed(() => ({
    name: auth.value.user.name,
    avatar: {
        text: getInitials(auth.value.user.name),
    },
}))

watch(
    () => page.props.auth,
    (newvalue) => {
        auth.value = newvalue
    },
    {
        immediate: true,
        deep: true,
    },
)

const handleLogout = (event?: Event) => {
    // Remove focus/ring from the clicked element
    if (event?.target instanceof HTMLElement) {
        event.target.blur()
    }

    router.post(
        route('logout'),
        {},
        {
            onSuccess: () => {
                updateAppearance('system')
                // reset app ui colors
                updateUi('brand-red', 'slate')
                // flush
                router.flushAll()
            },
        },
    )
}

const items = computed<DropdownMenuItem[][]>(() => [
    [
        {
            label: 'Account',
            icon: 'i-lucide-user-cog',
            to: route('settings.account.edit', {}, false),
            target: '_self',
        },
        {
            label: 'Appearance',
            icon: 'i-lucide-swatch-book',
            to: route('settings.appearance.edit', {}, false),
            target: '_self',
        },
        {
            label: 'Authentication',
            icon: 'i-hugeicons-security',
            to: route('settings.password.edit', {}, false),
            target: '_self',
        },
        {
            label: 'Two Factor Auth (2FA)',
            icon: 'i-streamline-plump:padlock-key',
            to: route('settings.two-factor.show', {}, false),
            target: '_self',
        },
        {
            label: 'Sessions',
            icon: 'i-heroicons-signal',
            to: route('settings.sessions.edit', {}, false),
            target: '_self',
        },
    ],
    [
        {
            label: 'Logout',
            icon: 'i-uil:signout',
            autofocus: false,
            class: 'focus:ring-0 focus-visible:ring-0 focus:outline-none cursor-pointer logout',
            onSelect: () => handleLogout(),
        },
    ],
])
</script>

<template>
    <UDropdownMenu
        :items="items"
        :content="{ align: 'center', collisionPadding: 12 }"
        :ui="{ content: collapsed ? 'w-48' : 'w-(--reka-dropdown-menu-trigger-width)' }"
    >
        <UButton
            v-bind="{
                ...user,
                label: collapsed ? undefined : auth.user.name,
                trailingIcon: collapsed ? undefined : 'i-lucide-chevrons-up-down',
            }"
            color="neutral"
            variant="ghost"
            block
            :square="collapsed"
            class="data-[state=open]:bg-elevated"
            :ui="{
                trailingIcon: 'text-dimmed',
            }"
        />

        <template #chip-leading="{ item }">
            <span
                :style="{
                    '--chip-light': `var(--color-${(item as any).chip}-500)`,
                    '--chip-dark': `var(--color-${(item as any).chip}-400)`,
                }"
                class="ms-0.5 size-2 rounded-full bg-(--chip-light) dark:bg-(--chip-dark)"
            />
        </template>
    </UDropdownMenu>
</template>

<style scoped></style>
