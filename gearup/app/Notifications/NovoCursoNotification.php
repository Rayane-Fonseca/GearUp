<?php

namespace App\Notifications;

use App\Models\Curso;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NovoCursoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $curso;

    /**
     * Passamos o Model do curso recém-criado
     */
    public function __construct(Curso $curso)
    {
        $this->curso = $curso;
    }

    /**
     * Define os canais de envio. 
     * 'mail' envia e-mail, 'database' grava no banco para ler no painel (Filament).
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Estrutura o e-mail que o aluno vai receber
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🚀 Novo Curso Disponível: ' . $this->curso->titulo)
            ->greeting('Olá, ' . $notifiable->nome . '!')
            ->line('Um novo curso que pode te interessar acabou de ser lançado na plataforma.')
            ->line('**Curso:** ' . $this->curso->titulo)
            ->line('**Carga Horária:** ' . $this->curso->carga_horaria . ' horas')
            ->action('Ver Detalhes do Curso', url('/cursos/' . ($this->curso->id_curso ?? $this->curso->id)))
            ->line('Bons estudos!');
    }

    /**
     * Dados salvos na tabela 'notifications' do banco (útil para o painel de avisos)
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id_curso' => $this->curso->id_curso ?? $this->curso->id,
            'titulo' => $this->curso->titulo,
            'mensagem' => 'O curso ' . $this->curso->titulo . ' já está disponível para inscrição.',
        ];
    }
}