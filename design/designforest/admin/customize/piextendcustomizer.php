<?php
/**
 * This file contains all of classes, which extend wordpress customize
 * @since 1.0
 */

if ( !defined('ABSPATH') )
{
    die();
}

if ( class_exists('WP_Customize_Control') )
{
    /**
     * Textarea
     * @since 1.0
     */
    class piTextarea extends WP_Customize_Control
    {
        public $type = 'textarea';
        public $des;
        public $has_button = false;
        public $button_class="";
        public $button_name = "Button";

        public function render_content()
        {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php
                if( !empty($this->des) )
                {
                    printf(  ( __( ('<p>%s</p>'), 'wiloke')),  $this->des);
                }
                $value = stripslashes( $this->value() );
                ?>
                <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_attr($value); ?></textarea>
                <?php
                if ( $this->has_button )
                {
                    ?>
                    <button style="margin-top:20px" class="button button-primary <?php echo esc_attr($this->button_class); ?>"><?php echo esc_html($this->button_name);  ?></button>
                <?php
                }
                ?>
            </label>
        <?php
        }
    }


    /**
     * Multiple select
     * @since 1.0
     */
    class piMultipleSelect extends WP_Customize_Control
    {
        public $type = 'pi_multiple_select';
        public $des;
        public $options;
        public $has_button = false;
        public $button_class="";
        public $button_name = "Button";

        public function render_content()
        {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php
                if( !empty($this->des) )
                {
                    printf(  ( __( ('<p>%s</p>'), 'wiloke')),  $this->des);
                }

                if ( !empty($this->options) )
                {
                    ?>
                    <select <?php $this->link(); ?> multiple>
                        <?php
                        foreach($this->options as $key => $name)
                        {
                            ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php if ( is_array($this->value()) && in_array($key, $this->value()) ){ echo 'selected'; } ?>><?php echo esc_html($name); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                <?php
                }else{
                    echo '<code>You haven\'t created any category yet.</code>';
                }
                ?>
            </label>
        <?php
        }
    }

    /**
     * creating the title for a group
     * @since 1.0
     */
    class piTitle extends WP_Customize_Control
    {
        public $type = "title";
        public function render_content()
        {
            ?>
            <h3 class="pi-title"><?php print $this->label; ?></h3>
            <?php
        }
    }

    /**
     * using this class if you want to display a description
     * @since 1.0
     */
    class piDescription extends WP_Customize_Control
    {
        public $type="description";

        public function render_content()
        {
            ?>
            <p><?php print wp_unslash($this->label); ?></p>
        <?php
        }
    }
}
?>