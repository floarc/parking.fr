<?php
namespace Floarc\ParkingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('cherche', 'choice', array(
  		  'choices'     => array('1' => 'Parking', '2' => 'Locataire'),
    	  'required'    => false,
		  'expanded'    => true,
		  'empty_value' => false,
		  'label'  		=> "Je cherche"
		))
		->add('address', 'text')
		->add('locality', 'hidden')
		->add('district', 'hidden')
		->add('state', 'hidden')
		->add('country', 'hidden')
		->add('postal_code', 'hidden')
		->add('lat', 'hidden')
		->add('lng', 'hidden')
		->add('zoom', 'hidden')
		->add('type', 'hidden');
	}

	/*
	public function getDefaultOptions(array $options)
	{
		return array(
				'data_class' => 'Floarc\ParkingBundle\Entity\Parking',
		);
	}
	*/
	
	/*
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Floarc\ParkingBundle\Entity\Parking',
		));
	}
	*/	
	
	public function getName()
	{
		return '';
	}
	
}