<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Talk\Events;

abstract class ARoomSyncedEvent extends ARoomEvent {
	public const PROPERTY_LAST_ACTIVITY = 'lastActivity';
}
