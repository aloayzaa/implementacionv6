<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateConecction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $user = 'root'; // localhost y servidor
    protected $pass = 'W0won5$5'; // localhost y servidor
    protected $conf_bd = 'nuevosprinter_'; //para el nombre

    protected $host;
    protected $ruc;
    public $tries = 1;

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
        $filename = config_path() . '/database.php';

        $file = fopen($filename, "r+");
        $lineas = file($filename);
        foreach ($lineas as $num_linea => $linea) {
            if ($num_linea == 63) {
                fwrite($file, "
                'DB_CONNECTION_" . $this->ruc . "' => [
                    'driver'    => 'mysql',
                    'host'  => '" . $this->host . "',
                    'database'  => '" . $this->conf_bd . $this->ruc . "',
                    'username'  => '" . $this->user . "',
                    'password'  => '" . $this->pass . "',
                    'charset' => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => '',
                    'port'      => '3306',
                    'strict'    => false,
                ],
                ");
            }

            fwrite($file, $linea);
        }
        fclose($file);
    }
}
