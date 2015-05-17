<?php

namespace Maci\MailerBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Common\Persistence\ObjectManager;

use Maci\TranslatorBundle\Controller\TranslatorController;

class MailerMenuBuilder
{
	private $factory;

	private $translator;

	private $locales;

	public function __construct(FactoryInterface $factory, TranslatorController $tc)
	{
	    $this->factory = $factory;
	    $this->translator = $tc;
	    $this->locales = $tc->getLocales();
	}

    public function createLeftMenu(Request $request)
	{
		$menu = $this->factory->createItem('root');

		$menu->setChildrenAttribute('class', 'nav');

		$menu->addChild($this->translator->getText('menu.home', 'Home'), array('route' => 'maci_homepage'));

		$menu->addChild($this->translator->getText('menu.admin.mailer', 'Mailer Home'), array('route' => 'maci_mailer'));

		$menu->addChild($this->translator->getText('menu.admin.mailer.add_subscribers', 'Subscribers List'), array('route' => 'maci_mailer_add_subscribers'));

		$menu->addChild($this->translator->getText('menu.admin.mailer.subscribers', 'Subscribers List'), array('route' => 'maci_mailer_subscribers'));

		return $menu;
	}
}
