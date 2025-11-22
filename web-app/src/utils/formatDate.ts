export function formatReviewDate(dateString: string): string {
  const date = new Date(dateString)
  const now = new Date()

  const diffMs = now.getTime() - date.getTime()
  const diffSec = Math.floor(diffMs / 1000)
  const diffMin = Math.floor(diffSec / 60)
  const diffHour = Math.floor(diffMin / 60)
  const diffDay = Math.floor(diffHour / 24)

  // 👉 Cách hiển thị dạng "x phút trước"
  if (diffMin < 1) return "Vừa xong"
  if (diffMin < 60) return `${diffMin} phút trước`
  if (diffHour < 24) return `${diffHour} giờ trước`

  // 👉 Tính xem có phải hôm nay/hôm qua không
  const isToday = date.toDateString() === now.toDateString()

  const yesterday = new Date()
  yesterday.setDate(now.getDate() - 1)
  const isYesterday = date.toDateString() === yesterday.toDateString()

  const time = date.toLocaleTimeString("vi-VN", { hour: "2-digit", minute: "2-digit" })

  if (isToday) return `Hôm nay • ${time}`
  if (isYesterday) return `Hôm qua • ${time}`

  const day = String(date.getDate()).padStart(2, "0")
  const month = String(date.getMonth() + 1).padStart(2, "0")
  const year = date.getFullYear()

  return `${day}/${month}/${year}`
}
