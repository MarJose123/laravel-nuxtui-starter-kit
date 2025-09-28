import { useAppColorMode } from '@/composables/useAppColorMode'
import { useAppearanceSync } from '@/composables/useAppearanceSync'
import { App } from 'vue'

export default {
    install(app: App) {
        // Initialize color mode globally
        const colorMode = useAppColorMode()

        // Initialize appearance sync
        useAppearanceSync()

        // Provide to all components
        app.provide('colorMode', colorMode)

        // Make available as global property
        app.config.globalProperties.$colorMode = colorMode.$colorMode
    },
}
