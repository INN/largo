inc/term-meta.php
=================

.. php:function:: largo_get_term_meta_post()

   Get the proxy post for a term

   :param string $taxnomy:
   :param int $term_id:

   :returns: int $post_id

.. php:function:: largo_add_term_meta()

   Add meta data to a term

   :param string $taxonomy:
   :param int $term_id:
   :param string $meta_key:
   :param mixed $meta_value:
   :param bool $unique:

.. php:function:: largo_delete_term_meta()

   Delete meta data to a term

   :param string $taxonomy:
   :param int $term_id:
   :param string $meta_key:
   :param mixed $meta_value:

.. php:function:: largo_get_term_meta()

   Get meta data to a term

   :param string $taxonomy:
   :param int $term_id:
   :param string $meta_key:
   :param bool $single:

.. php:function:: largo_update_term_meta()

   Update meta data to a term

   :param string $taxonomy:
   :param int $term_id:
   :param string $meta_key:
   :param mixed $meta_value:
   :param mixed $prev_value: