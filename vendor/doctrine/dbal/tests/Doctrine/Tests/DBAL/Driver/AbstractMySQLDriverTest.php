<?php

namespace Doctrine\Tests\DBAL\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MariaDb1027Platform;
use Doctrine\DBAL\Platforms\MySQL57Platform;
use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\MySqlSchemaManager;

class AbstractMySQLDriverTest extends AbstractDriverTest
{
    public function testReturnsDatabaseName(): void
    {
        parent::testReturnsDatabaseName();

        $database = 'bloo';
        $params   = [
            'user'     => 'foo',
            'password' => 'bar',
        ];

        $statement = $this->createMock(ResultStatement::class);

        $statement->expects($this->once())
            ->method('fetchColumn')
            ->willReturn($database);

        $connection = $this->getConnectionMock();

        $connection->expects($this->once())
            ->method('getParams')
            ->willReturn($params);

        $connection->expects($this->once())
            ->method('query')
            ->willReturn($statement);

        self::assertSame($database, $this->driver->getDatabase($connection));
    }

    protected function createDriver(): Driver
    {
        return $this->getMockForAbstractClass(AbstractMySQLDriver::class);
    }

    protected function createPlatform(): AbstractPlatform
    {
        return new MySqlPlatform();
    }

    protected function createSchemaManager(Connection $connection): AbstractSchemaManager
    {
        return new MySqlSchemaManager($connection);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDatabasePlatformsForVersions(): array
    {
        return [
            ['5.6.9', MySqlPlatform::class],
            ['5.7', MySQL57Platform::class],
            ['5.7.0', MySqlPlatform::class],
            ['5.7.8', MySqlPlatform::class],
            ['5.7.9', MySQL57Platform::class],
            ['5.7.10', MySQL57Platform::class],
            ['8', MySQL80Platform::class],
            ['8.0', MySQL80Platform::class],
            ['8.0.11', MySQL80Platform::class],
            ['6', MySQL57Platform::class],
            ['10.0.15-MariaDB-1~wheezy', MySqlPlatform::class],
            ['5.5.5-10.1.25-MariaDB', MySqlPlatform::class],
            ['10.1.2a-MariaDB-a1~lenny-log', MySqlPlatform::class],
            ['5.5.40-MariaDB-1~wheezy', MySqlPlatform::class],
            ['5.5.5-MariaDB-10.2.8+maria~xenial-log', MariaDb1027Platform::class],
            ['10.2.8-MariaDB-10.2.8+maria~xenial-log', MariaDb1027Platform::class],
            ['10.2.8-MariaDB-1~lenny-log', MariaDb1027Platform::class],
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getExceptionConversionData(): array
    {
        return [
            self::EXCEPTION_CONNECTION => [
                ['1044', null, ''],
                ['1045', null, ''],
                ['1046', null, ''],
                ['1049', null, ''],
                ['1095', null, ''],
                ['1142', null, ''],
                ['1143', null, ''],
                ['1227', null, ''],
                ['1370', null, ''],
                ['2002', null, ''],
                ['2005', null, ''],
            ],
            self::EXCEPTION_FOREIGN_KEY_CONSTRAINT_VIOLATION => [
                ['1216', null, ''],
                ['1217', null, ''],
                ['1451', null, ''],
                ['1452', null, ''],
            ],
            self::EXCEPTION_INVALID_FIELD_NAME => [
                ['1054', null, ''],
                ['1166', null, ''],
                ['1611', null, ''],
            ],
            self::EXCEPTION_NON_UNIQUE_FIELD_NAME => [
                ['1052', null, ''],
                ['1060', null, ''],
                ['1110', null, ''],
            ],
            self::EXCEPTION_NOT_NULL_CONSTRAINT_VIOLATION => [
                ['1048', null, ''],
                ['1121', null, ''],
                ['1138', null, ''],
                ['1171', null, ''],
                ['1252', null, ''],
                ['1263', null, ''],
                ['1364', null, ''],
                ['1566', null, ''],
            ],
            self::EXCEPTION_SYNTAX_ERROR => [
                ['1064', null, ''],
                ['1149', null, ''],
                ['1287', null, ''],
                ['1341', null, ''],
                ['1342', null, ''],
                ['1343', null, ''],
                ['1344', null, ''],
                ['1382', null, ''],
                ['1479', null, ''],
                ['1541', null, ''],
                ['1554', null, ''],
                ['1626', null, ''],
            ],
            self::EXCEPTION_TABLE_EXISTS => [
                ['1050', null, ''],
            ],
            self::EXCEPTION_TABLE_NOT_FOUND => [
                ['1051', null, ''],
                ['1146', null, ''],
            ],
            self::EXCEPTION_UNIQUE_CONSTRAINT_VIOLATION => [
                ['1062', null, ''],
                ['1557', null, ''],
                ['1569', null, ''],
                ['1586', null, ''],
            ],
            self::EXCEPTION_DEADLOCK => [
                ['1213', null, ''],
            ],
            self::EXCEPTION_LOCK_WAIT_TIMEOUT => [
                ['1205', null, ''],
            ],
        ];
    }
}
