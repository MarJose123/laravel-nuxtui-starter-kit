import { usePage } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

export type NeutralColor = 'slate' | 'gray' | 'zinc' | 'neutral' | 'stone'
const appConfig = useAppConfig()

export function updateUiColor(primaryColor: string, neutralColor: NeutralColor) {
    appConfig.ui.colors.primary = primaryColor
    appConfig.ui.colors.neutral = neutralColor
}

const setCookie = (name: string, value: string, days = 365) => {
    if (typeof document === 'undefined') {
        return
    }

    const maxAge = days * 24 * 60 * 60

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`
}

const getStoredPrimaryColor = () => {
    if (typeof window === 'undefined') {
        return null
    }

    return localStorage.getItem('primary-color') as string | null
}

const getStoredNeutralColor = () => {
    if (typeof window === 'undefined') {
        return null
    }

    return localStorage.getItem('neutral-color') as NeutralColor | null
}

export function initializeUiColor() {
    if (typeof window === 'undefined') {
        return
    }

    // Initialize the theme from saved preference or default to a system...
    const primary = getStoredPrimaryColor() ?? usePage().props?.theme.primary
    const neutral = getStoredNeutralColor() ?? (usePage().props?.theme.neutral as NeutralColor)

    updateUiColor(primary ?? appConfig.ui.colors.primary, neutral ?? appConfig.ui.colors.neutral)
}

const primaryColor = ref<string>(appConfig.ui.colors.primary)
const neutralColor = ref<NeutralColor>(appConfig.ui.colors.neutral)

export function useColorUi() {
    onMounted(() => {
        const savedPrimaryColor =
            getStoredPrimaryColor() ?? usePage().props?.theme.primary ?? appConfig.ui.colors.primary
        const savedNeutralColor =
            getStoredNeutralColor() ?? usePage().props?.theme.neutral ?? appConfig.ui.colors.neutral

        if (savedPrimaryColor) {
            primaryColor.value = savedPrimaryColor
        }
        if (savedNeutralColor) {
            neutralColor.value = savedNeutralColor as NeutralColor
        }
    })

    function updateUi(primary: string, neutral: NeutralColor) {
        primaryColor.value = primary
        neutralColor.value = neutral

        //set local
        localStorage.setItem('primary-color', primary)
        localStorage.setItem('neutral-color', neutral)

        //set cookie
        setCookie('primary-color', primary)
        setCookie('neutral-color', neutral)

        updateUiColor(primary, neutral)
    }

    return {
        primaryColor,
        neutralColor,
        updateUi,
    }
}
