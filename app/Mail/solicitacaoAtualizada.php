<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class solicitacaoAtualizada extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $categoria;
    public string $dataCriacao;
    public string $status;
    public string $token;

    public function __construct(string $userName, string $categoria, string $dataCriacao, string $status, string $token)
    {
        $this->userName = $userName;
        $this->categoria = $categoria;
        $this->dataCriacao = $dataCriacao;
        $this->status = $status;
        $this->token = $token;
    }

    public function build()
    {
        return $this->view('emails.solicitacao_atualizada')
            ->subject('Atualização na sua solicitação!')
            ->with([
                'userName' => $this->userName,
                'categoria' => $this->categoria,
                'dataCriacao' => $this->dataCriacao,
                'status' => $this->status,
                'token' => $this->token
            ]);
    }
}
