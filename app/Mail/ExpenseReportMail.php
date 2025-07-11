<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

class ExpenseReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Expense Report',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'expenses.email',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->path)
                ->as('Expense_Report.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
