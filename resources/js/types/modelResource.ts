export interface modelResource {
    data: modelResourceData[]
    links: modelResourceLinks
    meta: modelResourceMeta
}

export interface modelResourceData {
    id: number | string
    [key: string]: unknown
}

interface modelResourceLinks {
    first: string
    last: string
    prev: string | null
    next: string | null
}

export interface modelResourceMeta {
    current_page: number
    from: number
    last_page: number
    path: string
    per_page: number
    to: number
    total: number
    links: modelResourceMetaLinks[]
}

interface modelResourceMetaLinks {
    url: string
    label: string
    page: number
    active: boolean
}
