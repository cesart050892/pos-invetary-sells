<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
	protected $name = "User";
	public function up()
	{
		$this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'constraint'     => 5,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'name'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
			],
			'username'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
			],
			'password'       => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
			],
			'profile'=> [
				'type'       => 'VARCHAR',
				'constraint' => '255',
			],
			'status'=> [
				'type'       => 'VARCHAR',
				'constraint' => '255',
			],
			'photo'=> [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null' => true
			],
			'last_login'=> [
				'type'       => 'TIMESTAMP',
				'null'		=> true
			],
		]);
		$this->forge->addKey('id', true);
		$this->forge->addField("created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
		$this->forge->addField("updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
		$this->forge->addField("deleted_at TIMESTAMP NULL DEFAULT NULL");
		$this->forge->createTable($this->name);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable($this->name);
	}
}
