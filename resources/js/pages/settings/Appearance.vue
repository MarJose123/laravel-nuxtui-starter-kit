<script setup lang="ts">
import AppLayout from '@/components/AppLayout.vue'
import HeadingSmall from '@/components/HeadingSmall.vue'
import { useAppearance } from '@/composables/useAppearance'
import { useColorUi } from '@/composables/useColorUi'
import Layout from '@/layouts/default.vue'
import { Head, router } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@nuxt/ui'
import { omit } from '@nuxt/ui/utils'
import colors from 'tailwindcss/colors'
import { computed, ref, watch } from 'vue'

defineOptions({ layout: Layout })

const breadcrumbItems = ref<BreadcrumbItem[]>([
    {
        label: 'Settings',
    },
    {
        label: 'Appearance',
        to: route('settings.appearance.edit', {}, false),
        target: '_self',
    },
])

const { appearance, updateAppearance } = useAppearance()
const { primaryColor, neutralColor, updateUi } = useColorUi()
const appConfig = useAppConfig()

const appearanceConfig = ref({
    theme: appearance.value,
    primary: appConfig.ui.colors.primary,
    neutral: appConfig.ui.colors.neutral,
})

const neutralColors = ['slate', 'gray', 'zinc', 'neutral', 'stone']
const colorsToOmit = ['inherit', 'current', 'transparent', 'black', 'white', ...neutralColors]
const primaryColors = Object.keys(omit(colors, colorsToOmit as any))

const modes: {
    label: 'light' | 'dark' | 'system'
    icon: string
}[] = [
    { label: 'light', icon: appConfig.ui.icons.light },
    { label: 'dark', icon: appConfig.ui.icons.dark },
    { label: 'system', icon: appConfig.ui.icons.system },
]

const primary = computed({
    get() {
        return appConfig.ui.colors.primary
    },
    set(option) {
        updateUi(option, neutralColor.value)
        appearanceConfig.value.primary = option
    },
})

const neutral = computed({
    get() {
        return appConfig.ui.colors.neutral
    },
    set(option) {
        updateUi(primaryColor.value, option)
        appearanceConfig.value.neutral = option
    },
})

const theme = computed({
    get() {
        return appearance.value
    },
    set(option: 'light' | 'dark' | 'system') {
        updateAppearance(option)
        appearanceConfig.value.theme = option
    },
})

watch(
    appearanceConfig,
    (data) => {
        console.log(data)
        router.patch(
            route('settings.appearance.store'),
            {
                mode: data.theme,
                primary: data.primary,
                secondary: data.neutral,
            },
            {
                preserveState: true,
                preserveScroll: true,
            },
        )
    },
    {
        deep: true,
    },
)
</script>

<template>
    <AppLayout :breadcrumbItems="breadcrumbItems">
        <Head title="Appearance settings" />

        <div class="flex w-full flex-col space-y-6">
            <!--  START:  Header Section        -->
            <HeadingSmall title="Appearance settings" description="Update your account's appearance settings" />
            <span class="flex w-7/12 border-b border-b-slate-200" />
            <!--   END: Header Section        -->

            <div class="w-5/12 space-y-6">
                <UFormField label="Theme">
                    <div class="flex flex-row gap-2">
                        <ThemePickerButton
                            size="md"
                            v-for="_mode in modes"
                            :key="_mode.label"
                            v-bind="_mode"
                            @click="theme = _mode.label"
                            :selected="theme === _mode.label"
                            :data-test="`theme-${_mode.label}`"
                        />
                    </div>
                </UFormField>
                <UFormField label="Primary Color">
                    <div class="-mx-2 grid grid-cols-3 gap-2">
                        <ThemePickerButton
                            v-for="color in primaryColors"
                            size="xl"
                            :key="color"
                            :label="color"
                            :chip="color"
                            :selected="primary === color"
                            @click="primary = color"
                            :data-test="`primary-color-${color.toLowerCase()}`"
                        />
                    </div>
                </UFormField>
                <UFormField label="Neutral Color">
                    <div class="-mx-2 grid grid-cols-3 gap-2">
                        <ThemePickerButton
                            v-for="color in neutralColors"
                            size="xl"
                            :key="color"
                            :label="color"
                            :chip="color === 'neutral' ? 'old-neutral' : color"
                            :selected="neutral === color"
                            @click="neutral = color"
                            :data-test="`secondary-color-${color.toLowerCase()}`"
                        />
                    </div>
                </UFormField>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
