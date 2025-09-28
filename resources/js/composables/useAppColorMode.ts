import { usePage } from '@inertiajs/vue3'
import Cookies from 'js-cookie'
import { computed, onMounted, ref, watch } from 'vue'

type ColorMode = 'light' | 'dark' | 'auto'

// Global state
const colorMode = ref<ColorMode>('auto')
const isInitialized = ref(false)

export function useAppColorMode() {
    const page = usePage()

    // Initialize from cookie on first use
    if (!isInitialized.value) {
        const savedMode = Cookies.get('appearance') as ColorMode
        if (savedMode && ['light', 'dark', 'auto'].includes(savedMode)) {
            colorMode.value = savedMode
        }
        isInitialized.value = true
    }

    // Watch for server-side appearance changes (e.g., after login)
    watch(
        () => page.props?.appearance,
        (serverAppearance) => {
            if (serverAppearance && ['light', 'dark', 'auto'].includes(serverAppearance as string)) {
                const newMode = serverAppearance as ColorMode
                if (colorMode.value !== newMode) {
                    colorMode.value = newMode
                    // Update cookie to match server
                    Cookies.set('appearance', newMode, {
                        path: '/',
                        sameSite: 'lax',
                        secure: import.meta.env.PROD,
                        expires: 365,
                    })
                }
            }
        },
        { immediate: true },
    )

    // Computed value for the actual applied theme
    const value = computed(() => {
        if (colorMode.value === 'auto') {
            // Check system preference
            if (typeof window !== 'undefined') {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
            }
            return 'light'
        }
        return colorMode.value
    })

    // Apply theme to document
    const applyTheme = (theme: string) => {
        if (typeof document !== 'undefined') {
            const html = document.documentElement
            html.classList.remove('light', 'dark')
            html.classList.add(theme)
        }
    }

    // Watch for changes and apply
    watch(
        value,
        (newValue) => {
            applyTheme(newValue)
        },
        { immediate: true },
    )

    // Listen for system theme changes when in auto mode
    onMounted(() => {
        if (typeof window !== 'undefined') {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
            const handleChange = () => {
                if (colorMode.value === 'auto') {
                    applyTheme(value.value)
                }
            }

            mediaQuery.addEventListener('change', handleChange)

            // Initial apply
            applyTheme(value.value)
        }
    })

    // Function to set color mode
    const setColorMode = (mode: ColorMode) => {
        colorMode.value = mode

        // Save to cookie
        Cookies.set('appearance', mode, {
            path: '/',
            sameSite: 'lax',
            secure: import.meta.env.PROD,
            expires: 365, // 1 year
        })

        applyTheme(value.value)
    }

    return {
        preference: colorMode,
        value,
        setColorMode,
        // Compatibility with Nuxt UI's useColorMode API
        $colorMode: {
            preference: colorMode,
            value,
            unknown: ref(false),
            forced: ref(false),
        },
    }
}
