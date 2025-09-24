import type { Quiz } from './Quiz';

export interface QuestionOption {
  key: string;
  text: string;
}

export interface Question {
  id: number;
  quiz_id: number;
  type: 'single' | 'multi' | 'truefalse' | 'fillblank';
  text: string;
  options?: QuestionOption[];
  correct_options?: string[];
  weight: number;
  created_at: string;
  updated_at: string;
  quiz?: Quiz;
}

export type QuestionPayload = Omit<Question, 'id' | 'created_at' | 'updated_at' | 'quiz'>;
