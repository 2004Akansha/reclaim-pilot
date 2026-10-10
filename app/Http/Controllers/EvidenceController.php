<?php

namespace App\Http\Controllers;

use App\Models\Evidence;
use App\Models\RecoveryCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    /**
     * Upload a file for an evidence item.
     */
    public function upload(
        Request $request,
        RecoveryCase $case,
        Evidence $evidence
    ) {
        /*
        |--------------------------------------------------------------------------
        | Make sure this evidence belongs to this case
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $evidence->recovery_case_id === $case->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Validate uploaded file
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:10240',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Delete previous file
        |--------------------------------------------------------------------------
        */

        if ($evidence->file_path) {

            Storage::disk('public')
                ->delete($evidence->file_path);
        }


        /*
        |--------------------------------------------------------------------------
        | Store new file
        |--------------------------------------------------------------------------
        */

        $file = $validated['file'];

        $path = $file->store(
            'evidence',
            'public'
        );


        /*
        |--------------------------------------------------------------------------
        | Save file information
        |--------------------------------------------------------------------------
        */

        $evidence->update([
            'file_path' => $path,

            'original_name' =>
                $file->getClientOriginalName(),

            'mime_type' =>
                $file->getMimeType(),

            'file_size' =>
                $file->getSize(),

            'completed' => true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Return to Prepare page
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('case.prepare', $case)
            ->with(
                'success',
                $evidence->title . ' uploaded successfully.'
            );
    }
}