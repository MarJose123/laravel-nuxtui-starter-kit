import { AppPageProps } from '@/types/index'
import { Notification } from '@/types/notification'
import type { route as routeFn } from 'ziggy-js'

declare global {
    const route: typeof routeFn
}

declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string
        [key: string]: string | boolean | undefined
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
    export interface InertiaConfig {
        flashDataType: {
            notification?: Notification
        }
    }
}
