import { RouteParams, Router, route as routeFn } from 'ziggy-js'

declare global {
    let route: typeof routeFn

    function route(): Router
    function route(name: string, params?: RouteParams<typeof name> | undefined, absolute?: boolean): string
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route: typeof route
    }
}
