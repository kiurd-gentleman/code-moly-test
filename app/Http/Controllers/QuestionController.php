<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index($quizId)
    {
        return Question::with('options')->where('quiz_id', $quizId)->get();
    }

    public function store(Request $request, $quizId)
    {
        $this->validate($request, [
            'question_text' => 'required|string',
            'correct_answer' => 'required|integer',
            'options' => 'required|array',
            'score' => 'required|integer',
            'negative_mark' => 'nullable|integer|lt:score',
        ]);

        try {
            DB::beginTransaction();
            $question = Question::create([
                'quiz_id' => $quizId,
                'question_text' => $request->question_text,
                'correct_answer' => $request->correct_answer,
                'score' => $request->score,
                'negative_mark' => $request->negative_mark ?? 0,
            ]);

            foreach ($request->options as $key => $option) {
                $question->options()->create([
                    'option' => $key+1,
                    'option_text' => $option['text'],
                    'is_correct' => (bool)$option['is_correct'],
                ]);
            }

            DB::commit();
            return response()->json($question, 201);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        Question::destroy($id);
        return response()->json(null, 204);
    }
}
