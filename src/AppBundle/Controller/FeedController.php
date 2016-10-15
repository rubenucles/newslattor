<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Feed;
use AppBundle\Form\FeedType;

/**
 * Feed controller.
 *
 */
class FeedController extends Controller
{

    /**
     * Lists all Feed entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Feed')->findAll();

        return $this->render('AppBundle:Feed:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Feed entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Feed();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('feed_show', array('id' => $entity->getId())));
        }

        return $this->render('AppBundle:Feed:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Feed entity.
     *
     * @param Feed $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Feed $entity)
    {
        $form = $this->createForm(new FeedType(), $entity, array(
            'action' => $this->generateUrl('feed_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Feed entity.
     *
     */
    public function newAction()
    {
        $entity = new Feed();
        $form   = $this->createCreateForm($entity);

        return $this->render('AppBundle:Feed:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Feed entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Feed')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feed entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Feed:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Feed entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Feed')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feed entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Feed:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Feed entity.
    *
    * @param Feed $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Feed $entity)
    {
        $form = $this->createForm(new FeedType(), $entity, array(
            'action' => $this->generateUrl('feed_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Feed entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Feed')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feed entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('feed_edit', array('id' => $id)));
        }

        return $this->render('AppBundle:Feed:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Feed entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Feed')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Feed entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('feed'));
    }

    /**
     * Scrapper Start
     *
     */
    public function scrapperAction()
    {

      $url_newspaper = array(
       'elpais'=>'http://ep00.epimg.net/rss/elpais/portada.xml',
       'elmundo'=>'http://estaticos.elmundo.es/elmundo/rss/portada.xml',
       'elconfidencial'=>'http://rss.elconfidencial.com/espana/',
       'larazon'=>'http://www.larazon.es/rss/portada.xml',
       'elperiodico' => 'http://www.elperiodico.com/es/rss/rss_portada.xml'
      );


      foreach($url_newspaper as $k=>$v){
        $scrapper = $this->get('app.scrapper');
        $scrapper->start($k,$v);
      }

      return $this->redirect($this->generateUrl('feed'));
    }

    /**
     * Creates a form to delete a Feed entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('feed_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
