// Màu hiển thị cho từng loại câu hỏi
export const getQuestionTypeColor = (type: string): string => ({
  single: 'blue',
  multiple: 'green',
  essay: 'orange',
  true_false: 'purple',
  fill_blank: 'cyan',
}[type] || 'default')

// Icon cho từng loại câu hỏi
export const getQuestionTypeIcon = (type: string): string => ({
  single: '🔘',
  multiple: '☑️',
  essay: '📝',
  true_false: '✅',
  fill_blank: '📄',
}[type] || '❓')

// Nhãn (label) hiển thị cho từng loại câu hỏi
export const getQuestionTypeLabel = (type: string): string => ({
  single: 'Một đáp án',
  multiple: 'Nhiều đáp án',
  essay: 'Tự luận',
  true_false: 'Đúng/Sai',
  fill_blank: 'Điền khuyết',
}[type] || 'Không xác định')
