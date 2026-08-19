<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AtividadePendenteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $dadosAtividade;

    /**
     * Recebe informações personalizadas sobre o prazo
     */
    public function __construct(array $dadosAtividade)
    {
        $this->dadosAtividade = $dadosAtividade;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Atenção: Prazo de Atividade Próximo do Fim')
            ->greeting('Atenção, ' . $notifiable->nome)
            ->line('Identificamos que você possui uma atividade pendente com o prazo quase expirando.')
            ->line('**Atividade:** ' . $this->dadosAtividade['nome_atividade'])
            ->line('**Curso:** ' . $this->dadosAtividade['nome_curso'])
            ->line('**Prazo Limite:** ' . $this->dadosAtividade['data_limite'])
            ->action('Responder Atividade Agora', url($this->dadosAtividade['url_link']))
            ->line('Não perca o prazo para garantir seus 100% de conclusão!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'atividade' => $this->dadosAtividade['nome_atividade'],
            'mensagem' => 'A atividade ' . $this->dadosAtividade['nome_atividade'] . ' vence em breve.',
        ];
    }
}