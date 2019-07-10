<?php

namespace App\Console\Commands;

use App\Models\Chat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteChatMessageMore30Day extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:eraser-msj-30';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Chat::query()->where('status_id', 2)
            ->whereIn('type', ['text', 'emoji'])
            ->whereRaw('datediff(now(), created_at) >= 30')
            ->delete();

        $chats = Chat::query()->where('status_id', 2)
            ->where('type','file')
            ->whereRaw('datediff(now(), created_at) >= 30')
            ->get();
        foreach ($chats as $chat) {
            $patch = $chat->user_uid .'/files/'.$chat->msj;
            Storage::disk('public')->delete($patch);
            Chat::query()->where('id', $chat->id)->delete();
        }
    }
}
