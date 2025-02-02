<?php

namespace Page\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use TheliaBlocks\Model\BlockGroup;
use TheliaBlocks\Model\BlockGroupQuery;

class EditPageForm extends PageForm
{
    /**
     * @return null
     */
    protected function buildForm()
    {
        parent::buildForm();

        $this->formBuilder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => $this->translator->trans('Page title', [], 'page.bo.default'),
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => $this->translator->trans('Description', [], 'page.bo.default')
                ]
            )
            ->add(
                'chapo',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => $this->translator->trans('Summary', [], 'page.bo.default')
                ]
            )
            ->add(
                'postscriptum',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => $this->translator->trans('Conclusion', [], 'page.bo.default')
                ]
            )
            ->add(
                'type',
                ChoiceType::class,
                [
                    "choices" => $this->getPageTypes(),
                    'required' => false,
                    'label' => $this->translator->trans('Page type', [], 'page.bo.default'),
                    'placeholder' => $this->translator->trans('Choose a page type', [], 'page.bo.default')
                ]
            )->add(
                'tag',
                TextType::class,
                [
                    'label' => $this->translator->trans('Page tag', [], 'page.bo.default'),
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->translator->trans('This page tag', [], 'page.bo.default')
                    ]
                ]
            )
        ;

        $this->formBuilder->remove("thelia-block");

        return null;
    }

    /**
     * @return array
     */
    protected function getTheliaBlocs(): array
    {
        $locale = $this->getRequest()->getSession()->getAdminEditionLang()->getLocale();
        $choices = [];

        //TODO: gestion des placeorlder est cassée
        $choices[$this->translator->trans('Choose a block', [], 'page.bo.default')] = null;

        $blocks = BlockGroupQuery::create()
            ->find();

        /** @var BlockGroup $block */
        foreach ($blocks as $block) {
            $block->setLocale($locale);
            $choices[$block->getTitle()] = $block->getId();
        }

        return $choices;
    }
}
