<x-filament::page>
    <form wire:submit.prevent="sendNotification">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4">
            Отправить всем пользователям
        </x-filament::button>
    </form>
</x-filament::page>
