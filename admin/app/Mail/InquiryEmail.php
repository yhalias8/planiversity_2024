<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InquiryEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $author_name;
    public $service_name;
    public $subject;
    public $current_timestamp;
    public $user_name;
    public $user_email;
    public $user_phone;
    public $user_message;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($author_name, $service_name, $subject, $current_timestamp, $user_name, $user_email, $user_phone, $user_message)
    {
        $this->author_name = $author_name;
        $this->service_name = $service_name;
        $this->subject = $subject;
        $this->current_timestamp = $current_timestamp;
        $this->user_name = $user_name;
        $this->user_email = $user_email;
        $this->user_phone = $user_phone;
        $this->user_message = $user_message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->markdown('emails.send');
        return $this->from('orders@planiversity.com', 'Planiversity')
            ->subject($this->subject)
            ->markdown('emails.inquiry');
    }
}
