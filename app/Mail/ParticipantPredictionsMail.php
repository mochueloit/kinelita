<?php

namespace App\Mail;

use App\Models\Fixture;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ParticipantPredictionsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param  Collection<int, Fixture>  $fixtures
     */
    public function __construct(
        public Participant $participant,
        public Collection $fixtures,
        public int $position,
        public int $totalParticipants,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tus pronósticos — Kinela Mundial 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.participant-predictions',
        );
    }
}
