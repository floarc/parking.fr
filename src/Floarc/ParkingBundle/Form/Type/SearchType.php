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
		->add('type_annonce', 'choice', array(
  		  'choices'     => array('offre' => 'trouver un parking', '2' => 'proposer un parking'),
    	  'required'    => false,
		  'expanded'    => true,
		  'empty_value' => false,
		  'label'  		=> "Je souhaite"
		))
		
		
		->add('type_contrat', 'choice', array(
				'choices'     => array('vente' => 'à la vente', 'location' => 'en location' , 'colocation' => 'en colocation'),
				'required'    => false,
				'expanded'    => true,
				'empty_value' => false,
				//'label'  		=> "Je souhaite"
				'label'  		=> false
		))		
		->add('address', 'text', array(
    	  'required'    => false,
		))
		->add('submit', 'submit')
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