export type CourseStatus = 'draft' | 'approved' | 'archived' | 'pending' | 'rejected';

/**
 * 🔵 Màu sắc cho trạng thái của khóa học
 */
export const getStatusColor = (status: CourseStatus): string => {
  switch (status) {
    case 'approved':
      return 'green'
    case 'pending':
      return 'orange'
    case 'rejected':
      return 'red'
    case 'draft':
      return 'gray'
    case 'archived':
      return 'purple'
    default:
      return 'default'
  }
}

/**
 * 🏷 Nhãn tiếng Việt cho trạng thái
 */
export const getStatusLabel = (status: CourseStatus): string => {
  switch (status) {
    case 'approved':
      return 'Đã xuất bản'
    case 'pending':
      return 'Chờ duyệt'
    case 'rejected':
      return 'Đã từ chối'
    case 'draft':
      return 'Bản nháp'
    case 'archived':
      return 'Đã lưu trữ'
    default:
      return status
  }
}

/**
 * (optional) Nếu muốn icon cho tag
 */
export const getStatusIcon = (status: CourseStatus): string => {
  switch (status) {
    case 'approved':
      return '✔'
    case 'pending':
      return '⏳'
    case 'rejected':
      return '✖'
    case 'draft':
      return '📄'
    case 'archived':
      return '📦'
    default:
      return ''
  }
}
