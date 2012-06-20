<?php

/**
 * OMP_Wordpress_DynamicResize
 *
 * add_image_size() is fine if you only plan on using one theme, and never
 * changing the image sizes within that. Also, it's a waste of disc space to
 * keep unnecessary resized copies in wp-uploads that are no longer neccessary.
 *
 * This class allows for dynamic resizing of images. E.g.
 *  - A theme requests an image in a certain width/height
 *  - This checks for a cached copy of that resized version and if there is,
 *    serves that.
 *  - If there isn't the image is resized and information about it is added
 *
 * @package OMP
 * @subpackage Wordpress
 * @copyright 1997-2012 Alistair Wooldrige
 * @author Alistair Wooldrige <alistair@wooldrige.co.uk>
 * @license Apache License, Version 2.0 {@link http://www.apache.org/licenses/LICENSE-2.0}
 */
class OMP_Wordpress_DynamicResize {
}
