<?php

use Illuminate\Queue\Console\TableCommand as QueueTableCommand;
use Illuminate\Foundation\Application;
use Mockery as m;

class QueueTableCommandTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testCreateMakesMigration()
	{
		$command = new QueueTableCommandTestStub(
			$files = m::mock('Illuminate\Filesystem\Filesystem'),
			$composer = m::mock('Illuminate\Foundation\Composer')
		);
		$creator = m::mock('Illuminate\Database\Migrations\MigrationCreator')->shouldIgnoreMissing();

		$app = new Application();
		$app->useDatabasePath(__DIR__);
		$app['migration.creator'] = $creator;
		$command->setLaravel($app);
		$path = __DIR__ . '/migrations';
		$creator->shouldReceive('create')->once()->with('create_jobs_table', $path)->andReturn($path);
		$files->shouldReceive('get')->once()->andReturn('foo');
		$files->shouldReceive('put')->once()->with($path, 'foo');
		$composer->shouldReceive('dumpAutoloads')->once();

		$this->runCommand($command);
	}


	protected function runCommand($command, $input = array())
	{
		return $command->run(new Symfony\Component\Console\Input\ArrayInput($input), new Symfony\Component\Console\Output\NullOutput);
	}

}

class QueueTableCommandTestStub extends QueueTableCommand {

	public function call($command, array $arguments = array())
	{
		//
	}

}
