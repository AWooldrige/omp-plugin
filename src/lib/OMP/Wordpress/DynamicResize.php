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
 *  - If there isn't the image is resized and cached
 *
 * @package OMP
 * @subpackage Wordpress
 * @copyright 1997-2012 Alistair Wooldrige
 * @author Alistair Wooldrige <alistair@wooldrige.co.uk>
 * @license Apache License, Version 2.0 {@link http://www.apache.org/licenses/LICENSE-2.0}
 */
class OMP_Wordpress_DynamicResize {

    /**
     * From the supplied path to a fullsize image, return the path to the
     * cached image for the width and heigh provided. This can be a filepath
     * or URL.
     *
     * @param string $path absoute or relative path to fullsize image
     * @param int $width width of the resized image
     * @param int $height height of the resized image
     * @static
     * @access public
     * @return string path to cached image for the size provided
     */
    public static function resizeCachePath($path, $width, $height) {
        $dirSplit = explode('/', $path);

        $extSplit = explode('.', $dirSplit[count($dirSplit)-1]);
        $extSplit[0] .= '-' . $width . 'x' . $height;

        $dirSplit[count($dirSplit)-1] = implode('.', $extSplit);

        return implode('/', $dirSplit);
    }

    /**
     * Dynamically generate a different sized version of an already existing
     * attachment image specified by an attachment ID
     *
     * Note: This is based on the kovshenin image shortcode plugin
     *       https://gist.github.com/1984363
     *
     * @param int $id the attachment id of the image to resize
     * @param (int|null) $width width to resize to, null instructs that the
     *        width should be chosen to maintain aspect ratio given the height
     * @param (int|null) $height height to resize to, null instructs that the
     *        height should be chosen to maintain aspect ratio given the width
     * @param array $options array of extra options
     * @access public
     * @return string URL of the resized image
     */
    public static function getResizedImageFromId($id, $width, $height, $options=null) {

        //Get the path and dimensions of the full size image
        $d = wp_get_attachment_image_src($id, 'full');
        $fullPath = get_attached_file($id);
        $fullUrl = $d[0];
        $fullWidth = $d[1];
        $fullHeight = $d[2];

        //Calculate the dimensions of the resized image
        $rDim = self::calculateSize(
            $width, $height, $fullWidth, $fullHeight
        );
        $rPath = self::resizeCachePath($fullPath, $rDim['width'], $rDim['height']);
        $rUrl = self::resizeCachePath($fullUrl, $rDim['width'], $rDim['height']);

        //Do we need to generate the resized image first?
        if (False === file_exists($rPath)) {
            /**
             * image_make_intermediate_size will resize or crop, we therefore
             * have to provide provide an exact width and height, and whether
             * to crop or resize.
             */
            //Do that actual resizing
            if (False === image_make_intermediate_size(
                $fullPath,
                $rDim['width'],
                $rDim['height'],
                $rDim['crop']
            )) {
                throw new Exception(
                    'Could not resize image using image_make_intermediate_size'
                );
            }
        }

        return array(
            'url' => $rUrl,
            'width' => $rDim['width'],
            'height' => $rDim['height']
        );
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
     * @param (int|null) $eWidth width in pixels (or null to maintain aspect
     *        ratio) of the expected image
     * @param (int|null) $eHeight height in pixels (or null to maitain aspect
     *        ratio) of the expected image
     * @param int $width width in pixels of the actual image
     * @param int $height height in pixels of the actual image
     *
     * @static
     * @access public
     * @return boolean true if a suitable match
     */
    public static function isSizeMatch($eWidth, $eHeight, $width, $height) {
        //Can't have both null
        if((true === is_null($eWidth)) and (true === is_null($eHeight))) {
            throw new InvalidArgumentException(
                'Both the expected width and height can\'t be null'
            );
        }

        //If we don't care about the width, just check that the height matches
        if(true === is_null($eWidth)) {
            return ($eHeight === $height);
        }

        //If we don't care about the height, just check that the width matches
        if(true === is_null($eHeight)) {
            return ($eWidth === $width);
        }

        //If we've reached here, must care about an exact match
        return (($eWidth === $width) and ($eHeight === $height));
    }


    /**
     * Calculate both the dimensions of the new image to resize to, and whether
     * this needs cropping or resizing (workaround to make
     * image_get_intermediate size do what we want)
     *
     * @param (int|null) $eWidth width in pixels (or null to maintain aspect
     *        ratio) of the expected image
     * @param (int|null) $eHeight height in pixels (or null to maitain aspect
     *        ratio) of the expected image
     * @param int $width width in pixels of the actual image
     * @param int $height height in pixels of the actual image
     *
     * @static
     * @access public
     * @return array new image dimensions. Array keys being: width, height
     *         and crop (whether to crop or resize, true to resize)
     */
    public static function calculateSize($eWidth, $eHeight, $width, $height) {
        //Can't have both null
        if((true === is_null($eWidth)) and (true === is_null($eHeight))) {
            throw new InvalidArgumentException(
                'Both the expected width and height can\'t be null'
            );
        }

        //If we don't care about the width, scale width to match the height
        if(is_null($eWidth)) {
            return array(
                'width' => (int) ($width * ($eHeight / $height)),
                'height' => $eHeight,
                'crop' => false
            );
        }

        //If we don't care about the height, just check that the width matches
        if(true === is_null($eHeight)) {
            return array(
                'width' => $eWidth,
                'height' => (int) ($height * ($eWidth / $width)),
                'crop' => false
            );
        }

        return array(
            'width' => $eWidth,
            'height' => $eHeight,
            'crop' => true
        );
    }
}
