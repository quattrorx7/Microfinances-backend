<?php

namespace app\modules\client\dto;

use app\models\Client;
use app\models\User;
use app\modules\client\forms\ClientPayForm;

class PayDto
{
    public User $user;

    public Client $client;

    public $amount;

    public $advanceIds;

    public $inCart;

    /**
     * PayDto constructor.
     * @param User $user
     * @param Client $client
     * @param ClientPayForm $form
     */
    public function __construct(User $user, Client $client, ClientPayForm $form)
    {
        $this->user = $user;
        $this->client = $client;
        $this->amount = $form->amount;
        $this->advanceIds = $form->advance_ids;
        $this->inCart = $form->in_cart;
    }
}