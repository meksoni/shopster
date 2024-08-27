<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Helpers;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $attachFile;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData, $attachFile = null)
    {
        $this->mailData = $mailData;
        $this->attachFile = $attachFile;
    }



    public function build()
    {
        $order = $this->mailData['order'];
        $email = $this->subject($this->mailData['subject'])
                      ->view('email.order')
                      ->with([
                        'order' => $order,
                        'qrCode' => $this->mailData['qrCode']
                    ]);;

        if ($this->attachFile) {
            $email->attach($this->attachFile, [
                'as' => 'ips_bank-nalog-' .$order->id. '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }


}
