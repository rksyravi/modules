Links within content can take many forms in WYSIWYG, link fields, free text,
entity reference fields and many others. Making sure that links are not broken
in your content can be difficult to achieve. This module provides a block that
displays a link report including all links in the rendered node. It can be set
to display on node view, node edit, and or node preview.

## Setup
1. Enable Node Link Report module.
2. Set permissions for which roles can view the link node report block
/admin/people/permissions#module-node_link_report
3. Place the "Node Link Report" block in whichever region you want it to appear.
/admin/structure/block
4. Configure the settings to meet your needs.
/admin/config/content/node_link_report

## Requirements
  phpDom - PHP must have phpDom installed. 
  https://www.php.net/manual/en/book.dom.php

## How does it work?
It renders the node without a page template and then curls every unique anchor
href in the content. External URLs can be excluded if desired. If broken
internal hrefs are found, it looks to see if they are to an unpublished node.
The report output is cached on node save for 24 hours so that the number of
repeated curl requests is kept to a minimum.

## Caveats
* Some links won't be tested correctly:
  1. Anchor hrefs created directly on the page template will not be processed
     since the standard page template is not used.
  2. The links are tested as an anonymous user. Links to any content that are
     not exposed to anonymous users will be listed as broken.
  3. malito, sms, and tel links are not fully tested. The link checker will not
     call phone numbers or send email.

## Screenshot of Node Link Report block
![Sample screenshot of the node link report block]
(https://www.drupal.org/files/project-images/node-link-report-sample.png "Sample 
of the Node Link Report block")
