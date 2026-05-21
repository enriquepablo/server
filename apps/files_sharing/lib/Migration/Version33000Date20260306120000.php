<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Files_Sharing\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\DB\Types;
use OCP\Migration\Attributes\AddColumn;
use OCP\Migration\Attributes\ColumnType;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;
use Override;

#[AddColumn(table: 'share_external', name: 'access_token', type: ColumnType::STRING, description: 'Stores the short-lived OCM Bearer access token separately from the password field')]
#[AddColumn(table: 'share_external', name: 'access_token_expires', type: ColumnType::INTEGER, description: 'Unix timestamp when the stored OCM Bearer access token expires')]
class Version33000Date20260306120000 extends SimpleMigrationStep {
	#[Override]
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();
		$table = $schema->getTable('share_external');

		$changed = false;

		if (!$table->hasColumn('access_token')) {
			$table->addColumn('access_token', Types::STRING, [
				'notnull' => false,
				'default' => null,
				'length' => 512,
			]);
			$changed = true;
		}

		if (!$table->hasColumn('access_token_expires')) {
			$table->addColumn('access_token_expires', Types::INTEGER, [
				'notnull' => false,
				'default' => null,
			]);
			$changed = true;
		}

		return $changed ? $schema : null;
	}
}
