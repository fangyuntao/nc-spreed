<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Talk\Events;

use OCA\Talk\Participant;
use OCA\Talk\Room;

abstract class ACallEndedForEveryoneEvent extends ARoomModifiedEvent {
	public function __construct(
		Room $room,
		?Participant $actor = null,
	) {
		parent::__construct(
			$room,
			self::PROPERTY_IN_CALL,
			Participant::FLAG_DISCONNECTED,
			null,
			$actor
		);
	}
}
