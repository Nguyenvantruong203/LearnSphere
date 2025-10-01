const API_BASE = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

type HttpOptions = {
  method?: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
  headers?: Record<string, string>
  body?: unknown
  withCredentials?: boolean
  params?: Record<string, any>
  authType?: 'client' | 'admin' // phân biệt loại token
}

function getToken(authType: 'client' | 'admin' = 'client'): string | null {
  const state = localStorage.getItem(authType === 'admin' ? 'admin_auth' : 'client_auth')
  if (!state) return null

  try {
    const parsed = JSON.parse(state)
    return parsed.token ?? null
  } catch {
    return null
  }
}

export async function http(path: string, opts: HttpOptions = {}) {
  const headers = new Headers({
    Accept: 'application/json',
    ...(opts.headers ?? {}),
  })

  if (opts.body instanceof FormData) {
    headers.delete('Content-Type')
  } else {
    headers.set('Content-Type', 'application/json')
  }

  // 👇 Lấy token theo loại auth
  const token = getToken(opts.authType ?? 'client')
  if (token) {
    headers.set('Authorization', `Bearer ${token}`)
  }

  let finalPath = `${API_BASE}${path}`
  if (opts.params) {
    const searchParams = new URLSearchParams()
    for (const key in opts.params) {
      if (opts.params[key] !== undefined && opts.params[key] !== null) {
        searchParams.append(key, opts.params[key])
      }
    }
    if (searchParams.toString()) {
      finalPath += `?${searchParams.toString()}`
    }
  }

  const res = await fetch(finalPath, {
    method: opts.method ?? 'GET',
    credentials: opts.withCredentials ? 'include' : 'same-origin',
    headers,
    body:
      opts.body instanceof FormData ? opts.body : opts.body ? JSON.stringify(opts.body) : undefined,
  })

  if (res.status === 401) {
    if (opts.authType === 'admin') {
      localStorage.removeItem('admin_auth')
    }
    if (opts.authType === 'client') {
      localStorage.removeItem('client_auth')
    }
    throw new Error('Session expired. Please login again.')
  }

  const isJson = res.headers.get('content-type')?.includes('application/json')
  const data = isJson ? await res.json() : await res.text()

  if (!res.ok) {
    const msg = isJson && (data as any)?.message ? (data as any).message : `HTTP ${res.status}`
    throw new Error(msg)
  }

  return data
}

export const httpClient = (path: string, opts: Omit<HttpOptions, 'authType'> = {}) =>
  http(path, { ...opts, authType: 'client' })

export const httpAdmin = (path: string, opts: Omit<HttpOptions, 'authType'> = {}) =>
  http(path, { ...opts, authType: 'admin' })
