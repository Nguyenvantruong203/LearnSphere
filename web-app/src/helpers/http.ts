const API_BASE = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

type HttpOptions = {
  method?: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
  headers?: Record<string, string>
  body?: unknown
  withCredentials?: boolean
  params?: Record<string, any>;
}

export async function http(path: string, opts: HttpOptions = {}) {
  const headers = new Headers({
    Accept: 'application/json',
    ...(opts.headers ?? {})
  });

  if (opts.body instanceof FormData) {
    // Khi gửi FormData, chúng ta phải xóa hoàn toàn header 'Content-Type'.
    // Điều này buộc trình duyệt phải tự động tạo ra một header đúng
    // với 'multipart/form-data' và một 'boundary' duy nhất.
    headers.delete('Content-Type');
  } else {
    // Đối với các yêu cầu JSON thông thường, chúng ta đặt header như bình thường.
    headers.set('Content-Type', 'application/json');
  }

  // Lấy token từ localStorage và tự động thêm vào header
  const token = localStorage.getItem('auth_token');
  if (token) {
    headers.set('Authorization', `Bearer ${token}`);
  }

  let finalPath = `${API_BASE}${path}`;
  if (opts.params) {
    const searchParams = new URLSearchParams();
    for (const key in opts.params) {
      if (opts.params[key] !== undefined && opts.params[key] !== null) {
        searchParams.append(key, opts.params[key]);
      }
    }
    if (searchParams.toString()) {
      finalPath += `?${searchParams.toString()}`;
    }
  }

  const res = await fetch(finalPath, {
    method: opts.method ?? 'GET',
    credentials: opts.withCredentials ? 'include' : 'same-origin',
    headers: headers,
    // Nếu là FormData, gửi thẳng. Nếu không, stringify như bình thường.
    body: opts.body instanceof FormData ? opts.body : (opts.body ? JSON.stringify(opts.body) : undefined)
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
