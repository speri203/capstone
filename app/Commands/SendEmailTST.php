<?php namespace NGAFID\Commands;

use NGAFID\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use NGAFID\User;
use Mail;
use Illuminate\Contracts\Mail\Mailer;

class SendEmailTST extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
    protected $user;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user)
	{
        $this->user = $user;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle(Mailer $mailer)
	{
        sleep(2);
        $data = array(
            'email' => $this->user->email,
            'name'  => $this->user->firstname,
        );
        $mailer->send('emails.reminder', $data, function ($message) use ($data) {
            $message->to( $data['email'] )->from( 'donotreply@sctest.com', $data['name'] )->subject( 'Testing...' );
        });
	}

}
