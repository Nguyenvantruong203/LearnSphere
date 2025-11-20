import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

// Lấy token chính xác theo vai trò hiện có
function getToken() {
  const path = window.location.pathname
  const isAdmin = path.startsWith('/admin') || path.startsWith('/instructor')

  const admin = JSON.parse(localStorage.getItem('admin_auth') || '{}')
  const instructor = JSON.parse(localStorage.getItem('instructor_auth') || '{}')
  const client = JSON.parse(localStorage.getItem('client_auth') || '{}')

  if (isAdmin && admin?.token) return admin?.token
  if (isAdmin && instructor?.token) return instructor?.token
  if (!isAdmin && client?.token) return client?.token
  return ''
}

const token = getToken()

const echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY || 'chat_key_123',
  wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname,
  wsPort: import.meta.env.VITE_PUSHER_PORT ? Number(import.meta.env.VITE_PUSHER_PORT) : 6001,
  wssPort: import.meta.env.VITE_PUSHER_PORT ? Number(import.meta.env.VITE_PUSHER_PORT) : 6001,
  forceTLS: false,
  disableStats: true,
  enabledTransports: ['ws', 'wss'],
  cluster: 'mt1',
  authEndpoint: `${import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'}/api/broadcasting/auth`,

  auth: {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: 'application/json',
    },
  },
})

export default echo
