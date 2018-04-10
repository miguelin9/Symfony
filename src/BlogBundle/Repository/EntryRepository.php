<?php

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use BlogBundle\Entity\Tag;
use BlogBundle\Entity\EntryTag;
use Doctrine\ORM\Tools\Pagination\Paginator;

class EntryRepository extends EntityRepository
{
    public function saveEntryTags($tags = null, $title = null, $category = null, $user = null, $entry = null)
    {
        $em = $this->getEntityManager();

        $tag_repo = $em->getRepository('BlogBundle:Tag');

        if ($entry == null) {
            $entry = $this->findOneBy(array(
                'title' => $title,
                'category' => $category,
                'user' => $user
            ));
        }
        
        if (substr($tags,-1, strlen($tags)) == ',') {
            $tags = substr($tags,0,-1);
        }
        $tags = explode(',', $tags);

        foreach ($tags as $tag) {
            $isset_tag = $tag_repo->findOneBy(array('name' => trim($tag)));
            if ($isset_tag == null) {
                $tab_obj = new Tag();
                $tab_obj->setName(trim($tag));
                $tab_obj->setDescripcion(trim($tag));
                if ($tab_obj != null) {
                    $em->persist($tab_obj);
                    $em->flush();
                }
            }

            $tag_final = $tag_repo->findOneBy(array('name' => trim($tag)));

            $entryTag_repo = $em->getRepository('BlogBundle:EntryTag');
            $entryTagAux = $entryTag_repo->findOneBy(array('tag' => $tag_final->getId(), 'entry' => $entry));

            if ($entryTagAux == null) {
                $entryTag = new EntryTag();
                $entryTag->setEntry($entry);
                $entryTag->setTag($tag_final);
                $em->persist($entryTag);
            }


        }

        $flush = $em->flush();

        return $flush;
    }

    public function getPaginateEntries($pageSize = 6, $currentPage = 1)
    {
        $em = $this->getEntityManager();

        $dql = "SELECT e FROM BlogBundle\Entity\Entry e ORDER BY e.id DESC";

        $query = $em->createQuery($dql)
            ->setFirstResult($pageSize * ($currentPage - 1))
            ->setMaxResults($pageSize);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return $paginator;
    }
}