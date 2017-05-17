<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Tests\Extension\Validator\Type\TypeTestCase;

class VisitTypeTest extends TypeTestCase
{

    public function testVisitType()
    {
        $formData = array(
            'visit_date' => '30-05-2017',
            'ticket_type' => 'journee',
        );

        $form = $this->factory->create(VisitType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}