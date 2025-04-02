<?php

namespace App\Jobs;

use Throwable;
use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail implements ShouldQueue, ShouldBeUnique
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $tries = 3;
    public $timeout = 120;
    public $deleteWhenMissingModels = true;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }
    
    public function backoff(): array
    {
        return [3, 6, 10];
    }

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function failed(?Throwable $exception)
    {
        Log::error('Failed to Send Welcome Email: ' . $exception->getMessage());
    }
}
