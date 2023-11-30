<?php

namespace Utopia\Messaging\Adapter\SMS;

use Utopia\Messaging\Adapter\SMS as SMSAdapter;
use Utopia\Messaging\Messages\SMS as SMSMessage;

class Telnyx extends SMSAdapter
{
    /**
     * @param  string  $apiKey Telnyx APIv2 Key
     */
    public function __construct(
        private string $apiKey,
        private ?string $from = null
    ) {
    }

    public function getName(): string
    {
        return 'Telnyx';
    }

    public function getMaxMessagesPerRequest(): int
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    protected function process(SMSMessage $message): string
    {
        $result = $this->request(
            method: 'POST',
            url: 'https://api.telnyx.com/v2/messages',
            headers: [
                'Authorization: Bearer '.$this->apiKey,
                'Content-Type: application/json',
            ],
            body: \json_encode([
                'text' => $message->getContent(),
                'from' => $this->from ?? $message->getFrom(),
                'to' => $message->getTo()[0],
            ]),
        );

        return \json_encode($result['response']);
    }
}