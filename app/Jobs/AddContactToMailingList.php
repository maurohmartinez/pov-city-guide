<?php

namespace App\Jobs;

use App\Models\User;
use Brevo\Brevo;
use Brevo\Contacts\Requests\CreateContactRequest;
use Brevo\Exceptions\BrevoApiException;
use Brevo\Exceptions\BrevoException;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AddContactToMailingList implements ShouldQueue
{
    use Queueable, Dispatchable, Batchable;

    public function __construct(public int $userId)
    {
    }

    /**
     * @throws BrevoApiException|BrevoException
     */
    public function handle(): void
    {
        $user = User::findOrFail($this->userId);

        try {
            $client = new Brevo(apiKey: config('app.brevo_api_key'), options: ['timeout' => 30]);
            $client->contacts->createContact(new CreateContactRequest([
                'email' => $user->email,
                'attributes' => [
                    'FIRSTNAME' => $user->name,
                ],
                'listIds' => [(int) config('app.brevo_mailing_list_id')],
                'updateEnabled' => true,
            ]));
        } catch (\Exception $e) {
            Log::error($e);

            return;
        }

        $user->update([
            'extras' => [
                'added_to_promotion_emails' => true,
            ],
        ]);
    }
}
