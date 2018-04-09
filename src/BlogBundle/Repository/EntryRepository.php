<?php

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use BlogBundle\Entity\Tag;
use BlogBundle\Entity\EntryTag;

class EntryRepository extends EntityRepository
{
    public function saveEntryTags($tags = null, $title = null, $category = null, $user = null, $entry = null)
    {
        $em = $this->getManager();

        $tag_repo = $em->getRepository('BlogBundle:Tag');

        if ($entry == null) {
            $entry = $this->findOneBy(array(
                'title' => $title,
                'category' => $category,
                'user' => $user
            ));
        }

        $tags .= ',';
        $tags = explode(',', $tags);

        foreach ($tags as $tag) {
            $isset_tag = $tag_repo->findOneBy(array('name' => $tag));
            if ($isset_tag == null) {
                $tab_obj = new Tag();
                $tab_obj->setName($tag);
                $tab_obj->setDescripcion($tag);
                if (!empty(trim($tag))) {
                    $em->persist($tab_obj);
                    $em->flush();
                }
            }

            $tag = $tag_repo->findOneBy(array('name' => $tag));

            $entryTag = new EntryTag();
            $entryTag->setEntry($entry);
            $entryTag->setTag($tag);
            $em->persist($entryTag);


        }

        $flush = $em->flush();

        return $flush;
    }
}