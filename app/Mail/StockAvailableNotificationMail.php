<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockAvailableNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Product $product,
        public ?Size $size = null
    ) {}

    public function envelope(): Envelope
    {
        $sizeText = $this->size ? " ({$this->size->size_number} Numara)" : "";
        return new Envelope(
            subject: "Müjde! '{$this->product->name}'{$sizeText} Yeniden Stokta | VELORA",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.stock-available',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
