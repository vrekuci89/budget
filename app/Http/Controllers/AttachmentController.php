<?php

namespace App\Http\Controllers;

use App\Repositories\AttachmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{
    private $attachmentRepository;

    public function __construct(AttachmentRepository $attachmentRepository)
    {
        $this->attachmentRepository = $attachmentRepository;
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required|in:earning,spending',
            'transaction_id' => 'required',
            'yeet' => 'required|mimes:jpeg,png,pdf'
        ]);

        $fileName = Str::random(20) . '.' . $request->file('yeet')->extension();
        $filePath = $request->file('yeet')->storeAs('attachments', $fileName);

        $transactionType = $request->transaction_type;
        $transactionId = $request->transaction_id;

        $this->attachmentRepository->create($transactionType, $transactionId, $filePath);

        return redirect('/' . $transactionType . 's/' . $transactionId);
    }

    public function delete(Request $request, string $id)
    {
        $attachment = $this->attachmentRepository->getById($id);

        if (!$attachment) {
            abort(404);
        }

        // Memorize some details before we delete it
        $transactionType = $attachment->transaction_type;
        $transactionId = $attachment->transaction_id;

        $this->attachmentRepository->delete($id);

        return redirect('/' . $transactionType . 's/' . $transactionId);
    }
}