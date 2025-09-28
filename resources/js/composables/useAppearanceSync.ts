import { useAppColorMode } from '@/composables/useAppColorMode'
import { usePage } from '@inertiajs/vue3'
import { watch } from 'vue'

export function useAppearanceSync() {
    const page = usePage()
    const { setColorMode } = useAppColorMode()

    // Watch for authentication state changes
    watch(
        () => page.props?.auth?.user,
        (user, previousUser) => {
            // If user just logged in (was null, now has user)
            if (!previousUser && user && page.props?.appearance) {
                // Small delay to ensure DOM is ready
                setTimeout(() => {
                    setColorMode(page.props?.appearance as 'light' | 'dark' | 'auto')
                }, 100)
            }
        },
        { immediate: true },
    )

    // Also watch for direct appearance changes
    watch(
        () => page.props?.appearance,
        (newAppearance) => {
            if (newAppearance && page.props.auth?.user) {
                setColorMode(newAppearance as 'light' | 'dark' | 'auto')
            }
        },
        { immediate: true },
    )
}
