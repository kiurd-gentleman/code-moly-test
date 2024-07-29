<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request, $quizId)
    {
        $this->validate($request, [
            'question_text' => 'required|string',
            'correct_answer' => 'required|string',
            'score' => 'required|integer',
            'negative_mark' => 'required|integer',
        ]);

        $question = Question::create([
            'quiz_id' => $quizId,
            'question_text' => $request->question_text,
            'correct_answer' => $request->correct_answer,
            'score' => $request->score,
            'negative_mark' => $request->negative_mark,
        ]);

        return response()->json($question, 201);
    }

    public function index($quizId)
    {
        return Question::where('quiz_id', $quizId)->get();
    }
}
