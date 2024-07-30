<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        return Quiz::with('subject')->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'subject_id' => 'required|integer',
        ]);

        $quiz = Quiz::create([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'user_id' => auth()->id(),
        ]);

        return response()->json($quiz, 201);
    }

    public function show($id)
    {
        return Quiz::with('questions')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'subject_id' => 'required|integer',
        ]);

        $quiz = Quiz::findOrFail($id);
        $quiz->update([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
        ]);

        return response()->json($quiz, 200);
    }
}
