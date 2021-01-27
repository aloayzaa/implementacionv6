<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user = 'root'; // localhost y servidor
    protected $pass = 'W0won5$5'; // localhost y servidor
    protected $conf_bd = 'nuevosprinter_'; //para el nombre

    protected $host;
    protected $ruc;

    public $timeout = 0;
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param $ruc
     */

    public function __construct($ruc)
    {
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
        $file = public_path() . '/nuevosprinter.sql';

        exec(public_path() . '/ejecuta.sh ' . $database_name . " " . $this->host ); //utf8 por latin1
        exec('/usr/bin/mysql -h '. $this->host.' --init-command="SET SESSION FOREIGN_KEY_CHECKS=0;" --default-character-set=utf8 -u root \'-pW0won5$5\' --port=3306 --max_allowed_packet=1024M ' . $database_name . ' < ' . $file . '');


    }
}
