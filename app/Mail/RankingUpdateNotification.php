<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class RankingUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  Collection<int, Participant>  $topRanking
     */
    public function __construct(
        public Participant $participant,
        public int $position,
        public int $totalParticipants,
        public Collection $topRanking,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu posición en la Kinela Mundial 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ranking-update',
        );
    }
}
