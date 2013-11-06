<?php

/**
 *
 */

namespace Accountancy\Entity;

use Accountancy\Entity\Account;

/**
 * User Entity
 */
class User
{
    protected $accounts = array();

    /**
     * @param array $accounts
     *
     * @return User
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * @return array
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @param Account $account
     *
     * @throws \LogicException
     * @return $this
     */
    public function addAccount(Account $account)
    {
        foreach ($this->accounts as $existingAccount) {
            if ($account->getName() === $existingAccount->getName()) {
                throw new \LogicException('Name of Account should be unique');
            }
        }

        $this->accounts[] = $account;

        return $this;
    }
}
