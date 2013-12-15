<?php

namespace Floarc\ParkingBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Finder\Finder;

/**
 * Purge de la base de données
 * Purge/Vide les données de la base de données
 * La base étant géré par le moteur InnoDB qui prend en compte les relations entre les table, Doctrine est dans l'incapacité à effectuer cette tâche
 *
 *
 * @uses $ php app/console doctrine:fixtures:load --append
 */

class LoadTruncateTable //extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{

	private $tableToTruncateList = array();
	private $connection;

	//A voir pour être utilisé dans le CronBundle
	public function load(ObjectManager $manager)
	{
		die("ssdsddsf");
		$this->connection = $manager->getConnection();

		$this->connection->executeUpdate("SET FOREIGN_KEY_CHECKS=0;");

		/* @var $classMetadata \Doctrine\ORM\Mapping\ClassMetadata */
		foreach ($manager->getMetadataFactory()->getAllMetadata() as $classMetadata) {

			$this->truncateTable($classMetadata->getTableName());

			foreach ($classMetadata->getAssociationMappings() as $field) {
				if (isset($field['joinTable']) && isset($field['joinTable']['name'])) {
					$this->truncateTable($field['joinTable']['name']);
				}
			}
		}

		$this->connection->executeUpdate("SET FOREIGN_KEY_CHECKS=1;");
	}

	private function truncateTable($tableName)
	{
		if (!in_array($tableName, $this->tableToTruncateList)) {
			$this->connection->executeUpdate($this->connection->getDatabasePlatform()->getTruncateTableSQL($tableName));
			$this->tableToTruncateList[] = $tableName;
		}
	}

	/**
	 * Définit dans quel ordre les fixtures doivent être lancé
	 */
	public function getOrder()
	{
		return 1; // the order in which fixtures will be loaded
	}

}