<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Submit pertanyaan dari landing page (public)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'question' => 'required|string|max:1000',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email/WhatsApp wajib diisi',
            'email.email' => 'Format email tidak valid',
            'question.required' => 'Pertanyaan wajib diisi',
            'question.max' => 'Pertanyaan maksimal 1000 karakter',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Question::create([
            'name' => $request->name,
            'email' => $request->email,
            'question' => $request->question,
        ]);

        return back()->with('success', 'Terima kasih! Pertanyaan Anda telah dikirim dan akan dijawab oleh admin kami.');
    }

    /**
     * Halaman admin untuk melihat semua pertanyaan
     */
    public function index()
    {
        $questions = Question::latest()->paginate(20);
        return view('admin.questions', compact('questions'));
    }

    /**
     * Update jawaban dan publish
     */
    public function update(Request $request, Question $question)
    {
        $validator = Validator::make($request->all(), [
            'answer' => 'required|string|max:2000',
        ], [
            'answer.required' => 'Jawaban wajib diisi',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $question->update([
            'answer' => $request->answer,
            'answered_at' => now(),
        ]);

        return back()->with('success', 'Jawaban berhasil disimpan!');
    }

    /**
     * Toggle publish status
     */
    public function togglePublish(Question $question)
    {
        $question->update([
            'is_published' => !$question->is_published,
        ]);

        $status = $question->is_published ? 'dipublikasi' : 'disembunyikan';
        return back()->with('success', "Pertanyaan berhasil {$status}!");
    }

    /**
     * Hapus pertanyaan
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
