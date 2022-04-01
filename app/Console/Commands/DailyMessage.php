<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeCertification;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;

class DailyMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan command to send daily messages';

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
     * @return int
     */
    public function handle()
    {

        $message = [
            'message' => 'Hello Folks. ',
            'message' => 'Did you know that you are the child of your mother. ',
            'message' => 'Not all curves are straight. ',
        ];

        $key = array_rand($message);
        $value = $message[$key];



        $users = User::all();
        foreach ($users as $user) {
            Mail::raw("{$key} -> {$value} \n{$str}", function ($mail) use ($user) {
                $mail->from('genesis.tech@io');
                $mail->to(['saturnolicious@gmail.com'])
                    ->subject('One Email per minute');
            });
        }



    }
}
