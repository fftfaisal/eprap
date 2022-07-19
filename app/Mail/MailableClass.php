<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailableClass extends Mailable {
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $data ) {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $mailable = $this;

        if ( isset( $this->data['mail_from'] ) ) {
            $mailable->from( $this->data['mail_from'] );
        }

        if ( isset( $this->data['subject'] ) ) {
            $mailable->subject( $this->data['subject'] ?? 'ePRAP' );
        }

        if ( isset( $this->data['cc'] ) ) {
            $mailable->cc( $this->data['cc'] );
        }

        if ( isset( $this->data['bcc'] ) ) {
            $mailable->bcc( $this->data['bcc'] );
        }

        if ( isset( $this->data['reply_to'] ) ) {
            $mailable->replyTo( $this->data['reply_to'] );
        }

        if ( isset( $this->data['attachments'] ) ) {
            $mailable->attach( $this->data['attachments'] );
        }

        $mailable->markdown( $this->data['template'] );

        return $mailable;

    }

}
