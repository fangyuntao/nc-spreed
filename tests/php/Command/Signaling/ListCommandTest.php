<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\Talk\Tests\php\Command\Signaling;

use OCA\Talk\Command\Signaling\ListCommand;
use OCP\IConfig;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Test\TestCase;

class ListCommandTest extends TestCase {
	protected IConfig&MockObject $config;
	protected InputInterface&MockObject $input;
	protected OutputInterface&MockObject $output;
	protected ListCommand&MockObject $command;

	public function setUp(): void {
		parent::setUp();

		$this->config = $this->createMock(IConfig::class);

		$this->command = $this->getMockBuilder(ListCommand::class)
			->setConstructorArgs([$this->config])
			->onlyMethods(['writeMixedInOutputFormat'])
			->getMock();

		$this->input = $this->createMock(InputInterface::class);
		$this->output = $this->createMock(OutputInterface::class);
	}

	public function testEmptyAppConfig(): void {
		$this->config->expects($this->once())
			->method('getAppValue')
			->with('spreed', 'signaling_servers')
			->willReturn(json_encode([]));

		$this->command->expects($this->once())
			->method('writeMixedInOutputFormat')
			->with(
				$this->equalTo($this->input),
				$this->equalTo($this->output),
				$this->equalTo([])
			);

		self::invokePrivate($this->command, 'execute', [$this->input, $this->output]);
	}

	public function testAppConfigDataChanges(): void {
		$this->config->expects($this->once())
			->method('getAppValue')
			->with('spreed', 'signaling_servers')
			->willReturn(json_encode([
				'servers' => [
					[
						'server' => 'wss://signaling.example.com',
						'verify' => true
					]
				],
				'secret' => 'my-test-secret'
			]));

		$this->command->expects($this->once())
			->method('writeMixedInOutputFormat')
			->with(
				$this->equalTo($this->input),
				$this->equalTo($this->output),
				$this->equalTo([
					'servers' => [
						[
							'server' => 'wss://signaling.example.com',
							'verify' => true
						]
					],
					'secret' => 'my-test-secret'
				])
			);

		self::invokePrivate($this->command, 'execute', [$this->input, $this->output]);
	}
}
