# WP-Doc-Builder
Wordpress Drag &amp; Drop Document Builder

#Introduction
I am beginning to build a drag & drop document builder plugin for wordpress. If anyone would like to help in it's construction please feel free to contact me.

##Structure
I am using the wp_post database table as well as 2 custom tables called wp_doc_builder_elements & wp_doc_builder_items.

#####Database tables:

1. **wp_post:** will store the main post type "doc_build".
2. **wp_doc_build_elements:** Stores the available elements to be dragged into the document
3. **wp_doc_build_items:** Stores the elements relating to the main post. (contains the 'post_id' field)

#####Example Image
coming soon.
