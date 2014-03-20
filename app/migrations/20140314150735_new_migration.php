<?php

use Phinx\Migration\AbstractMigration;

class NewMigration extends AbstractMigration
{
    
    /**
     * Migrate Up.
     */
    public function up()
    {
    	$table = $this->table('news');
        $table->addColumn('created', 'datetime')
              ->addColumn('name', 'string', array ('limit' => '255'))
              ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    	$table = $this->table('news');
    	$table->drop();
    }
}