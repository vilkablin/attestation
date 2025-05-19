<?php

namespace App\Filament\Admin\Pages;


use App\Models\User;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendTelegramNotification extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationGroup = 'Уведомления'; // если хочешь группу
    protected static ?int $navigationSort = 2; // опционально для порядка
    protected static ?string $navigationLabel = 'Telegram Рассылка'; // метка в меню
    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane'; // иконка
    protected static ?string $title = 'Отправить уведомление в Telegram';


    protected static string $view = 'filament.admin.pages.send-telegram-notification';


    public ?string $message = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Textarea::make('message')
                ->label('Текст уведомления')
                ->required()
                ->rows(6)
                ->columnSpan('full'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('send')
                ->label('Отправить всем пользователям')
                ->action('sendNotification')
                ->color('primary'),
        ];
    }

    public function sendNotification()
    {
        $data = $this->form->getState();
        $message = $data['message'];

        if (!$message) {
            Notification::make()
                ->title('Текст не может быть пустым')
                ->danger()
                ->send();
            return;
        }

        $users = User::whereNotNull('telegram_chat_id')->get(); // Убедись, что такая колонка есть

        foreach ($users as $user) {
            try {
                Telegram::bot()->sendMessage([
                    'chat_id' => $user->telegram_chat_id,
                    'text' => $message,
                ]);
            } catch (\Exception $e) {
                // Можно логировать $e->getMessage()
                continue;
            }
        }

        Notification::make()
            ->title('Уведомление успешно отправлено!')
            ->success()
            ->send();

        $this->form->fill(); // Очистим форму
    }
}
