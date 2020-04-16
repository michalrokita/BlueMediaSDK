<?php


namespace michalrokita\BlueMediaSDK\Notifications;


use LaLit\XML2Array;
use michalrokita\BlueMediaSDK\DataTypes\BMConfirmation;
use michalrokita\BlueMediaSDK\Helpers\BMHash;
use michalrokita\BlueMediaSDK\DataTypes\BMNotification;
use michalrokita\BlueMediaSDK\Exceptions\InvalidHashException;
use michalrokita\BlueMediaSDK\Exceptions\NotificationNotReceivedException;
use michalrokita\BlueMediaSDK\BMService;
use michalrokita\BlueMediaSDK\Helpers\Response;

class Receiver
{
    /**
     * @var BMNotification|null
     */
    private $notification;

    /**
     * Receiver constructor.
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     */
    public function __construct()
    {
        $this->handleNotification();
    }

    /**
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     * @throws \Exception
     */
    public function confirmReceivingNotification(): void
    {
        $confirmation = (new BMConfirmation($this->notification->getOrderId()))->toArray();

        echo Response::XML($confirmation);
    }

    /**
     * Returns params from the notification sent by BlueMedia
     * @return array
     * @throws InvalidHashException
     * @throws NotificationNotReceivedException
     */
    public function getNotification(): array
    {
        if ($this->notification === null) {
            throw new NotificationNotReceivedException();
        }

        if (!$this->notification->isValid()) {
            throw new InvalidHashException();
        }

        return $this->notification->getParams();
    }

    /**
     * @param string $data Base64 encoded string containing XML file
     * @return array
     * @throws \Exception
     */
    private function decode(string $data): array
    {
        $data = base64_decode($data);
        $xml = XML2Array::createArray($data);
        $xml = $xml['transactionList'];

        return [
            'serviceId' => $xml['serviceID'],
            'params' => $xml['transactions']['transaction'],
            'hash' => $xml['hash']
        ];
    }

    /**
     * Check if notification was received and if it was, the method verifies it and save
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     */
    private function handleNotification(): void
    {
        try {
            $raw = $this->getRawNotification();
        } catch (NotificationNotReceivedException $e) {
            $this->notification = null;
            return;
        }

        $decoded = $this->decode($raw);

        $this->notification = new BMNotification(
            $decoded['serviceId'],
            $decoded['params'],
            $decoded['hash']
        );

        BMHash::verify($this->notification);
    }

    /**
     * @return string|null
     * @throws NotificationNotReceivedException
     */
    private function getRawNotification(): ?string
    {
        if (isset($_POST['transactions'])) {
            return $_POST['transactions'];
        }

        throw new NotificationNotReceivedException();
    }
}