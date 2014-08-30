<?php

namespace Genj\SocialFeedBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class ArticleAdmin
 *
 * @package Genj\ArticleBundle\Admin
 */
class SocialFeedAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_by' => 'publishAt',
        '_sort_order' => 'desc'
    );

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('authorUsername')
            ->add('provider')
            ->add('body');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('provider')
            ->add('authorUsername')
            ->add('headline', null, array('identifier' => true))
            ->add('isActive')
            ->add('publishAt')
            ->add('_action', 'actions',
                array(
                    'actions' => array(
                        'edit' => array(),
                        'delete' => array()
                    )
                )
            );
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Basics', array('position' => 'left'))
            ->add('headline', null, array('attr' => array('class' => 'span12')))
            ->end()

            ->with('Status', array('position' => 'right'))
            ->add('isActive', 'choice', array(
                    'choices' => array(0 => 'draft', 1 => 'published'),
                    'expanded' => true,
                    'required' => true,
                    'label' => 'Status',
                    'attr' => array('class' => 'radio-list')
                )
            )
            ->add('publishAt')
            ->end();
    }

    /**
     * @return array
     */
    protected function setupTemplateList()
    {
        $options = array(
            'standard' => 'Standard'
        );

        return $options;
    }
}
