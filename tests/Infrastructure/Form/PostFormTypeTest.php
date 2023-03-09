<?php

namespace App\Tests\Infrastructure\Form;

use App\Domain\Entity\Post;
use App\Infrastructure\Form\PostFormType;
use Symfony\Component\Form\Test\TypeTestCase;

class PostFormTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'title' => 'Test post',
            'description' => 'This is a test post',
        ];

        $postToCompare = new Post();
        $form = $this->factory->create(PostFormType::class, $postToCompare);

        $post = new Post();
        $post->setTitle($formData['title']);
        $post->setDescription($formData['description']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($post, $postToCompare);
    }

    public function testFormRendering(): void
    {
        $form = $this->factory->create(PostFormType::class);

        $view = $form->createView();
        $titleView = $view->children['title']->vars;
        $descriptionView = $view->children['description']->vars;

        $this->assertStringContainsString('Enter a title...', $titleView['attr']['placeholder']);
        $this->assertStringContainsString('Enter a description...', $descriptionView['attr']['placeholder']);
        $this->assertStringContainsString('bg-transparent block border-b-2 w-full h-20 text-5xl outline-none', $titleView['attr']['class']);
        $this->assertStringContainsString('bg-transparent block mt-10 border-b-2 w-full h-60 text-5xl outline-none', $descriptionView['attr']['class']);
    }
}
