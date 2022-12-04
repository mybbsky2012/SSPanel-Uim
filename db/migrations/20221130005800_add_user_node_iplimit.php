<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddUserNodeIplimit extends AbstractMigration
{
    public function up(): void
    {
        if (! $this->table('user')->hasColumn('node_iplimit')) {
            $this->table('user')
                ->addColumn('node_iplimit', 'integer', [ 'comment' => '同时可连接IP数', 'default' => 0 ])
                ->save();
        }
    }

    public function down(): void
    {
        $this->table('user')
            ->removeColumn('node_iplimit')
            ->save();
    }
}
