<?php

class Account extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('accounts');
        $this->hasColumn('username', 'string');
        $this->hasColumn('email', 'string');
    }
}
