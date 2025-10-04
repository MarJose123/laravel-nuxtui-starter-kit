export interface WebSession {
    agent: {
        browser: string | null
        platform: string | null
        is_desktop: boolean
        country: string | null
        country_code: string | null
        flag: string | null
        city: string | null
        isp: string | null
        timezone: string | null
        latitude: string | null
        longitude: string | null
    }
    ip_address: string
    is_current_device: boolean
    last_active: string
}
