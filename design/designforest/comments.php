<div id="comments">
    <div class="comments-inner-wrap">
        <?php if (post_password_required()) : ?>
        <p><?php _e( 'Post is password protected. Enter the password to view any comments.', 'wiloke' ); ?></p>
    </div>
</div>
<?php return; endif; ?>
<?php if (have_comments()) : ?>
    <h3 id="comments-title" class="text-uppercase"><?php comments_number(); ?></h3>
    <?php pi_comment_nav(); ?>
    <ul class="commentlist">
        <?php wp_list_comments('type=comment&callback=pi_comment'); // Custom callback in functions.php ?>
    </ul>
<?php endif; ?>
<?php if ( !comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
    <p class="pi-comment-closed"><?php _e( 'Comments are closed here.', 'wiloke' ); ?></p>
    </div>
    </div>
<?php else : ?>
    <!-- LEAVER YOUR COMMENT -->
    <?php
    $commenter = wp_get_current_commenter();
    $commenter['comment_author'] = $commenter['comment_author'] == '' ? '': $commenter['comment_author'];
    $commenter['comment_author_email'] = $commenter['comment_author_email'] == '' ? '': $commenter['comment_author_email'];
    $commenter['comment_author_url'] = $commenter['comment_author_url'] == '' ? '': $commenter['comment_author_url'];

    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $sao      = ( $req ? " *" : '' );
    $comment_args = array(
        'title_reply'       => __( 'LEAVE A COMMENT', 'wiloke' ),
        'fields' => apply_filters( 'comment_form_default_fields', array(
            'author' => '<div class="form-item form-name"><label for="author">Your name'.$sao.'</label><input type="text" id="author" name="author" tabindex="1" value="'.esc_attr($commenter['comment_author']).'" ' . $aria_req . ' /></div>',
            'email'  => '<div class="form-item form-email"><label for="email">Your email'.$sao.'</label><input type="text" id="email" name="email" tabindex="2" value="'.esc_attr($commenter['comment_author_email']).'" ' . $aria_req . ' /></div>',
            'url'    => '<div class="form-item form-website"><label for="url">Your webiste</label><input type="text" id="url" name="url"  tabindex="3" value="" ' . $aria_req . ' /></div>' )),
        'comment_field' => '<div class="form-item form-textarea"><label for="comment">Your Message</label><textarea id="comment" name="comment"  tabindex="4"	class="tb-eff"></textarea></div>',
        'comment_notes_after' => '',
        'comment_notes_before' => '',
        'logged_in_as'         => '<div class="form-item form-login-logout">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'wiloke' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post->ID ) ) ) ) . '</div>'
    );
    ?>
    </div>
    </div>
    <?php
        comment_form($comment_args, $post->ID);
    ?>

    <!-- END / LEAVER YOUR COMMENT -->
<?php endif; ?>