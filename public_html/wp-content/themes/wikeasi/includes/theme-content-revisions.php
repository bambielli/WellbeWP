<?php
/**
 * WooThemes Content Revisions Manager.
 *
 * Add a list of revisions to each entry, as well as the abillity to view
 * a selected revision and a diff between the revision and the current published version.
 *
 * @category Modules
 * @package WordPress
 * @subpackage WooFramework
 * @author Matty at WooThemes
 * @since 1.0.0
 * @inspiration Post Revisions Display by Scott Carpenter and D'Arcy Norman ( http://www.movingtofreedom.org/10/07/30/wordpress-plugin-post-revision-and-diff-viewer )
 *
 * TABLE OF CONTENTS
 *
 * - Constructor()
 * - init()
 * - add_section_heading()
 * - add_revisions_heading()
 * - get_diffs()
 * - break_up_lines()
 * - revisions_list()
 * - revisions_note()
 * - display_revision_notice()
 * - add_noindex_header()
 * - filter_content()
 * - filter_title()
 */
class WooThemes_Content_Revisions {
	/**
	 * WooThemes_Content_Revisions function.
	 *
	 * @access public
	 * @return void
	 */
	function WooThemes_Content_Revisions () {} // End Constructor

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	function init () {
		add_action( 'wp_head', array( &$this, 'add_noindex_header' ), 10 );
		add_filter( 'the_content', array( &$this, 'filter_content' ), 10 );
		add_filter( 'the_title', array( &$this, 'filter_title' ), 10 );
		add_action( 'the_post', array( &$this, 'display_revision_notice' ), 10 );
	} // End init()

	/**
	 * add_section_heading function.
	 *
	 * @access public
	 * @param string $header
	 * @param string $text
	 * @return string $text
	 */
	function add_section_heading ( $header, $text ) {
		$pattern = '/(<tbody[^>]*>)/i';
		$text = preg_replace( $pattern, "$1\n" . '<tr><th colspan="4">' . $header . '</th></tr>', $text );
		return $text;
	} // End add_section_heading()

	/**
	 * add_revisions_heading function.
	 *
	 * @access public
	 * @param string $old_rev
	 * @param string $new_rev
	 * @param string $text
	 * @return string $text
	 */
	function add_revisions_heading ( $old_rev, $new_rev, $text ) {
		$pattern = '/(<tbody[^>]*>)/i';
		$text = preg_replace( $pattern, "$1\n" . '<tr><th class="diff-deletedline" colspan="2">' .
			$old_rev . '</th><th class="diff-addedline" colspan="2">' .
			$new_rev . '</th></tr>', $text, 1 );
		return $text;
	} // End add_revisions_heading()

	/**
	 * get_diffs function.
	 *
	 * @access public
	 * @param object $post
	 * @param object $revision
	 * @return string
	 */
	function get_diffs ( $post, $revision ) {
		$previous = $this->break_up_lines( $revision->post_content );
		$current = $this->break_up_lines( $post->post_content );

		// diff the unfiltered content
		$diffs = wp_text_diff( $previous, $current );
		$diffs_title = wp_text_diff( $revision->post_title, $post->post_title );

		// add header/footer rows for "parts"
		$diffs = $this->add_section_heading( __( 'Content', 'woothemes' ), $diffs );
		$diffs_title = $this->add_section_heading( __( 'Title', 'woothemes' ), $diffs_title );

		$diffs = $diffs_title . $diffs;
		if ( empty( $diffs ) ) {
			$diffs = '<p>' . sprintf( __( 'There are no differences between the %s revision and the current revision. (Maybe only post meta information was changed.)', 'woothemes' ),  wp_post_revision_title( $revision, false ) ) . '</p>';
		} else {
			$diffs = $this->add_revisions_heading( wp_post_revision_title( $revision, false ), __( 'Current Revision', 'woothemes' ), $diffs ) .
				"\n<p>" . sprintf( __( '%1$sNote:%2$s Spaces may be added to comparison text to allow better line wrapping.', 'woothemes' ), '<i>', '</i>' ) . "</p>\n";
		}
		
		return apply_filters( 'revisions_diffs', '<div id="revision-diffs" class="revision-diffs">' . '<h3>' . __( 'Revision Differences', 'woothemes' ) . '</h3>' . "\n" . $diffs . "\n" . '</div>' . "\n" );
	} // End get_diffs()

	/**
	 * break_up_lines function.
	 *
	 * @access public
	 * @param string $text
	 * @return string $text
	 */
	function break_up_lines ( $text ) {
		$pattern = '/([^\s]{12}[-+_<>\/=";:()])([^\s]{12})/';
		do {
			$text = preg_replace( $pattern, "$1 $2", $text, -1, $num_replaced );
		} while ( $num_replaced > 0 );

		return $text;
	} // End break_up_lines()

