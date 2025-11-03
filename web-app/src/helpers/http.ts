// src/helpers/http.ts
const API_BASE = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

type HttpOptions = {
  method?: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
  headers?: Record<string, string>
  body?: unknown
  withCredentials?: boolean
  params?: Record<string, any>
  authType?: 'client' | 'instructor' | 'admin' // 👈 thêm instructor
}

/**
 * 🔑 Lấy token đúng với vai trò
 */
function getToken(authType: 'client' | 'instructor' | 'admin' = 'client'): string | null {
  let key = 'client_auth'
  if (authType === 'admin') key = 'admin_auth'
  if (authType === 'instructor') key = 'instructor_auth'

  const state = localStorage.getItem(key)
  if (!state) return null

  try {
    const parsed = JSON.parse(state)
    return parsed.token ?? null
  } catch {
    return null
  }
}

/**
 * 🌐 Hàm http chính
 */
export async function http(path: string, opts: HttpOptions = {}) {
  const headers = new Headers({
    Accept: 'application/json',
    ...(opts.headers ?? {}),
  })

  // 🔹 Nếu không gửi FormData → thêm Content-Type
  if (!(opts.body instanceof FormData)) {
    headers.set('Content-Type', 'application/json')
  }

  // 🔹 Thêm token (Bearer)
  const token = getToken(opts.authType ?? 'client')
  if (token) headers.set('Authorization', `Bearer ${token}`)

  // 🔹 Tạo URL hoàn chỉnh
  let finalPath = path.startsWith('http')
    ? path
    : `${API_BASE}${path.startsWith('/') ? path : '/' + path}`

  if (opts.params) {
    const searchParams = new URLSearchParams()
    Object.entries(opts.params).forEach(([k, v]) => {
      if (v !== undefined && v !== null) searchParams.append(k, v as any)
    })
    const query = searchParams.toString()
    if (query) finalPath += `?${query}`
  }

  // 🔹 Gửi request
  const res = await fetch(finalPath, {
    method: opts.method ?? 'GET',
    mode: 'cors', // ✅ Cho phép CORS
    credentials: 'include', // ✅ Cho phép cookie + Sanctum
    headers,
    body:
      opts.body instanceof FormData
        ? opts.body
        : opts.body
        ? JSON.stringify(opts.body)
        : undefined,
  })

  // 🔹 Xử lý hết hạn session
  if (res.status === 401) {
    const key =
      opts.authType === 'admin'
        ? 'admin_auth'
        : opts.authType === 'instructor'
        ? 'instructor_auth'
        : 'client_auth'
    localStorage.removeItem(key)
    throw new Error('Session expired. Please login again.')
  }

  // 🔹 Parse JSON
  const isJson = res.headers.get('content-type')?.includes('application/json')
  const data = isJson ? await res.json() : await res.text()

  if (!res.ok) {
    const msg = isJson && (data as any)?.message ? (data as any).message : `HTTP ${res.status}`
    throw new Error(msg)
  }

  return data
}

/**
 * 🎓 Client API (student)
 */
export const httpClient = (path: string, opts: Omit<HttpOptions, 'authType'> = {}) =>
  http(path, { ...opts, authType: 'client' })

/**
 * 🧑‍🏫 Instructor API
 */
export const httpInstructor = (path: string, opts: Omit<HttpOptions, 'authType'> = {}) =>
  http(path, { ...opts, authType: 'instructor' })

/**
 * 👨‍💼 Admin API
 */
export const httpAdmin = (path: string, opts: Omit<HttpOptions, 'authType'> = {}) =>
  http(path, { ...opts, authType: 'admin' })
