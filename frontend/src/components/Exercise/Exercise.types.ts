import { ILangs } from "@/types/Langs.types";

export type IProps = {
  exercise: IExercise,
  number: number,
}

export type IExercise = {
  id: number;
  question: ILangs
  answer: string;
  solution: ILangs | null;
  img_url: string | null;
  video_url: string | null;
  user_id: number;
  category_exercise_id: number | null;
  level_exercise_id: number | null;
  subject_id: number;
  is_ai: boolean;
  is_public: boolean;
  is_approved: boolean;
  created_at?: Date;
  updated_at?: Date;
};