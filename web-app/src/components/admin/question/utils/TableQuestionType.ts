export const getQuestionTypeColor = (type: string): string => ({
  single: 'blue',
  multiple_choice: 'green',
  essay: 'orange',
  true_false: 'purple',
  fill_blank: 'cyan',
}[type] || 'default')

export const getQuestionTypeIcon = (type: string): string => ({
  single: '🔘',          // Single choice
  multiple_choice: '☑️', // Multiple choice
  essay: '📝',           // Essay / writing
  true_false: '✅',      // True/False
  fill_blank: '📄',      // Fill in the blanks
}[type] || '❓')

export const getQuestionTypeLabel = (type: string): string => ({
  single: 'Single Choice',
  multiple_choice: 'Multiple Choice',
  essay: 'Essay',
  true_false: 'True / False',
  fill_blank: 'Fill in the Blank',
}[type] || 'Unknown Type')
