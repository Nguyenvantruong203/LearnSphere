const API_BASE = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

type HttpOptions = {
  method?: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
  headers?: Record<string, string>
  body?: unknown
  withCredentials?: boolean
}

export async function http(path: string, opts: HttpOptions = {}) {
  const headers = new Headers({
    'Content-Type': 'application/json',
    Accept: 'application/json',
    ...(opts.headers ?? {})
  });

  // Lấy token từ localStorage và tự động thêm vào header
  const token = localStorage.getItem('auth_token');
  if (token) {
    headers.set('Authorization', `Bearer ${token}`);
  }

  const res = await fetch(`${API_BASE}${path}`, {
    method: opts.method ?? 'GET',
    credentials: opts.withCredentials ? 'include' : 'same-origin',
    headers: headers,
    body: opts.body ? JSON.stringify(opts.body) : undefined
  })

  // Nếu nhận được 401 Unauthorized, có thể token đã hết hạn, nên xóa đi và reload
  if (res.status === 401) {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
    // Không reload để tránh vòng lặp vô hạn, chỉ throw lỗi
    // window.location.reload();
    throw new Error('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
  }

  const isJson = res.headers.get('content-type')?.includes('application/json')
  const data = isJson ? await res.json() : await res.text()

  if (!res.ok) {
    const msg = isJson && data?.message ? data.message : `HTTP ${res.status}`
    throw new Error(msg)
  }

  return data
}
