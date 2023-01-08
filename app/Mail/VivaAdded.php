<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Viva;
use Illuminate\Http\Request;

class VivaAdded extends Mailable
{
    protected $data_array;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $code = $request->code;
        $viva = Viva::where('code', $code)->get();
        $this->data_array = ['name' => $viva[0]['project_name'] ,
        'year' => $viva[0]['year'] ,
        'sup_mark' => $viva[0]['sup_mark'] ,
         'pre_mark'=> $viva[0]['pre_mark'] ,
         'exa_mark' => $viva[0]['exa_mark'],
           'sup_name' => $viva[0]['sup_name'],
           'pre_name' => $viva[0]['pre_name'],
           'exa_name' => $viva[0]['exa_name'],
           'final_mark' => $viva[0]['final_mark'],
           'students' => (json_decode($viva[0]['students'],true)),
        'Vivacode' => $code];

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('viva.rest.api@gmail.com', 'Viva App'),
        subject: 'Viva Created',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {

        return new Content(

            view: 'welcome',
            with: $this->data_array
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
