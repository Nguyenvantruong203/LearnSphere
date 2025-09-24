import type { Topic } from './Topic';
import type { Lesson } from './Lesson';
import type { Question } from './Question';

export interface Quiz {
  id: number;
  topic_id: number;
  lesson_id?: number;
  title: string;
  duration_minutes: number;
  shuffle_questions: boolean;
  shuffle_options: boolean;
  max_attempts: number;
  created_at: string;
  updated_at: string;
  topic?: Topic;
  lesson?: Lesson;
  questions?: Question[];
}

export type QuizPayload = Omit<Quiz, 'id' | 'created_at' | 'updated_at' | 'topic' | 'lesson' | 'questions'>;
