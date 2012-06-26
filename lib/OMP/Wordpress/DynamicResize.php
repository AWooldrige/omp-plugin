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

    /**
     * TODO
     *
     * This class needs a large refactory, at the moment it is based almost
     * verbatim on the original. This needs splitting out and unit testing
     */

    /**
     * Dynamically generate a different sized version of an already existing
     * attachment image specified by an attachment ID
     *
     * Note: This is based on the kovshenin image shortcode plugin
     *       https://gist.github.com/1984363
     *
     * @param int $id the attachment id of the image to resize
     * @param mixed $width width to resize to, null instructs that the width
     *        should be chosen to maintain aspect ratio given the height
     * @param mixed $height height to resize to, null instructs that the height
     *        should be chosen to maintain aspect ratio given the width
     * @param array $options array of extra options
     * @access public
     * @return string URL of the resized image
     */
    public static function getResizedImageFromId($id, $width, $height, $options=null) {

        $width = absint($width);
        $height = absint($height);
        $needs_resize = true;

        // Look through the attachment meta data for an image that fits our size.
        $meta = wp_get_attachment_metadata( $attachment_id );
        foreach($meta['sizes'] as $key => $size) {
            if ($size['width'] == $width && $size['height'] == $height) {
                $src = str_replace( basename( $src ), $size['file'], $src );
                $needs_resize = false;
                break;
            }
        }

        // If an image of such size was not found, we can create one.
        if ($needs_resize) {
            $attached_file = get_attached_file( $attachment_id );
            $resized = image_make_intermediate_size(
                $attached_file,
                $width,
                $height,
                true
            );

            if (!is_wp_error($resized)) {

                // Let metadata know about our new size.
                $key = sprintf( 'resized-%dx%d', $width, $height );
                $meta['sizes'][$key] = $resized;

                $src = str_replace(
                    basename( $src ),
                    $resized['file'],
                    $src
                );
                wp_update_attachment_metadata( $attachment_id, $meta );

                // Record in backup sizes so everything's cleaned up when
                // attachment is deleted.
                $backup_sizes = get_post_meta(
                    $attachment_id,
                    '_wp_attachment_backup_sizes',
                    true
                );

                if (! is_array($backup_sizes)) {
                    $backup_sizes = array();
                }

                $backup_sizes[$key] = $resized;
                update_post_meta(
                    $attachment_id,
                    '_wp_attachment_backup_sizes',
                    $backup_sizes
                );
            }
        }

        return esc_url($src);
    }

    /**
     * Dynamically generate a different sized version of an already existing
     * attachment image specified by an attachment URL
     *
     * Note: This is based on the kovshenin image shortcode plugin
     *       https://gist.github.com/1984363
     *
     * @param int $url the attachment url of the image to resize
     * @param mixed $width width to resize to, null instructs that the width
     *        should be chosen to maintain aspect ratio given the height
     * @param mixed $height height to resize to, null instructs that the height
     *        should be chosen to maintain aspect ratio given the width
     * @param array $options array of extra options
     * @access public
     * @return string URL of the resized image
     */
    public static function getResizedImageFromUrl($url, $width, $height, $options=null) {

        $width = absint($width);
        $height = absint($height);

        global $wpdb;

        $upload_dir = wp_upload_dir();
        $base_url = strtolower( $upload_dir['baseurl'] );

        // Let's see if the image belongs to our uploads directory.
        if (substr( $src, 0, strlen( $base_url)) != $base_url) {
            return "Error: external images are not supported.";
        }

        // Look the file up in the database.
        $file = str_replace(trailingslashit($base_url), '', $src );
        $attachment_id = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attachment_metadata' AND meta_value LIKE %s LIMIT 1;",
                '%"' . like_escape( $file ) . '"%'
            )
        );

        // If an attachment record was not found.
        if ( ! $attachment_id ) {
            return "Error: attachment not found.";
        }

        return OMP_Wordpress_DynamicResize::getResizedImageFromId(
            $attachment_id,
            $width,
            $height,
            $options
        );
    }


    /**
     * Is the size of image already resized by WordPress suitable for the
     * dimensions of image desired? If null is provided for either width or
     * height, then this is unbounded (I.e. was resized to maintain aspect
     * ratio).
     *
     * @param mixed $eWidth width in pixels (or null to maintain aspect ratio)
     *        of the expected image
     * @param mixed $eHeight height in pixels (or null to maitain aspect ratio)
     *        of the expected image
     * @static
     * @access public
     * @return boolean true if a suitable match
     */
    public static function isSizeMatch($eWidth, $eHeight, $aWidth, $aHeight) {
    }
}
