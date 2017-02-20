<div class="about-author">
	<?php
    $aAuthorInfo = get_user_meta( $post->post_author );
    $aMetaData   = array();
    $social      = "";
    $author      = "";

    if ( !empty($aAuthorInfo) && !is_wp_error($aAuthorInfo) )
    {
        foreach ( $aAuthorInfo as $key => $aData )
        {
            $aMetaData[$key] = $aData[0];
        }
    }


    if ( !empty($aMetaData) )
    {
        if ( isset($aMetaData['pi_user_info']) )
        {
            $aAuthorData = unserialize($aMetaData['pi_user_info']);

            if ( isset($aAuthorData['avatar']) )
            {
                $avatar = '<div class="image-thumb fl"><img src="'.esc_url($aAuthorData['avatar']).'"  alt="'. esc_attr( $aMetaData['nickname'] ) .'"></div>';
            }
            $author .= $avatar;
        }


        if ( isset($aAuthorData['toggle_social']) && !empty($aAuthorData['toggle_social']) )
        {
            $social .= '<div class="author-social">';
                foreach ( piConfigs::$aConfigs['configs']['rest']['follow'] as $key => $aInfo  )
                {
                    if ( isset($aAuthorData['social_link'][$key]) && !empty($aAuthorData['social_link'][$key]) )
                    {
                        $social .= '<a target="_blank" href="'.esc_url($aAuthorData['social_link'][$key]).'"><i class="'.esc_attr($aInfo[0]).'"></i></a>';
                    }
                }
            $social .= '</div>';
        }

        $author .= '<div class="author-info">';
            $author .='<div class="author-name">';
                $author .='<h4 class="text-uppercase"><a href="'.esc_url(pi_get_author_page($post->ID)).'">'.$aMetaData['nickname'].'</a></h4>';
            $author .='</div>';
            $author .= $social;
            if ( !empty($aMetaData['description']) )
            {
                $author .= '<div class="author-content"><p>'.$aMetaData['description'].'</p></div>';
            }else{
                $author .= sprintf( (__('<a href="%s" target="_blank" class="pi-edit-profile"><code>Edit your profile</code> or check this <a href="%s" target="_blank"><code>video</code></a> to know more', 'wiloke')), admin_url('profile.php'), 'https://youtu.be/bC_dOKfCm4A' );
            }
        $author .= '</div>';

        print $author;
    }else{
        printf( (__('<a href="%s" target="_blank" class="pi-edit-profile">Edit your profile</a>', 'wiloke')), admin_url('profile.php') );
    }

	?>
</div>