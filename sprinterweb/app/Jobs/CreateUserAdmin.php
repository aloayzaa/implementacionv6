<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateUserAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $ruc;
    protected $host;

    protected $conf_bd = 'nuevosprinter_'; //para el nombre

    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $ruc
     */
    public function __construct(User $user, $ruc)
    {
        $this->user = $user;
        $this->ruc = $ruc;
        $this->host = env('APP_ENV') == 'local' ? '192.168.0.17' : 'localhost';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $database_name = $this->conf_bd . $this->ruc;
        exec(public_path() . '/create_admin.sh ' . $database_name . " " . escapeshellarg ($this->user->password)  . " " . $this->user->email . " " . $this->host);
    }
}
