<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class confirmaSolicitacao extends Mailable
{
    use Queueable, SerializesModels;

    public string $token;
    public string $userName;

    public function __construct(string $token, string $userName)
    {
        $this->token = $token;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->view('emails.confirma_solicitacao')
            ->subject('Solicitação criada com sucesso!')
            ->with([
                'token' => $this->token,
                'userName' => $this->userName
            ]);
    }
}
