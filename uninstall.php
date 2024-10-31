<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('sbtp_common');
delete_option('sbtp_posts');
delete_option('sbtp_pages');