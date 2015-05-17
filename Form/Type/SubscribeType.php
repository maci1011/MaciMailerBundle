<?php

namespace Maci\MailerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;

/**
 * Subscribe
 */
class SubscribeType extends AbstractType
{
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Maci\MailerBundle\Entity\Subscriber',
			// 'cascade_validation' => true
		));
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
            ->add('name', null, array('required' => false))
			->add('mail', 'email', array(
	            'constraints' => new Email(array(
	            	'message' => 'Insert your Email'
	            ))
	        ))
			->add('send', 'submit')
		;
	}

	public function getName()
	{
		return 'subscribe';
	}
}
