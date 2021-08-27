<?php

namespace App\Console\Commands;

use App\Models\ContactDetail;
use App\Models\Conversation;
use Illuminate\Console\Command;
use PhpImap\Exceptions\ConnectionException;
use PhpImap\Mailbox;

class ImportEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:emails';

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
     * @return int
     */
    public function handle()
    {
        $mailbox = new Mailbox(
            '{outlook.office365.com:993/imap/ssl}INBOX', // IMAP server and mailbox folder
            'info@chicstays.com', // Username for the before configured mailbox
            '69018#klmjE', // Password for the before configured username
            storage_path('app/emails'), // Directory, where attachments will be saved (optional)
            'US-ASCII' // Server encoding (optional)
        );

        try {
            $mail_ids = $mailbox->searchMailbox('UNSEEN');
        } catch (ConnectionException $ex) {
            die('IMAP connection failed: '.$ex->getMessage());
        } catch (Exception $ex) {
            die('An error occured: '.$ex->getMessage());
        }
        $mail_ids = array_reverse($mail_ids);

        foreach ($mail_ids as $mail_id) {
            echo "+------ P A R S I N G ------+\n";

            $email = $mailbox->getMail(
                $mail_id, // ID of the email, you want to get
                true // Do NOT mark emails as seen (optional)
            );

            $contactDetail = ContactDetail::where('contact', $email->fromAddress)->orderBy('id', 'desc')->take(1)->first();
            
            if($contactDetail) {
                Conversation::create([
                    'contact_detail_id' => $contactDetail->id,
                    'from_user_id' => $contactDetail->user_id,
                    'to_user_id' => 1,
                    'message' => $email->textPlain,
                    'type' => 'email',
                    'is_viewed' => 0
                ]);
            }
            
        }
    }
}
