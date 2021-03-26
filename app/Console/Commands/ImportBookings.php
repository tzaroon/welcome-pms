<?php

namespace App\Console\Commands;

use App\Imports\BookingsImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports bookings from excel.';

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
        Excel::import(new BookingsImport, storage_path().DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'reservations-received.xlsx');
    }
}
