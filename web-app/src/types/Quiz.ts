import type { Topic } from './Topic'
import type { Lesson } from './Lesson'
import type { Question } from './Question'

/* ========= 🔹 Quiz ========= */
export interface Quiz {
  id: number
  topic_id: number
  lesson_id?: number
  title: string
  duration_minutes: number
  shuffle_questions: boolean
  shuffle_options: boolean
  max_attempts: number
  created_at: string
  updated_at: string
  topic?: Topic
  lesson?: Lesson
  questions?: Question[]
}

export type QuizPayload = Omit<
  Quiz,
  'id' | 'created_at' | 'updated_at' | 'topic' | 'lesson' | 'questions'
>

/* ========= 🔹 Quiz Attempt ========= */
export interface QuizAttempt {
  id: number
  user_id: number
  quiz_id: number
  attempt_no: number
  status: 'in_progress' | 'completed' | 'expired'
  score: number
  max_score: number
  correct_count: number
  wrong_count: number
  duration_seconds: number
  started_at: string
  submitted_at: string | null
  created_at: string
  updated_at: string
}

/* ========= 🔹 Quiz Attempt Answers ========= */
export interface QuizAttemptAnswer {
  id?: number
  attempt_id: number
  question_id: number
  selected_options: string[]
  is_correct?: boolean
  points_awarded?: number
}

/* ========= 🔹 Payloads ========= */
export interface QuizSubmitPayload {
  attempt_id: number
  answers: {
    question_id: number
    selected_options: string[]
  }[]
}

/* ========= 🔹 API Responses ========= */
export interface QuizDetailResponse {
  success: boolean
  data: Quiz
}

export interface QuizStartResponse {
  success: boolean
  data: {
    attempt: QuizAttempt
  }
}

export interface QuizSubmitResponse {
  success: boolean
  data: {
    attempt: QuizAttempt
    correct_count: number
    wrong_count: number
    score: number
    max_score: number
  }
}

export interface QuizReviewResponse {
  success: boolean
  data: {
    attempt: QuizAttempt
    answers: (QuizAttemptAnswer & {
      question_id: number
      text: string
      options: string[]
      correct_options: string[]
    })[]
  }
}

export interface QuizDetailData {
  id: number
  title: string
  description?: string
  duration_minutes: number
  total_questions: number
  topic?: Pick<Topic, 'id' | 'title'>
  lesson?: Pick<Lesson, 'id' | 'title'>
  questions: Question[]
  user_attempt?: {
    score: number
    completed_at: string
  } | null
}

export interface QuizDetailResponse {
  success: boolean
  data: QuizDetailData
}
