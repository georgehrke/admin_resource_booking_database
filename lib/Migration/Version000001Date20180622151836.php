<?php
namespace OCA\AdminResourceBookingDatabase\Migration;

use Doctrine\DBAL\Types\Type;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IOutput;

/**
 * Auto-generated migration step: Please modify to your needs!
 */
class Version000001Date20180622151836 extends SimpleMigrationStep {

	/**
	 * @param IOutput $output
	 * @param \Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 * @since 13.0.0
	 */
	public function changeSchema(IOutput $output, \Closure $schemaClosure, array $options) {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		$types = ['resources', 'rooms'];
		foreach($types as $type) {
			if (!$schema->hasTable('admin_resource_booking_db_' . $type)) {
				$table = $schema->createTable('admin_resource_booking_db_' . $type);

				$table->addColumn('id', Type::BIGINT, [
					'autoincrement' => true,
					'notnull' => true,
					'length' => 11,
					'unsigned' => true,
				]);
				$table->addColumn('resource_id', Type::STRING, [
					'notnull' => false,
					'length' => 64,
				]);
				$table->addColumn('email', Type::STRING, [
					'notnull' => false,
					'length' => 255,
				]);
				$table->addColumn('displayname', Type::STRING, [
					'notnull' => false,
					'length' => 255,
				]);
				$table->addColumn('group_restrictions', Type::STRING, [
					'notnull' => false,
					'length' => 4000,
				]);

				$table->setPrimaryKey(['id'], 'admin_resource_booking_db_' . $type . '_id_idx');
				$table->addIndex(['resource_id'], 'admin_resource_booking_db_' . $type . '_backendresource_idx');
				$table->addIndex(['email'], 'admin_resource_booking_db_' . $type . '_email_idx');
				$table->addIndex(['displayname'], 'admin_resource_booking_db_' . $type . '_displayname_idx');
			}
		}

		return $schema;
	}
}
