<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmissionRequest;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;

class SubmissionController extends Controller
{
    public function store(SubmissionRequest $request): RedirectResponse
    {
        if (Submission::query()->where('email', $request->email)->exists()) {
            return redirect()->route('home');
        }

        Submission::create($request->validated());

        return redirect()->route('home');
    }
}