	/**
	 * revisions_list function.
	 *
	 * @access public
	 * @param post $post
	 * @param array $args (default: null)
	 * @return string
	 */
	function revisions_list ( $post, $args = null ) {
		$defaults = array( 'parent' => false, 'right' => false, 'left' => false, 'format' => 'list', 'type' => 'all', 'since_publish' => false, 'rev_id' => 0, 'is_rev' => false );
		extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

		$rev_list = '';
		switch ( $type ) {
		case 'autosave' :
			if ( !$autosave = wp_get_post_autosave( $post->ID ) ) {
				$rev_list = '<p>' . __( 'No autosave', 'woothemes' ) . '</p>';
			}
			$revisions = array( $autosave );
			break;
		case 'revision' : // just revisions - remove autosave later
		case 'all' :
		default :
			if ( !$revisions = wp_get_post_revisions( $post->ID ) ) {
				$rev_list = '<p>' . __( 'There are no revisions for this post.', 'woothemes' ) . '</p>';
			}
			break;
		}

		$titlef = _x( '%1$s by %2$s', 'post revision 1:datetime, 2:name', 'woothemes' );

		if ( $parent ) {
			array_unshift( $revisions, $post );
		}

		$rows = '';
		$class = false;
		if ( $since_publish ) {
			$post_time = strtotime( $post->post_date_gmt );
		}
		foreach ( $revisions as $revision ) {
			if ( 'revision' === $type && wp_is_post_autosave( $revision ) ) {
				continue;
			}
			if ( $since_publish && strtotime( $revision->post_date_gmt ) < $post_time ) {
				continue;
			}
			// 2nd param to wp_post_revision_title determines if link (if true, only links if you have access)
			$date = wp_post_revision_title( $revision, false );
			if ( $rev_id != $revision->ID ) {
				$plink = get_permalink();
				if ( strpos( $plink, '?' ) === false ) {
					$sep = '?';
				} else {
					$sep = '&';
				}
				$date = '<a href="' . $plink . $sep . 'rev=' . $revision->ID . '">' . "$date</a>";
			}
			$name = get_the_author_meta( 'display_name', $revision->post_author );
			$title = sprintf( $titlef, $date, $name );

			$rows .= "\t" . '<li>' . $title . '</li>' . "\n";
		}

		if ( !empty( $rows ) ) {
			// add current revision to the top
			$date = wp_post_revision_title( $post, false );
			if ( $is_rev ) {
				$date = '<a href="' . get_permalink() . '">' . $date . '</a>';
			}
			$name = get_the_author_meta( 'display_name', $post->post_author );
			$title = sprintf( $titlef, $date, $name );
			$rows = "\t<li>$title</li>\n$rows";

			$rev_list = '<ul class="post-revisions">' .
				"\n$rows\n</ul>";
		} else if ( empty( $rev_list ) ) {
				$rev_list = '<p>' . __( 'This post has not been revised since publication.', 'woothemes' ) . '</p>';
			}
		return apply_filters( 'revisions_list', '<div class="content-revisions">' . '<h3>' . __( 'Revisions', 'woothemes' ) . '</h3>' . "\n" . $rev_list . "\n" . '</div>' . "\n" );
	} // End revisions_list()

	/**
	 * revisions_note function.
	 *
	 * @access public
	 * @param object $post
	 * @param object &$revision
	 * @param int &$rev_id
	 * @param boolean &$is_rev
	 * @return string $note
	 */
	function revisions_note ( $post, &$revision, &$rev_id, &$is_rev ) {
		$is_rev = false;
		$note = '';
		
		if ( isset( $_GET['rev'] ) ) {
			$rev_id = intval( $_GET['rev'] );
			$current_rev_link = '<a href="' . get_permalink() . '">' . __( 'current revision', 'woothemes' ) . '</a>';
			$view_current = sprintf( __( '(Viewing %s instead.)', 'woothemes' ), $current_rev_link );
			if ( ! $revision = get_post( $rev_id ) ) {
				$note = sprintf( __( 'Revision %1$s not found. %2$s', 'woothemes' ), $rev_id, $view_current );
			} else {
				$post_id = $post->ID;
				if ( $revision->post_parent == 0 ) {
					$note = __( 'This is the current revision.' , 'woothemes' );
				} else if ( $revision->post_parent != $post->ID ) { // this check should come before the date check
						$note = sprintf( __( 'Revision %1$s is not a revision of this post. %2$s', 'woothemes' ), $rev_id, $view_current );
					} else if ( strtotime( $revision->post_date_gmt ) < strtotime( $post->post_date_gmt ) ) {
						$note = sprintf( __( 'Revision %1$s is a pre-publication revision. %2$s', 'woothemes' ), $rev_id, $view_current );
					} else {
					$is_rev = true;
					$note = sprintf( __( 'You are viewing an old revision of this post, from %s.', 'woothemes' ), wp_post_revision_title( $revision, false ) ) . ' ' . sprintf( __( '%1$sSee below for differences%2$s between this version and the %3$s', 'woothemes' ), '<a href="#revision-diffs">', '</a>', $current_rev_link ) . '.';
				}
			}
			$note = '<div class="revision-header woo-sc-box note"><p>' . $note . "</p></div>\n";
		}
		return $note;
	} // End revisions_note()

