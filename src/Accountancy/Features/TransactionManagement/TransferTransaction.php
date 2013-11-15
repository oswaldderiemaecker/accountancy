<?php
/**
 *
 */

namespace Accountancy\Features\TransactionManagement;

use Accountancy\Entity\Category;
use Accountancy\Entity\User;
use Accountancy\Features\FeatureException;

/**
 * Class TransferTransaction
 *
 * @package Accountancy\Features\TransactionManagement
 */
class TransferTransaction
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var integer
     */
    protected $fromAccountId;

    /**
     * @var integer
     */
    protected $toAccountId;

    /**
     * @var double
     */
    protected $amount = 0.0;

    /**
     * @param \Accountancy\Entity\User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param integer $fromAccountId
     *
     * @return $this
     */
    public function setFromAccountId($fromAccountId)
    {
        $this->fromAccountId = (int) $fromAccountId;

        return $this;
    }

    /**
     * @param integer $toAccountId
     *
     * @return $this
     */
    public function setToAccountId($toAccountId)
    {
        $this->toAccountId = (int) $toAccountId;

        return $this;
    }

    /**
     * @param double $amount
     */
    public function setAmount($amount)
    {
        $this->amount = (double) $amount;
    }

    /**
     * @throws \Accountancy\Features\FeatureException
     */
    public function run()
    {
        $toAccount = $this->user->findAccountById($this->toAccountId);

        if (is_null($toAccount)) {
            throw new FeatureException("Target account doesn't exist");
        }

        $fromAccount = $this->user->findAccountById($this->fromAccountId);

        if (is_null($fromAccount)) {
            throw new FeatureException("Source account doesn't exist");
        }

        if ($toAccount->getCurrencyId() != $fromAccount->getCurrencyId()) {
            throw new FeatureException("Currency is't supported by target account");
        }

        if ($this->amount <= 0.0) {
            throw new FeatureException("Amount of money should be greater than zero");
        }

        $accounts = $this->user->getAccounts();

        foreach ($accounts as $key => $value) {

            if ($value->getId() === $fromAccount->getId()) {
                $accounts[$key]->decreaseBalance($this->amount);
            } elseif ($value->getId() === $toAccount->getId()) {
                $accounts[$key]->increaseBalance($this->amount);
            }
        }

        $this->user->setAccounts = $accounts;
    }
}
