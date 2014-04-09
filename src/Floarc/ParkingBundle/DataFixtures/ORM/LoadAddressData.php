<?php
namespace Floarc\ParkingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Floarc\ParkingBundle\Entity\Address;
use Lsw\ApiCallerBundle\Call\HttpGetJson;
use Lsw\ApiCallerBundle\Call\HttpGetHtml;
use Lsw\ApiCallerBundle\Call\CurlCall;
use Symfony\Component\DomCrawler\Crawler;


class LoadAddressData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
	
	/**
	 * @var ContainerInterface
	 */
	private $container;
	
	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
		
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$conn=$manager->getConnection();
		$file=$this->container->get('kernel')->getRootDir().'/../geoaddress.sql';
		if(!file_exists($file)) {
			echo sprintf('File %s does not exists', $file);
			return;
		}
		echo sprintf('File %s exists', $file);
		$data = file_get_contents($file);
		
// 		echo "<pre>";
// 		print_r($data);
// 		echo "</pre>";
		
		
		$conn->executeUpdate($data);
	}
	
	
	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 10; // the order in which fixtures will be loaded
	}	
}