	/**
	 * display_revision_notice function.
	 *
	 * @access public
	 * @return void
	 */
	function display_revision_notice () {
		global $post, $woo_options;
		if ( isset( $_GET['rev'] ) && in_the_loop() ) {
			$rev_id = intval( $_GET['rev'] );
			$revision = get_post( $rev_id );
			if ( $revision->post_parent == $post->ID ) { $is_rev = true; }
			
			if ( $is_rev == true ) { echo $this->revisions_note( $post, $revision, $rev_id, $is_rev ); }
		}
	} // End display_revision_notice()

	/**
	 * add_noindex_header function.
	 *
	 * @access public
	 * @return void
	 */
	function add_noindex_header () {
		global $post;
		if ( is_singular() && isset( $_GET['rev'] ) ) {
			$revision = get_post( intval( $_GET['rev'] ) );
			if ( $revision->post_parent == $post->ID ) {
				echo "\t\t" . '<meta name="robots" content="noindex, nofollow" />' . "\n";
			}
		}
	} // End add_noindex_header()

	/**
	 * filter_content function.
	 *
	 * @access public
	 * @param string $content
	 * @return string $content
	 */
	function filter_content ( $content ) {
		// Don't show revisions on page templates, other than the "full width" page template.
		if ( is_page_template() && ! is_page_template( 'template-fullwidth.php' ) ) { return $content; }
		
		remove_filter( 'the_title', array( &$this, 'filter_title' ), 10 ); // We're done with the_title filter, so remove it.
		remove_action( 'the_post', array( &$this, 'display_revision_notice' ), 10 ); // We're done with the_post action, so remove it.
		
		if ( ! is_singular() ) { return $content; }
		
		global $post, $woo_options;
		if ( isset( $_GET['rev'] ) ) {
			$rev_id = intval( $_GET['rev'] );
			$revision = get_post( $rev_id );
			if ( $revision->post_parent == $post->ID ) { $is_rev = true; }

			if ( isset( $revision->post_content ) ) {
				$content = get_the_content( $rev_id );
				
				if ( isset( $woo_options['woo_auto_revisions_list'] ) && ( apply_filters( 'woo_auto_revisions_list', $woo_options['woo_auto_revisions_list'] ) != 'false' )  ) {
					$content .= $this->revisions_list( $post, array( 'rev_id' => $rev_id ) );
				}
				
				if ( isset( $woo_options['woo_auto_revisions_diff'] ) && ( apply_filters( 'woo_auto_revisions_diff', $woo_options['woo_auto_revisions_diff'] ) != 'false' )  ) {
					$content .= $this->get_diffs( $post, $revision );
				}
			}
		} else {
			if ( isset( $woo_options['woo_auto_revisions_list'] ) && ( apply_filters( 'woo_auto_revisions_list', $woo_options['woo_auto_revisions_list'] ) != 'false' )  ) {
				$content .= $this->revisions_list( $post );
			}
		}
		
		return $content;
	} // End filter_content()

	/**
	 * filter_title function.
	 *
	 * @access public
	 * @param string $title
	 * @return string $title
	 */
	function filter_title ( $title ) {
		if ( ! is_singular() ) { return $title; }

		$is_rev = false;
		global $post;
		if ( isset( $_GET['rev'] ) ) {
			$revision = get_post( intval( $_GET['rev'] ) );
			if ( ( $revision->post_parent == $post->ID ) && ( strip_tags( strtolower( $title ) ) == strtolower( $post->post_title ) ) ) { $is_rev = true; }
			
			if ( isset( $revision->post_title ) && ( $is_rev == true ) ) { $title = $revision->post_title; }
		}
		return $title;
	} // End filter_title()
} // End Class
?>