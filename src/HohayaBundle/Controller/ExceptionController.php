<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HohayaBundle\Controller;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController as BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExceptionController extends Controller
{
    public function showExceptionAction()
    {
        //return $this->redirectToRoute('mica_homepage', array('id' => 1, 'cle' => 'm'));
        return $this->render('@Twig/Exception/error404.html.twig');
    }
}
