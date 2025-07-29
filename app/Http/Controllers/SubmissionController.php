<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmissionRequest;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

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

    public function exportCsv(): Response
    {
        $submissions = Submission::all();

        $csvData = [];
        $csvData[] = ['First Name', 'Last Name', 'Email', 'Company Name'];

        foreach ($submissions as $submission) {
            $csvData[] = [
                $submission->first_name,
                $submission->last_name,
                $submission->email,
                $submission->company_name,
            ];
        }

        $fileName = 'submissions_'.now()->format('Y-m-d H:i:s').'.csv';

        $handle = fopen('php://temp', 'wb+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"');
    }
}
