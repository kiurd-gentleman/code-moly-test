<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function store(Request $request, $quizId)
    {
        $this->validate($request, [
            'answers' => 'required|array',
        ]);

        $quiz = Quiz::with('questions')->findOrFail($quizId);
        $score = 0;
        $totalScore = 0;

        foreach ($quiz->questions as $question) {
            $totalScore += $question->score;
            if (isset($request->answers[$question->id]) && $request->answers[$question->id] == $question->correct_answer) {
                $score += $question->score;
            } else {
                $score -= $question->negative_mark;
            }
        }

        $result = Result::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quizId,
            'score' => $score,
            'total_score' => $totalScore,
        ]);

        return response()->json($result, 201);
    }

    public function index()
    {
        $results = Result::with('user', 'quiz')->get()->groupBy('quiz_id');
        $rankings = [];

        foreach ($results as $quizId => $quizResults) {
            $rankedResults = $quizResults->sortByDesc('score')->values();
            foreach ($rankedResults as $rank => $result) {
                $rankings[] = [
                    'user' => $result->user->name,
                    'quiz' => $result->quiz->title,
                    'score' => $result->score,
                    'rank' => $rank + 1,
                ];
            }
        }

        return response()->json($rankings);
    }
}
