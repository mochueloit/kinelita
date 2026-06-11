<?php

namespace App\Mail;

use App\Models\Participant;
use App\Services\ParticipantPdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParticipantPredictionsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Participant $participant,
        public int $position,
        public int $totalParticipants,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu kinela — Kinela Mundial 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.participant-predictions',
        );
    }

    public function attachments(): array
    {
        $pdfService = app(ParticipantPdfService::class);

        $this->participant->loadMissing('predictions');

        $filename = $pdfService->filename($this->participant);

        return [
            Attachment::fromData(
                fn () => $pdfService->output($this->participant),
                $filename,
            )->withMime('application/pdf'),
        ];
    }
}
